<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';

	class Getitem {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function giftcon() {
            $query_timedone1 = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `timedone`='yes' WHERE `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
            $query_timedone2 = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `timedone`='yes' WHERE `howtobuy`='lots' AND `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_getitem WHERE `type`='giftcon' AND `pointdone`!='yes' AND date(`starttime`)<=date(now()) AND date(`endtime`)>date(now()) ORDER BY `starttime` DESC ");
            while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

		public function delivery() {
            $query_timedone1 = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `timedone`='yes' WHERE `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
            $query_timedone2 = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `timedone`='yes' WHERE `howtobuy`='lots' AND `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_getitem WHERE `type`='delivery' AND `pointdone`!='yes' AND date(`starttime`)<=date(now()) AND date(`endtime`)>date(now()) ORDER BY `starttime` DESC ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

		public function deadline() {
            $query_timedone1 = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `timedone`='yes' WHERE `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
            $query_timedone2 = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `timedone`='yes' WHERE `howtobuy`='lots' AND `pointdone`!='yes' AND date(`endtime`)<=date(now()) ");
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_getitem WHERE `pointdone`!='yes' AND date(`starttime`)<=date(now()) AND date(`endtime`)>date(now()) AND DATEDIFF(date(`endtime`),date(now()))<=5 ORDER BY `endtime` ASC ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

	}
	
	$getitem = new Getitem();
	
	try {
            $purpose = $_GET["purpose"];
                         
            switch($purpose){
            	case "giftcon":
            		$getitem-> giftcon();
            		break;
            	case "delivery":
            		$getitem-> delivery();
            		break;
            	case "deadline":
            		$getitem-> deadline();
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>