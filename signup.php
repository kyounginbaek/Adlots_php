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
		
		public function does_user_exist($email, $password, $phone, $nickname, $recommend) {
			$latest_signin = date('Y-m-d H-i-s');
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE phone='$phone' ");
			if(mysqli_num_rows($query) > 0) {
				$json['response'] = 'phone_exists';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);

			} else {
				$query_email = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE email='$email' ");
				$query_nickname = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE nickname='$nickname' ");

				if(mysqli_num_rows($query_email) > 0) {
					$json['reponse'] = 'email_exists';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);

				} else if (mysqli_num_rows($query_nickname) > 0){
					$json['response'] = 'nickname_exists';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);

				} else {
					if(empty($recommend)){ //추천인이 비어있을 경우, 기존과 똑같이 진행
						mysqli_query($this-> connection, "INSERT INTO adlots_users (`email`,`password`,`phone`,`nickname`,`when`) VALUES ('$email','$password','$phone','$nickname','$latest_signin') ");
						$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `email`='$email' AND `phone`='$phone' ");
						$row = $query-> fetch_array(MYSQLI_ASSOC);
						$array = array(
	        					"response" => "success",
	        					"id" => $row["id"]
    					);
						echo json_encode($array, JSON_UNESCAPED_UNICODE);
					} else { //추천인이 있을 경우, 추천인 아이디 검색 실시
						$query_user = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `nickname`='$recommend' ");
						if(mysqli_num_rows($query_user) > 0){
							mysqli_query($this-> connection, "INSERT INTO adlots_users (`email`,`password`,`phone`,`nickname`,`when`) VALUES ('$email','$password','$phone','$nickname','$latest_signin') ");
							$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `email`='$email' AND `phone`='$phone' ");
							$query_update = mysqli_query($this-> connection, "UPDATE adlots_users SET `recommend`='$recommend' , `userpoint`=`userpoint`+50 WHERE `nickname`='$nickname' ");
							$query_update2 = mysqli_query($this-> connection, "UPDATE adlots_users SET `recommend_number`=`recommend_number`+1 , `recommend_point`=`recommend_point`+50 , `userpoint`=`userpoint`+50 WHERE `nickname`='$recommend' ");

							$row = $query-> fetch_array(MYSQLI_ASSOC);
							$array = array(
	        							"response" => "success",
	        							"id" => $row["id"]
    								);
							echo json_encode($array, JSON_UNESCAPED_UNICODE);
							mysqli_close($this-> connection);
						} else {
							$json['response'] = 'no_recommend';
							echo json_encode($json, JSON_UNESCAPED_UNICODE);
							mysqli_close($this-> connection);
						}
					}
				}
			}
		}
	}
	
	$user = new User();
	
	try {
			$email = $body->email;
			$password = $body->password;
			$phone = $body->phone;
			$nickname = $body->nickname;
			$recommend = $body->recommend;

			$password = password_hash($password, PASSWORD_DEFAULT); 
			$user-> does_user_exist($email, $password, $phone, $nickname, $recommend);

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>