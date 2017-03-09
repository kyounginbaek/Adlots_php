<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class Recommend {
		
		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}
		
		public function getinfo($nickname) {
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `nickname`='$nickname' ");
			$row = $query-> fetch_array(MYSQLI_ASSOC);
			$array = array(
	        				"recommend" => $row["recommend"],
	        				"recommend_number" => $row["recommend_number"],
	        				"recommend_point" => $row["recommend_point"]
    					);
			echo json_encode($array, JSON_UNESCAPED_UNICODE);
		}

		public function sendinfo($nickname, $str_recommend) {
			$query_user = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `nickname`='$str_recommend' ");
			if(mysqli_num_rows($query_user) > 0){
				$query_update = mysqli_query($this-> connection, "UPDATE adlots_users SET `recommend`='$str_recommend' , `userpoint`=`userpoint`+50 WHERE `nickname`='$nickname' ");
				$query_update2 = mysqli_query($this-> connection, "UPDATE adlots_users SET `recommend_number`=`recommend_number`+1 , `recommend_point`=`recommend_point`+50 , `userpoint`=`userpoint`+50 WHERE `nickname`='$str_recommend' ");

				$json['response'] = 'success';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
				mysqli_close($this-> connection);
			} else {
				$json['response'] = 'no_user';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
				mysqli_close($this-> connection);
			}
		}
	}
	
	$recommend = new Recommend();
	
	try {
			$purpose = $_GET['purpose'];

			$nickname = $body->nickname;
			$str_recommend = $body->str_recommend;

			switch($purpose){
				case "getinfo":
					$recommend-> getinfo($nickname);
					break;
            	case "sendinfo":
					$recommend-> sendinfo($nickname, $str_recommend);
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>