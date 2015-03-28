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
 * @category  Config
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */

namespace ABirkett\classes;

/**
 * Defines a set of symbols, building a site configuration.
 *
 * As well as defining symbols, the site config is used to set PHP.ini options,
 * such as error_reporting, display_errors and date_default_timezone_set.
 *
 * The end goal for this file is to allow the site to move servers, and be back
 * up and running after making basic changes here. The essential part of this
 * is the database connection detail, which if set correctly, the site should be
 * opperational.
 *
 * @category  Config
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */
class Config
{


    /**
     * Set up the environment
     * @return void
     */
    public function __construct()
    {
        // Show PHP errors and warnings.
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        // Timezone for converting timestamps.
        date_default_timezone_set('Europe/London');
        // Where the site is hosted, should include protocol.
        define('DOMAIN_NAME', 'http://birk.it/');
        // Should be the bare domain, no protocol, subdomain or slashes.
        define('BASIC_DOMAIN_NAME', 'birk.it');
        // Database connection.
        define('DATABASE_FILENAME', 'sql/birkit.db');

    }//end __construct
}//end class
