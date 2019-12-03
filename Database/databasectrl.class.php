<?php

require(dirname(__DIR__) . "/database.class.php");

class DatabaseCtrl
{
	private $db;
	private $link = false;
	public static $verbose = false;

	function __construct(Database $database)
	{
		$this->db = $database;
	}

	public function connect()
	{
		$db = $this->db;
		if ($this->link == false)
			$this->link = mysqli_connect($db->address, $db->username, $db->password, $db->dbname, $db->port);
		else if (self::$verbose)
			$this->error_msg("A database connection is already live...");
	}

	private function error_msg(string $msg)
	{
		?>
		<style>
			.ErrorBanner {
				background-color: red;
				width:	100%;
				height:	200px;
			}
		</style>
			<h1 class="ErrorBanner">DATABASE ERROR</h1>
		<?php
		echo $msg . PHP_EOL;
	}
}

?>