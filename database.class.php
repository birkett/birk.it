<?php
//-----------------------------------------------------------------------------
// Database class
//
//  Basic class to interface with a MySQLi database
//-----------------------------------------------------------------------------

class Database
{
	private $mLink; //Store the connection link
	private $host, $port, $user, $pass, $db;
	
	//-----------------------------------------------------------------------------
	// Constructor
	//		In: Database name
	//		Out: none
	//-----------------------------------------------------------------------------
	public function __construct($requesteddb)
	{
		$this->host = DATABASE_HOSTNAME;
		$this->port = DATABASE_PORT;
		$this->user = DATABASE_USERNAME;
		$this->pass = DATABASE_PASSWORD;
		$this->db = $requesteddb;

		$this->Connect($this->host, $this->port, $this->user, $this->pass, $this->db);
	}

	//-----------------------------------------------------------------------------
	// Destructor
	//		In: none
	//		Out: none
	//-----------------------------------------------------------------------------
	public function __destruct()
	{
		$this->Close();
	}
	
	//-----------------------------------------------------------------------------
	// Close a connection
	//		In: none
	//		Out: none
	//-----------------------------------------------------------------------------
	private function Close()
	{
		if($this->mLink) mysqli_close($this->mLink);
	}
	
	//-----------------------------------------------------------------------------
	// Create a connection
	//		In: Database connection details
	//		Out: none
	//-----------------------------------------------------------------------------
	public function Connect($host, $port, $user, $pass, $db)
	{
		$this->mLink = mysqli_connect($host .':'. $port, $user, $pass);
		if(!$this->mLink) { die("Unable to connect to database: " . mysqli_connect_error()); }
		
		$this->SelectDB($db);
	}
	
	//-----------------------------------------------------------------------------
	// Get MySQL server version info
	//		In: none
	//		Out: Version string
	//-----------------------------------------------------------------------------
	public function ServerInfo()
	{
		return mysqli_get_server_info($this->mLink);
	}
	
	//-----------------------------------------------------------------------------
	// Select a database
	//		In: Database name
	//		Out: none
	//-----------------------------------------------------------------------------
	private function SelectDB($db)
	{
		if(!$this->mLink) { return; }
		$result = mysqli_select_db($this->mLink, $db);
		if(!$result) { die("Unable to select database: " . mysqli_error($this->mLink)); }
	}
	
	//-----------------------------------------------------------------------------
	// Run a query
	//		In: Query string
	//		Out: MySQLi result
	//-----------------------------------------------------------------------------
	public function RunQuery($query)
	{
		if(!$this->mLink) { return; }
		$result = mysqli_query($this->mLink, $query);
		if(!$result) { die("Query failed: " . mysqli_error($this->mLink)); }
		return $result;
	}
	
	//-----------------------------------------------------------------------------
	// Get single row from a result
	//		In: MySQLi result
	//		Out: Single row
	//   Returns next row on each call until end, then NULL
	//-----------------------------------------------------------------------------
	public function GetRow($result)
	{
		return mysqli_fetch_row($result);
	}
	
	//-----------------------------------------------------------------------------
	// Get number of rows
	//		In: MySQLi result
	//		Out: Number of rows (can be fetched with GetRow()
	//-----------------------------------------------------------------------------
	public function GetNumRows($result)
	{
		return mysqli_num_rows($result);
	}
	
	//-----------------------------------------------------------------------------
	// Escape string
	//		In: Raw string
	//		Out: Escaped string
	//-----------------------------------------------------------------------------
	public function EscapeString($string)
	{
		return mysqli_real_escape_string($this->mLink, $string);
	}
}
?>