<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class Latest_Signin {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function latest_signin($userid) {
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE id='$userid' ");
			$row = $query-> fetch_array(MYSQLI_ASSOC);
			$latest = $row["latest_signin"];
			$today_signin = date('Y-m-d H:i:s');

			$str_Y = strcmp(substr($latest,0,4),substr($today_signin,0,4));
			$str_m = strcmp(substr($latest,5,2),substr($today_signin,5,2));
			$str_d = strcmp(substr($latest,8,2),substr($today_signin,8,2));
			
			if($str_Y==0 && $str_m==0 && $str_d==0) { //strcmp 일치 시 0 반환
			// latest_signin과 today_signin의 년,월,일이 똑같을 시, 이미 랏츠가 지급된 것으로 간주
				$array = array(
	        				"response" => 'signin_already',
	        				"latest_signin" => $today_signin
    					);
				echo json_encode($array, JSON_UNESCAPED_UNICODE);
			} else { // 매일매일 로그인 20랏츠 지급
				$query_update = mysqli_query($this-> connection, "UPDATE adlots_users SET `userpoint`=`userpoint`+20 , `signin_point`=`signin_point`+20 , `latest_signin`='$today_signin' WHERE `id`='$userid' ");
				$array = array(
	        				"response" => 'signin_point',
	        				"latest_signin" => $today_signin
    					);
				echo json_encode($array, JSON_UNESCAPED_UNICODE);
			}
		}
	}

	$Latest_signin = new Latest_Signin();

	try {
			$userid = $body->userid;
			$Latest_signin-> latest_signin($userid);

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>