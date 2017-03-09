<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));
	
	class Refund {
		
		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function getitem($nickname, $howtobuy, $itemid){
			$query_useritem = mysqli_query($this-> connection, "SELECT * FROM adlots_useritem WHERE `nickname`='$nickname' AND `howtobuy`='$howtobuy' AND `itemid`='$itemid' ");
			if(mysqli_num_rows($query_useritem)>0){
				$row = $query_useritem-> fetch_array(MYSQLI_ASSOC);
				$array = array(
        			"userlotspoint" => $row["userlotspoint"],
        			"endpoint" => $row["endpoint"],
        			"nowpoint" => $row["nowpoint"]
    			);
				echo json_encode($array, JSON_UNESCAPED_UNICODE);
            	mysqli_close($this -> connection);
			}
		}

		public function refund($nickname, $howtobuy, $itemid, $userlotspoint){
			$query_useritem = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `refund`='yes' WHERE `nickname`='$nickname' AND `howtobuy`='$howtobuy' AND `itemid`='$itemid' ");
			$query_users = mysqli_query($this-> connection, "UPDATE adlots_users SET `userpoint`=`userpoint`+'$userlotspoint' WHERE `nickname`='$nickname' ");
		}
	}
	
	$refund = new Refund();

	try {
			$purpose = $_GET['purpose'];

			$nickname = $body->nickname;
			$howtobuy = $body->howtobuy;
			$itemid = $body->itemid;
			$userlotspoint = $body->userlotspoint;

			switch($purpose){
				case "getrefunditem":
					$refund-> getitem($nickname, $howtobuy, $itemid);
					break;
            	case "refund":
            		$refund-> refund($nickname, $howtobuy, $itemid, $userlotspoint);
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>