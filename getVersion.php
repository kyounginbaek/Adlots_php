<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class GetVersion {
		
		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}
		
		public function getversion() {
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_version WHERE `id`='1' ");
			$row = $query-> fetch_array(MYSQLI_ASSOC);
			$array = array(
	        			"response" => $row["versionName"],
	        			"versionMessage" => $row["versionMessage"]
    				);
			echo json_encode($array, JSON_UNESCAPED_UNICODE);
		}
	}
	
	$getVersion = new GetVersion();
	
	try {
			$getVersion-> getversion();

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>