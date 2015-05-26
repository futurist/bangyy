<?php
require_once('config.php');


$table = "orders";

$dateWhere =  $_SESSION['ajaxcrud_where_clause'][$table];
$tableRows = getColumnComment($table);

if(!$dateWhere) $dateWhere = " WHERE 1 ";

foreach( $tableRows as $key=>$val ){
	$q = $_REQUEST[$key];
	if($q){
		$dateWhere .= " AND  $key LIKE '%{$q}%' ";
	}
}


$r1 = q1("select count(*) as c, sum(total) as t from orders $dateWhere");

echo json_encode($r1);


