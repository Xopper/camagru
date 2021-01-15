<?php

namespace System\Config;

use \System\DatabaseHandler;
use PDO;
use PDOException;

class Setup
{
	/**
	 * @var \PDO
	 * @link https://stackoverflow.com/a/32911188 
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
		if (!$this->isExists($DB_HOST, $dbname, $DB_USER, $DB_PASS))
		{
			$this->setup();
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

		/**
		 * Table structure for table `comments`
		 */
		self::$pdo->exec("CREATE TABLE `comments` (
			`id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL,
			`image_id` int(11) NOT NULL,
			`comment` text NOT NULL,
			`created_at` datetime NOT NULL
		  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		/**
		 * Table structure for table `images`
		 */
		self::$pdo->exec("CREATE TABLE `images` (
			`id` int(11) NOT NULL,
			`image_name` varchar(255) NOT NULL,
			`user_id` int(11) NOT NULL,
			`created_at` datetime NOT NULL
		  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		/**
		 * Table structure for table `likes`
		 */
		self::$pdo->exec("CREATE TABLE `likes` (
			`id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL,
			`image_id` int(11) NOT NULL
		  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		/**
		 * Table structure for table `users`
		 */
		self::$pdo->exec("CREATE TABLE `users` (
			`id` int(11) NOT NULL,
			`firstName` varchar(255) NOT NULL,
			`lastName` varchar(255) NOT NULL,
			`username` varchar(255) NOT NULL,
			`password` varchar(255) NOT NULL,
			`email` varchar(255) NOT NULL,
			`confirmation_token` varchar(60) DEFAULT NULL,
			`confirmed_at` datetime DEFAULT NULL,
			`notification_on` tinyint(1) NOT NULL,
			`reset_token` varchar(60) DEFAULT NULL,
			`reset_at` datetime DEFAULT NULL,
			`remember_token` varchar(255) DEFAULT NULL
		  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		/**
		 * Indexes for dumped tables
		 */


		/**
		 * Indexes for table `comments`
		 */
		self::$pdo->exec("ALTER TABLE `comments` ADD PRIMARY KEY (`id`);");

		/**
		 * Indexes for table `images`
		 */
		self::$pdo->exec("ALTER TABLE `images` ADD PRIMARY KEY (`id`);");

		/**
		 * Indexes for table `likes`
		 */
		self::$pdo->exec("ALTER TABLE `likes` ADD PRIMARY KEY (`id`);");

		/**
		 * Indexes for table `users`
		 */
		self::$pdo->exec("ALTER TABLE `users` ADD PRIMARY KEY (`id`);");

		/**
		 * AUTO_INCREMENT for dumped tables
		 */

		/**
		 * AUTO_INCREMENT for table `comments`
		 */
		self::$pdo->exec("ALTER TABLE `comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		/**
		 * AUTO_INCREMENT for table `images`
		 */
		self::$pdo->exec("ALTER TABLE `images` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		/**
		 * AUTO_INCREMENT for table `likes`
		 */
		self::$pdo->exec("ALTER TABLE `likes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		/**
		 * AUTO_INCREMENT for table `users`
		 */
		self::$pdo->exec("ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; COMMIT;");
	}
}