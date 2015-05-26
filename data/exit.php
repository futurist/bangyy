<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With');
mb_http_input("utf-8");
mb_http_output("utf-8");
date_default_timezone_set("PRC");

require_once("../manager/config.php");
error_reporting(E_ERROR|E_WARNING);

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


$redirect = $GET["redirect"];

$phone = $_SESSION["phone"];
$_SESSION["phone"]="";
$_SESSION["name"]="";
$_SESSION["weixin"]="";

$date = date("Y-m-d H:i:s");
$time = time();

if($phone) {
	q("insert into logs (action,phone, referer, ip, dtime) values( 'exit', '$phone', '', '$ip', '$date') ");
}

if($redirect)
	header("Location: $redirect");

echo "";



?>
