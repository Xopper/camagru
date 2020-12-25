<?php


class loadDB {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;
    
    public function __construct(){
        // Set DataBase Source Name
        $dsn = 'mysql:host=' .$this->host;
        $options = array(
          PDO::ATTR_PERSISTENT => true,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try{
          $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
          // echo "DB Connected";
          } 
          catch(PDOExeption $e){
          $this->error = $e->getMessage();
          echo $this->error;
          }
		}
		
        public function createDb(){
            $this->stmt = $this->dbh->prepare('CREATE DATABASE IF NOT EXISTS ok');
            $this->stmt->execute();
		}
		
        public function createTables() {
            // accounts
            $this->stmt = $this->dbh->prepare('use ok');
            $this->stmt->execute();

            $this->stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS `accounts` (
                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `active` int(11) NOT NULL DEFAULT '0',
                `email` varchar(255) NOT NULL,
                `vkey` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `email_notif` tinyint(11) DEFAULT NULL,
                `created_at` date NOT NULL
              )");
            $this->stmt->execute();
            
            // Comments
            // $this->stmt = $this->dbh->prepare('use ok');
            // $this->stmt->execute();
            $this->stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS `comments` (
                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `post_parent_id` int(11) NOT NULL,
                `comment_parent_id` int(11) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `comment` varchar(255) NOT NULL
              )");
            $this->stmt->execute();

                //likes
            // $this->stmt = $this->dbh->prepare('use ok');
            // $this->stmt->execute();
            $this->stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS `likes` (
                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `like_parent_id` int(11) NOT NULL
              ) ");
            $this->stmt->execute();

            // Postes
            // $this->stmt = $this->dbh->prepare('use ok');
            // $this->stmt->execute();
            $this->stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS`posts` (
                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `user_id` int(11) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
              )");
            $this->stmt->execute();
            
            // Comments
            // $this->stmt = $this->dbh->prepare('use ok');
            // $this->stmt->execute();
            $this->stmt = $this->dbh->prepare("CREATE TABLE IF NOT EXISTS `comments` (
                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `post_parent_id` int(11) NOT NULL,
                `comment_parent_id` int(11) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `comment` varchar(255) NOT NULL
              )");
            $this->stmt->execute();
            
        }
    }
    
    $trigger = new loadDB();
    $trigger->createDb();
    $trigger->createTables();

/**
 * @link https://stackoverflow.com/a/32911188 
 */