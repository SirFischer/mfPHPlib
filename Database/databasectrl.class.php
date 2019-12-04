<?php

require(dirname(__DIR__) . "/../Base/Module.class.php");
require(dirname(__DIR__) . "/database.class.php");

class DatabaseCtrl extends Module
{
	private $db;
	private $link = false;

	function __construct(Database $database)
	{
		$this->db = $database;
		$this->connect();
	}

	public function __get($item)
	{
		if ('link' === $item)
			return ($this->link);
		else
			$this->AddDiagnostic(false, "Error: Bad access to $item...");
	}

	public function connect()
	{
		$db = $this->db;
		if ($this->link == false)
		{
			if (!($this->link = mysqli_connect($db->address, $db->username, $db->password, $db->dbname, $db->port)))
			{
				$this->AddDiagnostic(false, "Error: Failed to connect to mysqli...");
				$this->AddDiagnostic(false, "Errno: " . mysqli_connect_errno());
				$this->AddDiagnostic(false, "Error: " . mysqli_connect_error());
			} else
				$this->AddDiagnostic(true, "Success: Connection to database successful!");
		} else
			$this->AddDiagnostic(false, "Warning: Database already running...");
	}
}

?>