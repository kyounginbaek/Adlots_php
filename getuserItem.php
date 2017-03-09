<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class GetuserItem {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function lots($nickname) {
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_useritem WHERE `nickname`='$nickname' AND `howtobuy`='lots' ORDER BY `when` DESC ");
			while($row = mysqli_fetch_assoc($query)){
            	$output_info[]= $row;  
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
		}

        public function purchase($nickname) {
            $query = mysqli_query($this-> connection, "SELECT * FROM adlots_useritem WHERE `nickname`='$nickname' AND `howtobuy`='purchase' ORDER BY `when` DESC ");
            while($row = mysqli_fetch_assoc($query)){
                $output_info[]= $row;
            }
            echo json_encode($output_info, JSON_UNESCAPED_UNICODE);
            mysqli_close($this -> connection);
        }
	}
	
	$getuserItem = new GetuserItem();
	
	try {
            $purpose = $_GET["purpose"];
            $nickname = $body->nickname;
             
            switch($purpose){
                case "lots":
                    $getuserItem-> lots($nickname);
                    break;
                case "purchase":
                    $getuserItem-> purchase($nickname);
                    break;
                default:
                    break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>