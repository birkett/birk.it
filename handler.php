<?php
//-----------------------------------------------------------------------------
// Handler
//  Handles both AJAX and GET requests.
//-----------------------------------------------------------------------------
require_once("config.php");
require_once("database.class.php");

use ABirkett\Database as Database;

//-----------------------------------------------------------------------------
// Generate()
//        Out: 8 character random alphanumeric string
//-----------------------------------------------------------------------------
function Generate()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < 8; $i++) {
        $result .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $result;
}

//-----------------------------------------------------------------------------
// AddNewURL
//        In: Long URL
//-----------------------------------------------------------------------------
function AddNewURL($inputurl)
{
    Database::getInstance()->runQuery(
        "INSERT INTO urls(original_url, short_url) VALUES(:inurl, :genurl)",
        array(
            ":inurl" => $inputurl,
            ":genurl" => Generate()
        )
    );
}

//-----------------------------------------------------------------------------
// SwapURL
//        In: URL
//        Out: The opposite URL or false on not found
//-----------------------------------------------------------------------------
function SwapURL($url)
{
    if (preg_match("/".BASIC_DOMAIN_NAME."/i", $url)) {
        $query = "SELECT original_url FROM urls WHERE short_url=:url";
        $url = substr($url, strlen($url)-8);
    } else {
        $query = "SELECT short_url FROM urls WHERE original_url=:url";
    }
    $result = Database::getInstance()->runQuery(
        $query,
        array(":url" => $url)
    );
    $row = Database::getInstance()->getRow($result);
    if (isset($row[0])) {
        return $row[0];
    }
    return false;
}

//-----------------------------------------------------------------------------
// Redirect()
//        In: URL
//-----------------------------------------------------------------------------
function Redirect($url)
{
    http_response_code(302);
    header('Location: ' . $url);
    exit();
}

//-----------------------------------------------------------------------------
// Finish()
//        In: Message to display and a HTTP status code. Message can be the URL
//        Out: Prints message
//-----------------------------------------------------------------------------
function Finish($msg, $code)
{
    http_response_code($code);
    exit($msg);
}

//-----------------------------------------------------------------------------
// Main logic
//-----------------------------------------------------------------------------
PHPDefaults();

if (isset($_POST['input'])) {
    //We are in generate mode
    if ($_POST['input'] == "") {
        Finish("URL cannot be blank", 400);
    }
    if (strlen($_POST['input']) > 2048) {
        Finish("Input URL too long!", 400);
    }

    //Remove http:// or https://
    $inputurl = preg_replace('#^https?://#', '', strtolower($_POST['input']));
    //Remove a trailing backslash
    $inputurl = rtrim($inputurl, "/");

    //Check if link is already shortened, and return the full URL instead
    if (preg_match("/".BASIC_DOMAIN_NAME."/i", $inputurl)) {
        $original = SwapURL($inputurl);
        if ($original) {
            Finish("http://".$original, 200); //Add the http back in
        }
        Finish("Not found!", 400);
    }

    if (!SwapURL($inputurl)) {
        AddNewUrl($inputurl);
    }
    Finish(DOMAIN_NAME . SwapURL($inputurl), 200);
} elseif (isset($_GET['url'])) {
    //We are in fetch mode
    $originalurl = SwapURL(DOMAIN_NAME . "/" . $_GET['url']);

    if ($originalurl) {
        Redirect("http://".$originalurl); //If found, redicrect
    } else {
        Redirect(DOMAIN_NAME); //Not found, go home
    }
}
Redirect(DOMAIN_NAME); //Do nothing when not called correctly
