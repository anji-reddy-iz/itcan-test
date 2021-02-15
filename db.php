<?php
   class Db {
        // The database connection
        protected static $connection;

        /**
         * Connect to the database
         * 
         * @return bool false on failure / mysqli MySQLi object instance on success
         */
        public function connect() {    
            // Try and connect to the database
            if(!isset(self::$connection)) {
                // Load configuration as an array. Use the actual location of your configuration file
                //$dbhost="topo.cqwgbnckalgi.ap-south-1.rds.amazonaws.com";
				$dbhost="localhost";
				$port="3306";
				$dbuser="root";
				$dbpass="";
				$dbname="testdb";
                self::$connection = new mysqli($dbhost, $dbuser, $dbpass,$dbname);
            }

            // If connection was not successful, handle the error
            if(self::$connection === false) {
                // Handle error - notify administrator, log to a file, show an error screen, etc.
                
                $dbhost="ed-master-ap-south-1c.caqxxscym7tv.ap-south-1.rds.amazonaws.com";
				$port="3306";
				$dbuser="doodsadmin";
				$dbpass="steve_1947steamengineteslanetflixamazon";
				$dbname="doods";
                self::$connection = new mysqli($dbhost, $dbuser, $dbpass,$dbname);
            }

            if(self::$connection === false) {
                return false;
            }
            return self::$connection;
        }

        /**
         * Query the database
         *
         * @param $query The query string
         * @return mixed The result of the mysqli::query() function
         */
        public function query($query) {
            // Connect to the database
            $connection = $this -> connect();
            // Query the database
            //$connection->free();
            $result = $connection -> query($query);
            mysqli_next_result($connection);
            return $result;
        }
        public function clearStoredResults(){
            global $mysqli;

            do {
                if ($res = $mysqli->store_result()) {
                    $res->free();
                }
            } while ($mysqli->more_results() && $mysqli->next_result());

        }
        public function select($query) {
            $rows = array();
            $result = $this -> query($query);
            if($result === false) {
                return false;
            }
            while ($row = $result -> fetch_assoc()) {
                $rows[] = $row;
            }
           // mysqli_free_result();
            //mysqli_next_result($this->$connection);
            return $rows;
        }
        public function multiQuery($query) {
            // Connect to the database
            $connection = $this -> connect();
            $result = $connection -> multi_query($query);
            $results=array();
            if($result===false)
            {
             return false;
            }else {
                do {
                    /* store first result set */
                    $rows=array();
                    if ($result = $connection->store_result()) {
                        while ($row = $result->fetch_array()) {
                            $rows[]=$row;
                        }
                        $results[]=$rows;
                        $result->free();
                    }
                } while ($connection->next_result());
                mysqli_next_result($connection);
            }
            return $results;
        }
        public function selectDataSet($query) {
            $rows = array();
            $result = $this -> multiQuery($query);
            if($result === false) {
                return false;
            }
            return $result;
        }

        /**
         * Fetch the last error from the database
         * 
         * @return string Database error message
         */
        public function error() {
            $connection = $this -> connect();
            return $connection -> error;
        }
		
		public function insert_id() {
			$connection = $this -> connect();
            return $connection -> insert_id;
		}

        public function affected_records() {
            $connection = $this -> connect();
            return $connection -> affected_rows;
        }
        /*public function mysqli_next_result() {
            $connection = $this -> connect();
            return $connection -> mysqli_next_result($connection);
        }*/
        /**
         * Quote and escape value for use in a database query
         *
         * @param string $value The value to be quoted and escaped
         * @return string The quoted and escaped string
         */
        public function quote($value) {
            $connection = $this -> connect();
            return "'" . $connection -> real_escape_string($value) . "'";
        }
		public function escape($value) {
            $connection = $this -> connect();
            return $connection -> real_escape_string($value);
        }
    }
	

?>


