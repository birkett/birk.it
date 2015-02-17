<?php
require_once("config.php");
require_once("functions.php");

PHPDefaults();

//-----------------------------------------------------------------------------
// Main logic
//-----------------------------------------------------------------------------
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
} else {
    //Render front page
    $page = file_get_contents("template/main.tpl");

    //Quotes to display as a tagline under the header
    $QUOTES = array(
        "Enter a long URL, get a nice short one back.",
        "The opposite of a Swedish pump.",
        "lolololol"
    );
    
    $page = replaceTag("{DOMAIN}", BASIC_DOMAIN_NAME, $page);
    $page = replaceTag("{YEAR}", date("Y"), $page);
    $page = replaceTag("{QUOTE}", $QUOTES[rand(0, count($QUOTES)-1)], $page);
    echo $page;
}
