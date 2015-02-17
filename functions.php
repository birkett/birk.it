<?php
/**
 * General site functions
 *
 * PHP Version 5.4
 *
 * @category  Functions
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      http://birk.it
 */

namespace ABirkett;

use ABirkett\PDOSQLiteDatabase as Database;

class Functions
{
    /**
     * Setup some default PHP settings
     * @return void
     */
    public static function PHPDefaults()
    {
        // Show PHP errors and warnings.
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        // Timezone for converting timestamps.
        date_default_timezone_set('Europe/London');

    }//end PHPDefaults()


    /**
     * Generate a random 8 character alphanumeric string
     * @return string
     */
    public static function generate()
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
    public static function addNewURL($url)
    {
        Database::getInstance()->runQuery(
            'INSERT INTO urls(original_url, short_url) VALUES(:inurl, :genurl)',
            array(
                ':inurl' => $url,
                ':genurl' => Functions::generate()
            )
        );

    }//end addNewURL()


    /**
     * Swap a long to short, or short to long URL
     * @param string $url URL to swap.
     * @return Opposite URL or false on not found
     */
    public static function swapURL($url)
    {
        if (preg_match('/'.BASIC_DOMAIN_NAME.'/i', $url) === 1) {
            $query = 'SELECT original_url FROM urls WHERE short_url=:url';
            $url   = substr($url, (strlen($url) - 8));
        } else {
            $query = 'SELECT short_url FROM urls WHERE original_url=:url';
        }

        $result = Database::getInstance()->runQuery(
            $query,
            array(':url' => $url)
        );
        $row = Database::getInstance()->getRow($result);
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
    public static function redirect($url)
    {
        http_response_code(302);
        header('Location: '.$url);
        exit();

    }//end redirect()


    /**
     * Exit the script with a given HTTP code and message
     * @param string  $msg  Message.
     * @param integer $code HTTP status code.
     * @return void
     */
    public static function finish($msg, $code)
    {
        http_response_code($code);
        exit($msg);

    }//end finish()


    /**
     * Replace a tag with a string (for inserting sub templates into the output)
     * @param string $tag    Tag to replace.
     * @param string $string String that will replace Tag.
     * @param string $output Unparsed template passed by reference.
     * @return void
     */
    public static function replaceTag($tag, $string, &$output)
    {
        $output = str_replace($tag, $string, $output);

    }//end replaceTag()
}//end class
