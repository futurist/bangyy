<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With');
mb_http_input("utf-8");
mb_http_output("utf-8");
date_default_timezone_set("PRC");
error_reporting(E_ERROR|E_WARNING);
session_start();

$type = $_GET["type"];
$val = $_GET["val"];
$msg = $_GET["msg"];


?>
<!DOCTYPE HTML>
<html>
<head id="Head1">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示</title>
</head>
<body>
	<div>
	<h1><?php
	switch ($type) { 
		case "payment": echo "订单消息"; break; 
	}
	?></h1>
	<div><p>
		<?php
		if($msg){
			echo $msg;
		}else{
			switch ($type.'-'.$val) { 
				case "payment-货到付款": echo "您已选择货到付款。请耐心等待，一般3-5个工作日即可送达。"; break; 
				case "payment-微信支付": echo "目前支付接口还未开通，请<a href=\"javascript:history.go(-1);\">返回</a>选择货到付款。"; break; 
			}
		}
	
	?>
	</p>
	</div>
	</div>
</body>