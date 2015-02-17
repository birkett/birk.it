<?php
//-----------------------------------------------------------------------------
// Handler
//  Handles both AJAX and GET requests.
//-----------------------------------------------------------------------------
require_once("database.class.php");

use ABirkett\Database as Database;

function PHPDefaults()
{
    //Show PHP errors and warnings
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    //Timezone for converting timestamps
    date_default_timezone_set("Europe/London");
}

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

/**
* Replace a tag with a string (for inserting sub templates into the output)
* @param string $tag    Tag to replace
* @param string $string String that will replace Tag
* @param string $output Unparsed template passed by reference
* @return none
*/
function replaceTag($tag, $string, &$output)
{
    $output = str_replace($tag, $string, $output);
}
