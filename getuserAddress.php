<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class GetuserAddress {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function getuseraddress($itemid, $nickname, $address, $type) {
			$query_address = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `address`='$address' WHERE `itemid`='$itemid' AND `nickname`='$nickname' AND `type`='$type' ");
            mysqli_close($this -> connection);
		}
	}
	
	$getuserAddress = new GetuserAddress();
	
	try {
            $itemid = $body->itemid;
            $nickname = $body->nickname;
            $address = $body->address;
            $type = $body->type;

            $getuserAddress-> getuseraddress($itemid, $nickname, $address, $type);

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>