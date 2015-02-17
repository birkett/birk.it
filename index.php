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
require_once 'database.class.php';
require_once 'functions.php';

PHPDefaults();

/*
    Main logic
*/

if (isset($_POST['input']) === true) {
    // We are in generate mode.
    if ($_POST['input'] === '') {
        finish('URL cannot be blank', 400);
    }

    if (strlen($_POST['input']) > 2048) {
        finish('Input URL too long!', 400);
    }

    // Remove http:// or https://.
    $inputurl = preg_replace('#^https?://#', '', strtolower($_POST['input']));
    // Remove a trailing backslash.
    $inputurl = rtrim($inputurl, '/');

    // Check if link is already shortened, and return the full URL instead.
    if (preg_match('/'.BASIC_DOMAIN_NAME.'/', $inputurl) !== false) {
        $original = swapURL($inputurl);
        if ($original !== false) {
            // Add the http back in.
            finish('http://'.$original, 200);
        }

        finish('Not found!', 400);
    }

    if (swapURL($inputurl) === false) {
        addNewUrl($inputurl);
    }

    finish(DOMAIN_NAME.swapURL($inputurl), 200);
} elseif (isset($_GET['url']) === true) {
    // We are in fetch mode.
    $originalurl = swapURL(DOMAIN_NAME.'/'.$_GET['url']);

    if ($originalurl !== false) {
        // If found, redirect.
        redirect('http://'.$originalurl);
    } else {
        // Not found, go home.
        redirect(DOMAIN_NAME);
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

    replaceTag('{DOMAIN}', BASIC_DOMAIN_NAME, $page);
    replaceTag('{YEAR}', date('Y', $page);
    replaceTag('{QUOTE}', $QUOTES[rand(0, (count($QUOTES) - 1))], $page);
    echo $page;
}
