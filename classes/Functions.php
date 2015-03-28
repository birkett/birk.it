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
 * @category  Functions
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */

namespace ABirkett\classes;

use ABirkett\classes\PDOSQLiteDatabase as Database;

/**
 * Basic site fuincions.
 *
 * @category  Functions
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */
class Functions
{


    /**
     * Generate a random 8 character alphanumeric string
     * @return string Short URL.
     */
    public function generate()
    {
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < 8; $i++) {
            $result .= $chars[rand(0, (strlen($chars) - 1))];
        }

        return $result;

    }//end generate()


    /**
     * Add a new URL
     * @param string $url Long URL to add.
     * @return void
     */
    public function addNewURL($url)
    {
        Database::getInstance()->runQuery(
            'INSERT INTO urls(original_url, short_url) VALUES(:inurl, :genurl)',
            array(
             ':inurl'  => $url,
             ':genurl' => $this->generate(),
            )
        );

    }//end addNewURL()


    /**
     * Swap a long to short, or short to long URL
     * @param string $url URL to swap.
     * @return string Opposite URL or false on not found
     */
    public function swapURL($url)
    {
        $query = 'SELECT short_url FROM urls WHERE original_url=:url';

        if (preg_match('/'.BASIC_DOMAIN_NAME.'/i', $url) === 1) {
            $query = 'SELECT original_url FROM urls WHERE short_url=:url';
            $url   = substr($url, (strlen($url) - 8));
        }

        $result = Database::getInstance()->runQuery(
            $query,
            array(':url' => $url)
        );
        $row    = Database::getInstance()->getRow($result);
        if (isset($row[0]) !== false) {
            return $row[0];
        }

        return false;

    }//end swapURL()


    /**
     * Exit the script with a redirect
     * @param string $url URL to redirect to.
     * @return void
     */
    public function redirect($url)
    {
        http_response_code(302);
        header('Location: '.$url);

    }//end redirect()


    /**
     * Exit the script with a given HTTP code and message
     * @param string  $msg  Message.
     * @param integer $code HTTP status code.
     * @return void
     */
    public function finish($msg, $code)
    {
        http_response_code($code);
        echo $msg;

    }//end finish()


    /**
     * Replace a tag with a string (for inserting sub templates into the output)
     * @param string $tag    Tag to replace.
     * @param string $string String that will replace Tag.
     * @param string $output Unparsed template passed by reference.
     * @return void
     */
    public function replaceTag($tag, $string, &$output)
    {
        $output = str_replace($tag, $string, $output);

    }//end replaceTag()
}//end class
