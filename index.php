<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Anthony Birkett
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * PHP Version 5.4
 *
 * @category  Pages
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */

namespace ABirkett;

require_once 'classes\Autoloader.php';

classes\Autoloader::init();
$siteFunctions = new \ABirkett\classes\Functions();

/*
 * Site front page and main logic.
 */

$longurl  = filter_input(INPUT_POST, 'input', FILTER_SANITIZE_URL);
$shorturl = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);

// Generate a new short URL.
if (isset($longurl) === true) {
    if ($longurl === '') {
        $siteFunctions->finish('URL cannot be blank', 400);
    }

    if (strlen($longurl) > 2048) {
        $siteFunctions->finish('Input URL too long!', 400);
    }

    // Remove http:// or https://.
    $inputurl = preg_replace('#^https?://#', '', strtolower($longurl));
    // Remove a trailing backslash.
    $inputurl = rtrim($inputurl, '/');

    // Check if link is already shortened, and return the full URL instead.
    if (preg_match('/'.BASIC_DOMAIN_NAME.'/', $inputurl) === 1) {
        $original = $siteFunctions->swapURL($inputurl);
        if ($original !== false) {
            // Add the http back in.
            $siteFunctions->finish('http://'.$original, 200);
        }

        $siteFunctions->finish('Not found!', 400);
    }

    if ($siteFunctions->swapURL($inputurl) === false) {
        $siteFunctions->addNewUrl($inputurl);
    }

    $siteFunctions->finish(DOMAIN_NAME.$siteFunctions->swapURL($inputurl), 200);
}//end if

// Redirect to a long URL from a given short URL.
if (isset($shorturl) === true) {
    $originalurl = $siteFunctions->swapURL(DOMAIN_NAME.'/'.$shorturl);

    if ($originalurl !== false) {
        // If found, redirect.
        $siteFunctions->redirect('http://'.$originalurl);
    } else {
        // Not found, go home.
        $siteFunctions->redirect(DOMAIN_NAME);
    }
}//end if

// Default to rendering the front page.
$page = file_get_contents('template/main.tpl');

// Quotes to display as a tagline under the header.
$QUOTES = array(
           'Enter a long URL, get a nice short one back',
           'The opposite of a Swedish pump',
           'lolololol',
          );

$siteFunctions->replaceTag('{DOMAIN}', BASIC_DOMAIN_NAME, $page);
$siteFunctions->replaceTag('{YEAR}', date('Y'), $page);
$siteFunctions->replaceTag(
    '{QUOTE}',
    $QUOTES[rand(0, (count($QUOTES) - 1))],
    $page
);
echo $page;
