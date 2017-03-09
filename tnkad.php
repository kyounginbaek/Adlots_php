<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class TnkAd {
		
		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}
		
		public function tnkad($id, $tnkpoint) {
			$query_tnkpoint = mysqli_query($this-> connection, "UPDATE adlots_users SET `tnkpoint`=`tnkpoint`+'$tnkpoint' , `userpoint`=`userpoint`+'$tnkpoint' WHERE `id`='$id' ");
		}
	}
	
	$tnkAd = new TnkAd();
	
	try {
			$id = $_POST['md_user_nm'];
			$tnkpoint = $_POST['pay_pnt'];
			$tnkAd-> tnkad($id, $tnkpoint);

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>