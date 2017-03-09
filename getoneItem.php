<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class GetoneItem {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function getoneitem($id) {
			$query = mysqli_query($this-> connection, "SELECT * FROM adlots_getitem WHERE `id`='$id' ");
			if(mysqli_num_rows($query)>0){
				$row = $query-> fetch_array(MYSQLI_ASSOC);
				$array = array(
        			"nowpoint" => $row["nowpoint"],
        			"endpoint" => $row["endpoint"],
        			"lotspeople" => $row["lotspeople"]
    			);
				echo json_encode($array, JSON_UNESCAPED_UNICODE);
            	mysqli_close($this -> connection);
			}
		}
	}
	
	$getoneItem = new GetoneItem();
	
	try {
            $id = $body->id;
            $getoneItem-> getoneitem($id);

    } catch (Exception $e) {
    	http_response_code(400);
    }
	
?>