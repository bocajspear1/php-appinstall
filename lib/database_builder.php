<?php

class database_builder
	{
		private $connection;
		private $database_name;
		private $user_name;
		private $password;
		
		public function create($name)
			{
				$this->connect();
				
				$this->database_name = mysqli_real_escape_string($this->connection, DATABASE_PREFIX . $name);
				$this->user_name = mysqli_real_escape_string($this->connection, $this->database_name);
				$this->password = mysqli_real_escape_string($this->connection, str_replace("(DATABASE_NAME)", $name, PASSWORD_STRING));
				
				if (!$this->database_exists())
					{
						$this->add_user();
						$this->make_database();
						$this->set_privileges();
						return array('database'=>$this->database_name,'user'=>$this->user_name,'password'=>$this->password);
					}else{
						echo "ERROR: This database already exists";
					}
				
			}
		
		private function connect()
			{
				$this->connection = mysqli_connect("localhost",USERNAME,PASSWORD);

				if (!$this->connection) {
					die('Not connected : ' . mysqli_error());
				}
			}
		
		private function database_exists()
			{
				$db_selected = mysqli_select_db($this->connection, $this->database_name);
				if ($db_selected) {
					return true;
				}else{
					return false;
				}
				
			}
		
		private function make_database()
			{
				$sql = "CREATE DATABASE IF NOT EXISTS `" . $this->database_name . "`";
				$this->query($sql);
				$sql = "GRANT ALL PRIVILEGES ON `" . $this->database_name . "` . * TO '" . USERNAME . "'@'localhost' WITH GRANT OPTION" ;
				$this->query($sql);
			}
		
		private function add_user()
			{
				
				$user_sql = "CREATE USER '" . $this->user_name . "'@'localhost' IDENTIFIED BY '" . $this->password . "';";
				$this->query($user_sql);
				$password_sql = "SET PASSWORD FOR '" .  $this->user_name . "'@'localhost' = PASSWORD('" . $this->password . "');";
				$this->query($password_sql);
				
			}
			
		private function set_privileges()
			{
				$sql = "GRANT ALL PRIVILEGES ON `" . $this->database_name . "` . * TO '" . $this->user_name . "'@'localhost'";
				
				$this->query($sql);
			}
			
		private function query($sql)
			{
				if (mysqli_query($this->connection,$sql))
				  {
				  //echo "Query Successful";
				  }
				else
				  {
				  echo "ERROR:" . mysqli_error($this->connection);
				  }
			}
			
	}

?>

