<?php
/**
 * Site front page and logic
 *
 * PHP Version 5.4
 *
 * @category  Pages
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      http://birk.it
 */

namespace ABirkett;

require_once 'config.php';
require_once 'PDOSQLiteDatabase.php';
require_once 'functions.php';

Functions::PHPDefaults();

/*
    Main logic
*/

if (isset($_POST['input']) === true) {
    // We are in generate mode.
    $longurl = filter_input(INPUT_POST, 'input', FILTER_SANITIZE_URL);
    if ($longurl === '') {
        Functions::finish('URL cannot be blank', 400);
    }

    if (strlen($longurl) > 2048) {
        Functions::finish('Input URL too long!', 400);
    }

    // Remove http:// or https://.
    $inputurl = preg_replace('#^https?://#', '', strtolower($longurl));
    // Remove a trailing backslash.
    $inputurl = rtrim($inputurl, '/');

    // Check if link is already shortened, and return the full URL instead.
    if (preg_match('/'.BASIC_DOMAIN_NAME.'/', $inputurl) === 1) {
        $original = Functions::swapURL($inputurl);
        if ($original !== false) {
            // Add the http back in.
            Functions::finish('http://'.$original, 200);
        }

        Functions::finish('Not found!', 400);
    }

    if (Functions::swapURL($inputurl) === false) {
        Functions::addNewUrl($inputurl);
    }

    Functions::finish(DOMAIN_NAME.Functions::swapURL($inputurl), 200);
} elseif (isset($_GET['url']) === true) {
    // We are in fetch mode.
    $shorturl = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
    $originalurl = Functions::swapURL(DOMAIN_NAME.'/'.$shorturl);

    if ($originalurl !== false) {
        // If found, redirect.
        Functions::redirect('http://'.$originalurl);
    } else {
        // Not found, go home.
        Functions::redirect(DOMAIN_NAME);
    }
} else {
    // Render front page.
    $page = file_get_contents('template/main.tpl');

    // Quotes to display as a tagline under the header.
    $QUOTES = array(
        'Enter a long URL, get a nice short one back',
        'The opposite of a Swedish pump',
        'lolololol',
    );

    Functions::replaceTag('{DOMAIN}', BASIC_DOMAIN_NAME, $page);
    Functions::replaceTag('{YEAR}', date('Y'), $page);
    Functions::replaceTag(
        '{QUOTE}',
        $QUOTES[rand(0, (count($QUOTES) - 1))],
        $page
    );
    echo $page;
}//end if
