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
        "INSERT INTO urls(original_url, short_url) VALUES(:inputurl, :generated)",
        array(
            ":inputurl" => $inputurl,
            ":generated" => Generate()
        )
    );
}

//-----------------------------------------------------------------------------
// GetShortURL
//        In: Long URL
//        Out: false on not found, short URL string on success
//-----------------------------------------------------------------------------
function GetShortURL($longurl)
{
    $result = Database::getInstance()->runQuery(
        "SELECT short_url FROM urls WHERE original_url = :longurl",
        array(":longurl" => $longurl)
    );
    $row = Database::getInstance()->getRow($result);
    if (isset($row[0])) {
        return $row[0];
    }
    return false;
}

//-----------------------------------------------------------------------------
// GetOriginalURL
//        In: Short URL
//        Out: false on not found, long URL string on success
//-----------------------------------------------------------------------------
function GetOriginalURL($shorturl)
{
    $result = Database::getInstance()->runQuery(
        "SELECT original_url FROM urls WHERE short_url=:shorturl",
        array(":shorturl" => $shorturl)
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
        $short = substr($inputurl, strlen($inputurl)-8);
        $original = GetOriginalUrl($short);
        if ($original) {
            Finish("http://".$original, 200); //Add the http back in
        }
        Finish("Not found!", 400);
    }

    if (!GetShortURL($inputurl)) {
        AddNewUrl($inputurl);
    }
    Finish(DOMAIN_NAME . GetShortURL($inputurl), 200);
} elseif (isset($_GET['url'])) {
    //We are in fetch mode
    $originalurl = GetOriginalURL($_GET['url']);

    if ($originalurl) {
        Redirect("http://".$originalurl); //If found, redicrect
    }
    Redirect(DOMAIN_NAME); //Not found, go home
}
Redirect(DOMAIN_NAME); //Do nothing when not called correctly
