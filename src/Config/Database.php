<?php
	class Database {
		private $host = "mysql";
		private $db_name = "events";
		private $username = "user";
		private $password = "password";
		public $conn;
		
		public function getConnection() {
			$this->conn = null;
			try {
				$dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
				$options = [
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false,
				];
				$this->conn = new PDO($dsn, $this->username, $this->password, $options);
			} catch(PDOException $exception) {
				// Log the detailed error into the PHP error log for later review
				error_log("Database Connection Error: " . $exception->getMessage());
				
				// Display error details only in a development environment
				if (getenv('APP_ENV') === 'development') {
					die("Connection error: " . $exception->getMessage());
				} else {
					// In production, show a generic error message
					die("A database connection error occurred.");
				}
			}
			return $this->conn;
		}
	}
