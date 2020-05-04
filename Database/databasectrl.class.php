<?php

class DatabaseCtrl extends Module
{
	private $db;
	private $link = false;
	private $last_insert = -1;

	function __construct(Database $database)
	{
		$this->db = $database;
		$this->connect();
	}

	public function __get($item)
	{
		if ('link' === $item)
			return ($this->link);
		else if ('last_insert' === $item)
			return ($this->last_insert);
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

	public function		query(string $query, string $argtype = "", ...$args)
	{
		$this->last_insert = -1;
		if ($this->link === FALSE)
			$this->connect();
		if (!($stmt = mysqli_prepare($this->link, $query)))
		{
			$this->AddDiagnostic(false, "ERROR: Failed to prepare query...");
			$this->AddDiagnostic(false, mysqli_errno($this->link));
			$this->AddDiagnostic(false, mysqli_error($this->link));
			return (NULL);
		}
		else
			$this->AddDiagnostic(true, "Query prepared!");
		if (!(mysqli_stmt_bind_param($stmt, $argtype, ...$args)) && $argtype != "")
		{
			$this->AddDiagnostic(false, "ERROR: Failed to bind parameters to query...");
			$this->AddDiagnostic(false, "Errno: " . mysqli_stmt_errno($stmt));
			$this->AddDiagnostic(false, "Error: " . mysqli_stmt_error($stmt));
			return (NULL);
		}
		else
			$this->AddDiagnostic(true, "Query parameters bound!");
		if (!(mysqli_stmt_execute($stmt)))
		{
			$this->AddDiagnostic(false, "Error: Failed to execute query...");
			$this->AddDiagnostic(false, "Errno: " . mysqli_stmt_errno($stmt));
			$this->AddDiagnostic(false, "Error: " . mysqli_stmt_error($stmt));
		}
		else
			$this->AddDiagnostic(true, "Query succesful!");
		$res = mysqli_stmt_get_result($stmt);
		$this->last_insert = $stmt->insert_id;
		mysqli_stmt_close($stmt);
		return ($res);
	}
}

?>