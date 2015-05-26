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

$name = $POST["name"];
$phone = $POST["phone"];
$type = $POST["type"];
$weixin = $POST["weixin"];
$pass = $POST["pass"];
$referer = $POST["referer"];

$date = date("Y-m-d H:i:s");
$time = time();

if(!$phone) exit();

$regCount = q1("select count(*) from members where phone='$phone' ");
//$regCount = reset($regCount);

if($regCount==0){
	q("insert into logs (action,phone, referer, ip, dtime) values( 'reg', '$phone', '$referer', '$ip', '$date') ");
	q("insert into members (name,phone, weixin, pass, referer, type, content, ip, dtime) values( '$name', '$phone', '$weixin', '$pass','$referer', '$type','登录后编辑详情', '$ip', '$date') ");
	//$lastID = q1("select id from members order by id desc limit 1 ");
	$_SESSION["phone"] = $phone;
	$_SESSION["type"] = $type;
	$_SESSION["name"] = $name;
	$_SESSION["weixin"] = $weixin;
}

echo $regCount;

exit();


?>
