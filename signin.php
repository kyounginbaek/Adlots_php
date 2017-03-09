<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));
	
	class User {
		
		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function signin($email, $password, $phone){
			$query_email = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `email`='$email' ");
			if(mysqli_num_rows($query_email) > 0){
				$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `email`='$email' AND `phone`='$phone' ");
				if(mysqli_num_rows($query) > 0){
					$row = $query-> fetch_array(MYSQLI_ASSOC);
					$password_hash = $row["password"];
					if(password_verify($password, $password_hash)){
						$array = array(
	        				"response" => "success",
	        				"nickname" => $row["nickname"],
	        				"id" => $row["id"]
    					);
						echo json_encode($array, JSON_UNESCAPED_UNICODE);
					} else {
						$json['response'] = 'wrong_password';
						echo json_encode($json, JSON_UNESCAPED_UNICODE);
					}
				} else {
					$json['response'] = 'different_phone';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);
					mysqli_close($this-> connection);
				}
			} else {
				$json['response'] = 'no_email';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
				mysqli_close($this-> connection);
			}
		}
	}
	
	$user = new User();

	try {
			$email = $body->email;
			$password = $body->password;
			$phone = $body->phone;

			$user-> signin($email, $password, $phone);

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>