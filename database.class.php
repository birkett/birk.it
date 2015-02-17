<?php
/**
 * Basic class to interface with a SQLite database
 *
 * PHP Version 5.4
 *
 * @category  Database
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      http://birk.it
 */

namespace ABirkett;

use PDO;

class Database
{
    /**
     * Store the current link to avoid reconnections
     * @var object $mLink
     */
    private $mLink;

    /**
     * Constructor
     * @return none
     */
    public function __construct()
    {
        try {
            $this->mLink = new PDO('sqlite:'.DATABASE_FILENAME);
            $this->mLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Unable to connect to database';
        }

    }//end __construct()


    /**
     * Destructor
     * @return none
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
            $database = new Database();
        }

        return $database;

    }//end getInstance()


    /**
     * Run a query
     * @param string  $query  Query string to run.
     * @param mixed[] $params Array of parameters to bind.
     * @return mixed[] Array of results
     */
    public function runQuery($query, $params = array())
    {
        if (!$this->mLink) {
            return;
        }

        $statement = $this->mLink->prepare($query);
        $statement->execute($params);
        if ($statement->columnCount() != 0) {
            return $statement->fetchAll();
        }

    }//end runQuery()


    /**
     * Get single row from a result, until no results left
     * @param mixed[] $result Array of rows
     * @return mixed[] One row array or null when none left
     */
    public function getRow(&$result)
    {
        if ($result === false) {
            return;
        }

        if (count($result) != 0) {
            return array_shift($result);
        } else {
            return null;
        }

    }//end getRow()


}//end class
