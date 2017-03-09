<?php
header("Content-Type: text/html; charset=UTF-8");
include_once 'connection.php';
$body = json_decode(file_get_contents('php://input'));

	class ItemhowtoBuy {

		private $db;
		private $connection;
		
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
		}

		public function lots($nickname, $phone, $howtobuy, $itemid, $type, $category, $brand, $itemname, $imagelink, $referlink, $endpoint, $endtime, $userlotspoint, $when, $winorlose) {
			$query_check = mysqli_query($this-> connection, "SELECT * FROM adlots_getitem WHERE `id`='$itemid' ");
			$row = $query_check-> fetch_array(MYSQLI_ASSOC);
			$lotspeople = $row["lotspeople"]; // 안드로이드에서 lotspeople 데이터를 안 가져와서 getitem의 lotspeople을 가져와서 useritem의 lotspeople 정보에 1을 증가시켜서 담는다
			$nowpoint = $row["nowpoint"]; // 안드로이드에서 nowpoint 데이터를 안 가져와서 getitem의 nowpoint를 가져와서 useritem의 nowpoint 정보에 업데이트시킨다
			if($userlotspoint > ($row["endpoint"]-$row["nowpoint"])){
				$json['response'] = 'overpoint'; // 만약 보유 포인트보다 더 많은 포인트를 응모했을 때
				echo json_encode($json, JSON_UNESCAPED_UNICODE);
			} else {
				$query_userrepeat = mysqli_query($this-> connection, "SELECT * FROM adlots_useritem WHERE `itemid`='$itemid' AND `nickname`='$nickname' AND `howtobuy`='lots' ");
				$row_userrepeat = $query_userrepeat-> fetch_array(MYSQLI_ASSOC);
				if(mysqli_num_rows($query_userrepeat) > 0){ // 추가 응모를 신청했을 때
					$lotsquota2 = $row_userrepeat["lotsquota2"];
					$query_getitem = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `nowpoint`=`nowpoint`+'$userlotspoint' WHERE `id`='$itemid' ");
					$query_useritem = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `userlotspoint`=`userlotspoint`+'$userlotspoint' , `lotsquota2`=`lotsquota2`+'$userlotspoint' WHERE `itemid`='$itemid' AND `howtobuy`='lots' AND `nickname`='$nickname' ");
					$query_otheruseritem = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `lotsquota1`=`lotsquota1`+'$userlotspoint' , `lotsquota2`=`lotsquota2`+'$userlotspoint' WHERE `itemid`='$itemid' AND `howtobuy`='lots' AND `nickname`!='$nickname' AND `lotsquota1`>'$lotsquota2' ");
					$query_updateuseritem = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `nowpoint`=`nowpoint`+'$userlotspoint' WHERE `itemid`='$itemid' AND `howtobuy`='lots' ");
					
				} else {
					$lotsquota1 = $row["nowpoint"] + 1; // 처음 응모를 신청했을 때
					$lotsquota2 = $row["nowpoint"] + $userlotspoint;
					$query_getitem = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `nowpoint`=`nowpoint`+'$userlotspoint' , `lotspeople`=`lotspeople`+1 WHERE `id`='$itemid' ");
					$query_useritem = mysqli_query($this-> connection, "INSERT INTO adlots_useritem (`nickname`,`phone`,`howtobuy`,`itemid`,`type`,`category`,`brand`,`itemname`,`imagelink`,`referlink`,`endpoint`,`endtime`,`userlotspoint`,`lotsquota1`,`lotsquota2`,`when`) VALUES ('$nickname','$phone','$howtobuy','$itemid','$type','$category','$brand','$itemname','$imagelink','$referlink','$endpoint','$endtime','$userlotspoint','$lotsquota1','$lotsquota2','$when') ");
					$query_updateuseritem = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `nowpoint`='$nowpoint'+'$userlotspoint' , `lotspeople`='$lotspeople'+1 WHERE `itemid`='$itemid' AND `howtobuy`='lots' ");
				}

				// 유저 랏츠 포인트 감소
				$query_userpoint = mysqli_query($this-> connection, "UPDATE adlots_users SET `userpoint`=`userpoint`-'$userlotspoint' WHERE `nickname`='$nickname' ");
				
				// 당첨자 추첨 과정
				if(($row["nowpoint"]+$userlotspoint) == $row["endpoint"]) {
					$query_getitem_done = mysqli_query($this-> connection, "UPDATE adlots_getitem SET `pointdone`='yes' , `whendone`='$when' , `winorlose`='$winorlose' WHERE `id`='$itemid' ");
					$query_useritem_done = mysqli_query($this-> connection, "UPDATE adlots_useritem SET `pointdone`='yes' , `whendone`='$when' WHERE `itemid`='$itemid' AND `howtobuy`='lots' ");
					
					$query_useritem_win = mysqli_query($this-> connection,"UPDATE adlots_useritem SET `winorlose`='win' WHERE `lotsquota1`<='$winorlose' AND `lotsquota2`>='$winorlose' AND `itemid`='$itemid' ");
					$query_useritem_lose = mysqli_query($this-> connection,"UPDATE adlots_useritem  SET `winorlose`='lose' WHERE `winorlose`!='win' AND `itemid`='$itemid' ");
					$json['response'] = 'pointdone';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);
					mysqli_close($this-> connection);
				} else { // 최종적인 성공 쿼리를 보낸다
					$json['response'] = 'success';
					echo json_encode($json, JSON_UNESCAPED_UNICODE);
					mysqli_close($this-> connection);
				}
			}
		}

		public function purchase($nickname, $phone, $howtobuy, $itemid, $type, $category, $brand, $itemname, $imagelink, $referlink, $endpoint, $when) {
			$query_useritem = mysqli_query($this-> connection, "INSERT INTO adlots_useritem (`nickname`,`phone`,`howtobuy`,`itemid`,`type`,`category`,`brand`,`itemname`,`imagelink`,`referlink`,`endpoint`,`when`) VALUES ('$nickname','$phone','$howtobuy','$itemid','$type','$category','$brand','$itemname','$imagelink','$referlink','$endpoint','$when') ");
			$query_userpoint = mysqli_query($this-> connection, "UPDATE adlots_users SET `userpoint`=`userpoint`-'$endpoint' WHERE `nickname`='$nickname' ");
		}

	}

	$itemhowtoBuy = new ItemhowtoBuy();

	try {
			$purpose = $_GET['purpose'];

			$nickname = $body->nickname;
			$phone = $body->phone;
			$howtobuy = $body->howtobuy;
			$itemid = $body->itemid;
			$type = $body->type;
			$category = $body->category;
			$brand = $body->brand;
			$itemname = $body->itemname;
			$imagelink = $body->imagelink;
			$referlink = $body->referlink;
			$endpoint = $body->endpoint;
			$endtime = $body->endtime;
			$userlotspoint = $body->userlotspoint;
			$when = $body->when;
			$winorlose = $body->winorlose;

			switch($purpose){
				case "lots":
					$itemhowtoBuy-> lots($nickname, $phone, $howtobuy, $itemid, $type, $category, $brand, $itemname, $imagelink, $referlink, $endpoint, $endtime, $userlotspoint, $when, $winorlose);
					break;
            	case "purchase":
					$itemhowtoBuy-> purchase($nickname, $phone, $howtobuy, $itemid, $type, $category, $brand, $itemname, $imagelink, $referlink, $endpoint, $when);
            		break;
            	default:
            		break;
            }

    } catch (Exception $e) {
    	http_response_code(400);
    }

?>