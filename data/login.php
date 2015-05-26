<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With');
mb_http_input("utf-8");
mb_http_output("utf-8");
date_default_timezone_set("PRC");
require_once("../manager/config.php");
error_reporting(E_ERROR|E_WARNING);
session_start();


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$name = $POST["name"];
$phone = $POST["phone"];
$weixin = $POST["weixin"];
$pass = $POST["pass"];
$referer = $POST["referer"];

$date = date("Y-m-d H:i:s");
$time = time();

if(!$phone) exit();

$regCount = q("select * from members where phone='$phone' and pass='$pass' ");
//$regCount = reset($regCount);

if(count($regCount)>0){
	q("insert into logs (action,phone, referer, ip, dtime) values( 'login', '$phone', '$referer', '$ip', '$date') ");
	$_SESSION["phone"] = $regCount[0]["phone"];
	$_SESSION["type"] = $regCount[0]["type"];
	$_SESSION["name"] = $regCount[0]["name"];
	$_SESSION["weixin"] = $regCount[0]["weixin"];
}

echo count($regCount);

exit();


?>
