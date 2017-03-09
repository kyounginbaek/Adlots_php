<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';

	class Getitem {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function giftcon() {
			$query = mysqli_query($this->connection, "SELECT * FROM adlots_getitem WHERE type='giftcon' ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

		public function delivery() {
			$query = mysqli_query($this->connection, "SELECT * FROM adlots_getitem WHERE type='delivery' ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

		public function deadline() {
			$query = mysqli_query($this->connection, "SELECT * FROM adlots_getitem WHERE type='delivery' ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

	}
	
	$getitem = new Getitem();
	
	try {
            $purpose = $_GET["purpose"];
             
            switch($purpose){
            	case "giftcon":
            		$getitem-> giftcon();
            		break;
            	case "delivery":
            		$getitem-> delivery();
            		break;
            	case "deadline":
            		$getitem-> deadline();
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>