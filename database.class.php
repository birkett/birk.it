<?php
//-----------------------------------------------------------------------------
// Database class
//
//  Basic class to interface with a MySQLi database
//-----------------------------------------------------------------------------

namespace ABirkett;

class Database
{
    private $mLink; //Store the connection link

    //-----------------------------------------------------------------------------
    // Constructor
    //        In: none
    //        Out: none
    //-----------------------------------------------------------------------------
    public function __construct()
    {
        try {
            $this->mLink = new PDO("sqlite:" . DATABASE_FILENAME);
            $this->mLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Unable to connect to database";
        }
    }

    //-----------------------------------------------------------------------------
    // Destructor
    //        In: none
    //        Out: none
    //-----------------------------------------------------------------------------
    public function __destruct()
    {
        $this->mLink = null;
    }

    //-----------------------------------------------------------------------------
    // getInstance
    //        In: none
    //        Out: none
    //-----------------------------------------------------------------------------
    public static function getInstance()
    {
        static $database = null;
        if (!isset($database)) {
            $database = new Database();
        }
        return $database;
    }

    //-----------------------------------------------------------------------------
    // Run a query
    //        In: Query string, Parameters array
    //        Out: Result
    //-----------------------------------------------------------------------------
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
    }

    //-----------------------------------------------------------------------------
    // Get single row from a result
    //        In: MySQLi result
    //        Out: Single row
    //   Returns next row on each call until end, then NULL
    //-----------------------------------------------------------------------------
    public function getRow(&$result)
    {
        if (!$result) {
            return;
        }
        if (count($result) != 0) {
            return array_shift($result);
        } else {
            return null;
        }
    }
}
