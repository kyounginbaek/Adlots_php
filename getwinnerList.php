<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';

	class GetwinnerList {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function winnerlist() {
			$query = mysqli_query($this->connection, "SELECT * FROM adlots_useritem WHERE `winorlose` = 'win' ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

	}
	
	$getwinnerList = new GetwinnerList();
	
	try {             
            $getwinnerList-> winnerlist();

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>