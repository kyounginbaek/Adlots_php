<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class GetuserPoint {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function userPoint($email) {
			$query = mysqli_query($this->connection, "SELECT * FROM adlots_users WHERE `email`='$email' ");
			if(mysqli_num_rows($query)>0){
            	$row = $query-> fetch_array(MYSQLI_ASSOC);
                $json['response'] = $row["userpoint"];
            }
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
		}
	}
	
	$getuserPoint = new GetuserPoint();
	
	try {
            $email = $body->email;
            $getuserPoint-> userPoint($email, $password);

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>