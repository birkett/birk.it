<?php
//-----------------------------------------------------------------------------
// Handler
//  Handles both AJAX and GET requests.
//-----------------------------------------------------------------------------
require_once("config.php");
require_once("database.class.php");

//-----------------------------------------------------------------------------
// GetDatabase()
//		Out: Returns a Database handle
//-----------------------------------------------------------------------------
function GetDatabase()
{
	static $db = NULL;
	if(!isset($db))	{ $db = new Database(DATABASE_NAME); }
	return $db;
}

//-----------------------------------------------------------------------------
// Generate()
//		Out: 8 character random alphanumeric string
//-----------------------------------------------------------------------------
function Generate()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < 8; $i++) { $result .= $chars[rand(0, strlen($chars) - 1)]; }
    return $result;
}

//-----------------------------------------------------------------------------
// AddNewURL
//		In: Long URL
//-----------------------------------------------------------------------------
function AddNewURL($inputurl)
{
	GetDatabase()->RunQuery("INSERT INTO urls(original_url, short_url) VALUES( '" . $inputurl . "','" . Generate() . "')");
}

//-----------------------------------------------------------------------------
// GetShortURL
//		In: Long URL
//		Out: false on not found, short URL string on success
//-----------------------------------------------------------------------------
function GetShortURL($longurl)
{
	$result = GetDatabase()->RunQuery("SELECT short_url FROM urls WHERE original_url ='" . $longurl . "'");
	$row = GetDatabase()->GetRow($result);
	if(isset($row[0])) { return $row[0]; } return false;
}

//-----------------------------------------------------------------------------
// GetOriginalURL
//		In: Short URL
//		Out: false on not found, long URL string on success
//-----------------------------------------------------------------------------
function GetOriginalURL($shorturl)
{
	$result = GetDatabase()->RunQuery("SELECT original_url FROM urls WHERE short_url='" . $shorturl . "'");
	$row = GetDatabase()->GetRow($result);
	if(isset($row[0])) { return $row[0]; } return false;
}

//-----------------------------------------------------------------------------
// Redirect()
//		In: URL
//-----------------------------------------------------------------------------
function Redirect($url)
{
	http_response_code(302);
	header( 'Location: '.$url );
	exit();
}

//-----------------------------------------------------------------------------
// Finish()
//		In: Message to display and a HTTP status code. Message can be the URL
//		Out: Prints message
//-----------------------------------------------------------------------------
function Finish($msg, $code)
{
	http_response_code($code);
	echo $msg;
	exit();
}

//-----------------------------------------------------------------------------
// Main logic
//-----------------------------------------------------------------------------
if(isset($_POST['input'])) //We are in generate mode
{
	if($_POST['input'] == "") //Sanity
		Finish("URL cannot be blank", 400);
		
	if(strlen($_POST['input']) > 2048) //Overflow
		Finish("Input URL too long!", 400);
		
	$inputurl = strtolower($_POST['input']);
			
	//Remove http:// or https://
	$inputurl = preg_replace('#^https?://#', '', $inputurl);
	//Remove a trailing backslash
	$inputurl = rtrim($inputurl,"/");
	
	//Check if link is already shortened, and return the full URL instead
	if(preg_match("/birk.it/i", $inputurl))
	{
		$short = substr($inputurl, strlen($inputurl)-8);
		$original = GetOriginalUrl($short);
		if($original)
			Finish($original, 200);
		Finish("Not found!", 400);
	}
	
	if (!GetShortURL($inputurl))
		AddNewUrl($inputurl);
	Finish(DOMAIN_NAME . GetShortURL($inputurl), 200);
}
else if(isset($_GET['url'])) //We are in fetch mode
{
	$originalurl = GetOriginalURL(GetDatabase()->EscapeString($_GET['url']));

	if($originalurl) //If found, redicrect
		Redirect($originalurl);
	Redirect(DOMAIN_NAME); //Not found, go home
}
exit(); //Do nothing when not called correctly
?>