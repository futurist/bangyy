<?php
require_once('config.php');

$table = $GET["table"];
$id = (int)$GET["id"];
$slot = (int)$GET["slot"];
$action = $GET["action"];


$section = "热门商品";
$name = "rmsp";

$layoutRM = q1("select * from layouts where name='$section' ");
$layoutRM = json_decode($layoutRM["layout"]);
$posID = $layoutRM->{$name};

$r = q1("select * from $table where id=$id ");


switch($action){
	case "get":
		echo json_encode($r);
		break;
	case "del":
		if($posID && count($posID) >0 && in_array($id, $posID) ) {
			//unset( $posID[$slot] );
			array_splice($posID, $slot,1);
			$layoutRM->{$name} = $posID;
			$posStr  = json_encode($layoutRM);
			q("update layouts set layout='$posStr' where  name='$section' ");

			$rr = array();
			foreach($posID as $val){
				$rr[] = q1("select * from shop_product where id = {$val} ");
			}
			echo json_encode($rr);
			exit();
		}
		break;
	case "add":

		if($posID && count($posID) >0 ) {
			if(in_array($id, $posID) ) {
				echo '{"error":1, "msg":"exists", "slot": '+ $slot +', "id": '+ $id +'}';
				exit();
			}
		}
		
		if( ! $posID ){
			$posID=array();
			array_push($posID, $id);
			$slot = count($posID)-1;
		} else {
			if( $posID[$slot] ){
				$posID[$slot] = $id;
			}else{
				array_push($posID, $id);
				$slot = count($posID)-1;
			}
		}
		$layoutRM->{$name} = $posID;

		$posStr  = json_encode($layoutRM);
		q("update layouts set layout='$posStr' where  name='$section' ");
		
		$r['slot'] = $slot;
		echo json_encode($r);
		break;
	
}
