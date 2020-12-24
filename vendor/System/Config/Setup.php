<?php

namespace System\Config;

use \System\DatabaseHandler;
use PDO;
use PDOException;

class Setup
{
	/**
	 * @var \PDO
	 */
	private static $pdo;

	/**
	 * Constructor 
	 * 
	 * @param $dbname
	 */
	public function __construct(string $dbname)
	{
		$this->dbName = $dbname;
		$this->init($dbname);
	}

	private function init($dbname)
	{
		require __DIR__ . "/database.php";
		echo "This is from Setup <br/>";
		if ($this->isExists($DB_HOST, $dbname, $DB_USER, $DB_PASS))
		{
			die("1");
		} else {
			$this->setup();
			die("O");
		}
	}

	/**
	 * check if the given database name exists or not
	 * 
	 * @param string $dbhost
	 * @param string $dbname
	 * @param string $dbuser
	 * @param string $dbpass
	 * @return bool
	 */
	private function isExists($dbhost, $dbname, $dbuser, $dbpass)
	{
		try {
			self::$pdo = new PDO(
				"mysql:host=$dbhost;dbname=INFORMATION_SCHEMA",
				"$dbuser",
				"$dbpass",
				array(
					PDO::MYSQL_ATTR_INIT_COMMAND	=> "SET NAMES UTF8",
					PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_NAMED,
				)
			);
		} catch(PDOException $e) {
			die($e->getMessage());
		}

		/**
		 * TODO check if the givent dbname exists
		 */

		// $pdo->exec("use INFORMATION_SCHEMA");

		$stmt = self::$pdo->prepare("SELECT SCHEMA_NAME FROM SCHEMATA WHERE SCHEMA_NAME = ?");
		$stmt->execute([$dbname]);
		$count = $stmt->rowCount();
		return $count ? true : false;
	}

	/**
	 * Setup all needed tables
	 */
	private function setup()
	{
		/**
		 * Create database
		 */
		self::$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbName}`");

		/**
		 * switch to the created database
		 */
		self::$pdo->exec("use `{$this->dbName}`");

		/**
		 * Create all tables needed for camagru
		 */
		
	}
}