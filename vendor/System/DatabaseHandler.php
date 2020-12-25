<?php

namespace System;

use PDO;
use PDOException;
use System\Config\Setup;

class DatabaseHandler
{
	private static $handler;
	private $app;

	/**
	 * INSERT/UPDATE properties
	 * start
	 */

	private $table;
	private $lastId;
	private $data = array();
	private $bindings = array();
	private $wheres = array();

	/**
	 * ends
	 */

	/**
	 * SELECT/DELETE properties
	 * start
	 */

	private $rows = 0;
	private $limit;
	private $offset;
	private $joins = array();
	private $selects = array();
	private $orderBy = array();

	/**
	 * end
	 */

	public function __construct(Application $app)
	{
		$this->app = $app;
		if (!$this->isConnected()) {
			$this->init();
		}
	}
	/**
	 * check if we have a connection with the db
	 * 
	 * @return bool
	 */
	private function IsConnected()
	{
		return static::$handler instanceof PDO;
	}
	private function init()
	{
		// pre($this->app->file->call("vendor/System/Config/database.php"));
		// die($DB_DSN);
		
		require __DIR__ . "/Config/database.php";

		/**
		 * Run the setup and check if the database name exists or not
		 * if not create it and all needed tables
		 */
		// new Setup($DB_NAME);
		
		// die(__DIR__ . "/Config/database.php");
		// echo "C÷onnected !";
		// echo $this->app->file->root . "/vendor/config/database.php";
		// $this->app->file->call("vendor/config/database.php");
		
		try {
			static::$handler = new PDO(
				$DB_DSN,
				$DB_USER,
				$DB_PASS,
				array(
					PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES UTF8",
					PDO::ATTR_ERRMODE                 => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE     => PDO::FETCH_OBJ,
				)
			);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
	/**
	 * get instance of PDO connection
	 * 
	 * @return \PDO
	 */
	public function getInstance()
	{
		return static::$handler;
	}

	/**
	 * SELECT Methodes
	 */

	public function table($table)
	{
		$this->table = $table;
		return $this;
	}
	public function from($table)
	{
		$this->table = $table;
		return $this;
	}
	/**
	 * Done
	 */
	public function data($key, $value = null)
	{
		if (is_array($key)) {
			$this->data = array_merge($this->data, $key);
			$this->addToBinding($key);
		} else {
			$this->data[$key] = $value;
			$this->addToBinding($value);
		}
		return $this;
	}
	public function where(...$bindings)
	{
		$sql = array_shift($bindings);
		$this->wheres[] = $sql;
		$this->addToBinding($bindings);
		return $this;
	}
	/**
	 * DML : Data Manipulation Language [INSERT, UPDATE, DELETE queries]
	 */
	public function insert($table = null)
	{
		if (isset($table)) {
			$this->table = $table;
		}
		$sql = "INSERT INTO " . $this->table . " SET ";
		$sql .= $this->setFields();
		// die($sql);
		$query = $this->query($sql, $this->bindings);
		$this->rows = $query->rowCount();
		$this->lastId = $this->getInstance()->lastInsertId();
		$this->reset();
		return $this;
	}
	/**
	 * DML : Data Manipulation Language [INSERT, UPDATE, DELETE queries]
	 */
	public function update($table = null)
	{
		if (isset($table)) {
			$this->table = $table;
		}
		$sql = "UPDATE " . $this->table . " SET ";
		$sql .= $this->setFields();
		if (!empty($this->wheres)) {
			$sql .= " WHERE " . implode(" ", $this->wheres); // set the array in one line str;
		}
		$query = $this->query($sql, $this->bindings);
		$this->rows = $query->rowCount();
		$this->reset();
		// $this->lastId = $this->getInstance()->lastInsertId();
		return $this;
	}
	/**
	 * Set Fields for INSERT and UPDATE methdes
	 * @return string
	 */
	private function setFields()
	{
		$sql = "";
		foreach (array_keys($this->data) as $key) {
			$sql .= '`' . $key . '` =  ? , ';
		}
		$sql = rtrim($sql, ", ");
		return $sql;
	}
	/**
	 * Needs to fit with my old Employee model
	 * like we need to add a table schema and get it from the users .e.g Model
	 * and it will be generic
	 */
	private function addToBinding($value)
	{
		if (is_array($value)) {
			$this->bindings = array_merge($this->bindings, array_values($value));
		} else {
			$this->bindings[] = $value;
		}
	}
	/**
	 * Return Last insserted Id in data base
	 * 
	 * @return int
	 */
	public function lastId()
	{
		return $this->lastId;
	}
	public function query(...$bindings)
	{
		$sql = array_shift($bindings);
		if (is_array($bindings[0]) and count($bindings) === 1) {
			$bindings = $bindings[0];
		}
		try {
			$stmt = $this->getInstance()->prepare($sql);
			foreach ($bindings as $key => $value) {
				/**
				 * be carefull when you gonna use named Params
				 * because you stor values in an indexed array
				 */
				$stmt->bindValue($key + 1, _e($value));
			}
			$stmt->execute();
		} catch (PDOException $e) {
			pre($this->bindings);
			echo $sql . "<br>";
			die($e->getMessage());
		}
		return $stmt;
	}
	/**
	 * the SELECT statement will be like that
	 * SELECT COLUMNS(*) FROM TABLE_NAME LEFT JOIN TABLE_NAME_2 ON ... WHERE ... 
	 * LIMIT NUMBER OFFSET NUMBER ORFER BY ... 
	 */

	public function select($select)
	{
		$this->selects[] = $select;
		return $this;
	}
	public function limit($limit, $offset = 0)
	{
		$this->limit = $limit;
		$this->offset = $offset;
	}
	public function join($join)
	{
		$this->joins = $join;
		return $this;
	}
	public function orderBy($orderBy, $sort = "ASC")
	{
		$this->orderBy = [$orderBy, $sort];
		return $this;
	}
	/**
	 * DML : Data Manipulation Language [INSERT, UPDATE, DELETE queries]
	 * DELETE FROM table_name WHERE condition;
	 */
	public function delete($table = null)
	{
		if (isset($table)) {
			$this->table = $table;
		}
		$sql = "DELETE FROM `" . $this->table . "`";
		if (!empty($this->wheres)) {
			$sql .= " WHERE " . implode(" ", $this->wheres);
		}
		// die($sql);
		$query = $this->query($sql, $this->bindings);
		$this->rows = $query->rowCount();
		$this->reset();
		return $this;
	}
	public function fetch($table = null)
	{
		if (isset($table)) {
			$this->table = $table;
		}
		$sql = $this->fetchStatement();
		// die($sql);
		$stmt = $this->query($sql, $this->bindings);
		$result = $stmt->fetch();
		$this->rows = $stmt->rowCount();
		$this->reset();
		return $result;
	}
	public function fetchAll($table = null)
	{
		if (isset($table)) {
			$this->table = $table;
		}
		$sql = $this->fetchStatement();
		// die($sql);

		$stmt = $this->query($sql, $this->bindings);
		$results = $stmt->fetchAll();
		$this->rows = $stmt->rowCount();
		$this->reset();
		return $results;
	}

	private function fetchStatement()
	{
		$sql = "SELECT ";
		if (!empty($this->selects)) {
			$sql .= implode(",", $this->selects);
		} else {
			$sql .= "*";
		}
		$sql .= " FROM `" . $this->table . "` ";
		if (!empty($this->joins)) {
			$sql .= implode(" ", $this->joins);
		}
		if (!empty($this->wheres)) {
			$sql .= " WHERE " . implode(" ", $this->wheres);
		}
		if (isset($this->limit)) {
			$sql .= " LIMIT " . $this->limit;
		}
		if (isset($this->offset)) {
			$sql .= " OFFSET " . $this->offset;
		}
		if (isset($this->orderBy[0])) {
			$sql .= " ORDER BY " . implode(" ", $this->orderBy);
		}
		return $sql;
	}
	public function rows()
	{
		return $this->rows;
	}
	private function reset()
	{
		$this->data = array();
		$this->bindings = array();
		$this->wheres = array();
		$this->table = null;

		$this->joins = array();
		$this->selects = array();
		$this->orderBy = array();
		$this->limit = null;
		$this->offset = null;
	}
}
