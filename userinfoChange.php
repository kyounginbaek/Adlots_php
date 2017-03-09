<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class UserinfoChange {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function newemail($newemail, $originalemail) {
			$query_check = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE email='$newemail' ");
			if(mysqli_num_rows($query_check) > 0) {
				$json['response'] = 'email_exists';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
			} else {
				$query = mysqli_query($this-> connection, "UPDATE adlots_users SET `email`='$newemail' WHERE `email`='$originalemail' ");
			}
		}

		public function newpassword($newpassword, $originalemail) {
			$newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
			$query = mysqli_query($this-> connection, "UPDATE adlots_users SET `password`='$newpassword' WHERE `email`='$originalemail' ");
		}

		public function newboth($newemail, $newpassword, $originalemail) {
			$query_check = mysqli_query($this-> connection, "SELECT * FROM adlots_users WHERE `email`='$newemail' ");
			if(mysqli_num_rows($query_check) > 0) {
				$json['response'] = 'email_exists';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
			} else {
				$newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
				$query = mysqli_query($this-> connection, "UPDATE adlots_users SET `email`='$newemail' , `password`='$newpassword' WHERE `email`='$originalemail' ");
			}
		}
	}

	$userinfoChange = new UserinfoChange();

	try {
			$purpose = $_GET['purpose'];

			$newemail = $body->newemail;
			$newpassword = $body->newpassword;
			$originalemail = $body->originalemail;

			switch($purpose){
				case "newemail":
					$userinfoChange-> newemail($newemail, $originalemail);
					break;
            	case "newpassword":
					$userinfoChange-> newpassword($newpassword, $originalemail);
            		break;
            	case "newboth":
					$userinfoChange-> both($newemail, $newpassword, $originalemail);
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>