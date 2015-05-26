<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With');
mb_http_input("utf-8");
mb_http_output("utf-8");
date_default_timezone_set("PRC");
error_reporting(E_ERROR|E_WARNING);
session_start();

require_once("../manager/config.php");



if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$table = $GET["table"];
$start = $GET["start"];
$phone = $GET["phone"];
$where=$GET["where"];
$orderby=$GET["orderby"];

if(!$start) $start=0;

$date = date("Y-m-d H:i:s");
$time = time();

if(!$table) exit();

$items = q("select * from $table where $where $orderby limit $start,5 ");

echo json_encode($items);

exit();


?>
