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
 * @category  Database
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */

namespace ABirkett\classes;

use PDO;

/**
 * Basic class to interface with a SQLite database
 *
 * @category  Classes
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */
class PDOSQLiteDatabase
{

    /**
     * Store the current link to avoid reconnections
     * @var object $mLink
     */
    private $mLink;


    /**
     * Constructor
     */
    public function __construct()
    {
        try {
            $this->mLink = new PDO('sqlite:'.DATABASE_FILENAME);
            $this->mLink->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (\PDOException $e) {
            echo 'Unable to connect to database';
        }

    }//end __construct()


    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->mLink = null;

    }//end __destruct()


    /**
     * Open a database handle
     * @return object Database handle
     */
    public static function getInstance()
    {
        static $database = null;
        if (isset($database) === false) {
            $database = new PDOSQLiteDatabase();
        }

        return $database;

    }//end getInstance()


    /**
     * Run a query
     * @param string $query  Query string to run.
     * @param array  $params Array of parameters to bind.
     * @return array Array of results
     */
    public function runQuery($query, array $params)
    {
        if ($this->mLink === null) {
            return array();
        }

        $statement = $this->mLink->prepare($query);
        $statement->execute($params);
        if ($statement->columnCount() !== 0) {
            $rows = $statement->fetchAll();

            return $rows;
        }

        return array();

    }//end runQuery()


    /**
     * Get single row from a result, until no results left
     * @param  array $result Array of rows.
     * @return void One row array or null when none left
     */
    public function getRow(array &$result)
    {
        if ($result === null) {
            return null;
        }

        if (count($result) !== 0) {
            return array_shift($result);
        }

        return null;

    }//end getRow()
}//end class
