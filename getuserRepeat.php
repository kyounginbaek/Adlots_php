<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';

	class GetuserRepeat {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function userrpeat($itemid, $nickname) {
			$query_userrepeat = mysqli_query($this->connection, "SELECT * FROM adlots_useritem WHERE `itemid`='$itemid' AND `nickname`='$nickname' ");
			if(mysqli_num_rows($query_userrepeat) > 0){
            	$json['response'] = 'yes';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
            } else {
            	$json['response'] = 'no';
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
            }
		}

	}
	
	$getuserRepeat = new GetuserRepeat();
	
	try {   
			$itemid = $body->itemid;
			$nickname = $body->nickname;

            $getuserRepeat-> userrpeat($itemid, $nickname);

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>