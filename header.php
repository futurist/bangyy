<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx48c6d809e977e8fb", "96d566201fb67ce017fc01c5f5d5e72f");
$signPackage = $jssdk->GetSignPackage();
?>
<?php
require_once('manager/config.php');

$phoneGet = $GET["phone"];
$phoneSession = $_SESSION["phone"];

$prevUrl = $_SERVER['HTTP_REFERER'];

function GetCurUrl(){
    if(!empty($_SERVER["REQUEST_URI"])){
        $scriptName = $_SERVER["REQUEST_URI"];
        $nowurl = $scriptName;
    }else{
        $scriptName = $_SERVER["PHP_SELF"];
        if(empty($_SERVER["QUERY_STRING"])) $nowurl = $scriptName;
        else $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
    }
    return $nowurl;
}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=640,user-scalable=no,target-densitydpi=device-dpi">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo isset($title)? $title : "板城酒业官微" ?></title>

<script type="text/javascript" src="js/zepto.min.js" ></script>
<script type="text/javascript" src="js/zepto.waypoints.min.js" ></script>
<script type="text/javascript">
$(function  () {
	$('#b3').click(function(e){
		e.preventDefault();
		$(".bg,.txtpyq").show();
	});
	$(".bg,.txtpyq").click(function  () {
		$(".bg,.txtpyq").hide();
	});
});
</script>

<!--script type="text/javascript" src="js/WeixinApi.js" ></script-->
<style type="text/css">
*{
	margin:0; padding:0;
}
html,body{
	width: 100%;
	height:100%
}
p{
	-webkit-margin-before: 0em;
	-webkit-margin-after: 0em;
	-webkit-margin-start: 0px;
	-webkit-margin-end: 0px;
}
*:not(input):not(textarea) {
	-webkit-touch-callout: none !important;
	-webkit-user-select: none !important;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
}

body{
	background-color:black;background-image:url(images/bg.jpg); background-repeat: repeat-y; overflow-x:hidden;
	background-size: cover;
}

.abs{
	position: absolute;
}
.abs2{
	float:left;
}


#b1{
	left: 0px;
	top: 0px;
	width: 100px;
	height: 77px;
}

#b2{
	left: 160px;
	top: 0px;
	width: 100px;
	height: 77px;
}
#b3{
	left: 335px;
	top: 0px;
	width: 100px;
	height: 77px;
}
#b4{
	left: 521px;
	top: 0px;
	width: 100px;
	height: 77px;
}

.bot{
	position: fixed;
	bottom: 0;
	width: 100%;
}
.wrap{
	position: relative;
	height: 100%;
	padding:0px 38px;
}

.clearfix:before, .clearfix:after {
    content: "\0020";
    display: block;
    height: 0;
    overflow: hidden;
}
.clearfix:after { clear: both; }
.clearfix { zoom: 1; }

.con{
	background: rgba(255,255,255,1);
	border:1px solid #a5a5a5;
	border-top:2px solid #eff1f3;
	border-radius: 0 0 10px 10px;
	font:24px/1 Arial;
	margin-bottom: 100px;

}
ul,li{list-style: none;}
.con a{
	font:24px/1 Arial;
	text-decoration: none;
	color: black;
}
.con li{
	padding: 10px 20px 10px 14px;
	border-bottom: 2px solid #eff1f3;
}
.con li:last-child{
	border:none;
}
.con li img{
	float: right;
	border:1px solid #eff1f3;
	margin-left: 18px;
}
.con li p{
	padding: 14px 0 12px 0;
}

.con.list2 li{
	padding-left:18px;
	padding-right:14px;
}
.con.list2 li img{
	float: none;
	border:1px solid #666;
	border:none;
	margin: 0px;

	-webkit-box-shadow: 3px 3px 3px #333;

}



.popmenu{
	position: fixed;
	  z-index: 9999;
	  bottom: 84px;
	  right: 0;
	  visibility: visible;
	  display: none;
}
.popmenu li{
	display: block;
	position: relative;
	overflow: visible;
}
.popmenu ul{
	border-radius: 0.6em;
	box-shadow: 0 1px 3px rgba(0,0,0,.2);
	background-clip: padding-box;
}

.popmenu li{
		border-width: 1px 0 0 0;
	  border-right-width: 1px;
	  border-left-width: 1px;
	  border-bottom-width: 1px;
	  border-style: solid;
	  border-color: #6b6b6b ;
	  padding: .5em 1.143em;
	  font-size: 14px;
	  cursor: default;
	  outline: 0;
	  background-color: #4d4d4d;

}

.popmenu li:first-child {

  -webkit-border-top-left-radius: inherit;
  border-top-left-radius: inherit;
}


.popmenu li a{

	margin: 0;
	display: block;
	position: relative;
	text-align: left;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;

	padding: .7em 0.5em;
	padding-right:2.5em;
	text-decoration: none;

	font:32px/1.3 Arial;

	cursor: pointer;
	-webkit-user-select: none;

	  color: #ffffff ;
	  text-shadow: 0  2px  0  #444444
}

.popmenu li a:after {

	background-color: rgba(0,0,0,.4) ;
	background-position: center center;
	background-repeat: no-repeat;
	border-radius: 1em;

	content: "";
	position: absolute;
	display: block;
	width: 44px;
	height: 44px;

	top: 50%;
	margin-top: -22px;
	right: .5625em;

  background-image: url('data:image/svg+xml;charset=US-ASCII,<%3Fxml%20version%3D"1.0"%20encoding%3D"iso-8859-1"%3F><!DOCTYPE%20svg%20PUBLIC%20"-%2F%2FW3C%2F%2FDTD%20SVG%201.1%2F%2FEN"%20"http%3A%2F%2Fwww.w3.org%2FGraphics%2FSVG%2F1.1%2FDTD%2Fsvg11.dtd"><svg%20version%3D"1.1"%20id%3D"Layer_1"%20xmlns%3D"http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg"%20xmlns%3Axlink%3D"http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink"%20x%3D"0px"%20y%3D"0px"%20%20width%3D"28px"%20height%3D"28px"%20viewBox%3D"0%200%2014%2014"%20style%3D"enable-background%3Anew%200%200%2014%2014%3B"%20xml%3Aspace%3D"preserve"><polygon%20style%3D"fill%3A%23FFFFFF%3B"%20points%3D"3.404%2C2.051%208.354%2C7%203.404%2C11.95%205.525%2C14.07%2012.596%2C7%205.525%2C-0.071%20"%2F><%2Fsvg>');
}

.txtpyq {
        display: none;
        text-align: right;
        position: absolute;
        width: 100%;
        right: 0;
        top: 0;
        z-index: 300000000000;
}
.bg {
        display: none;
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 20000000;
}



</style>
</head>
<body class="app">

<div class="wrap">

<div class="bg"></div>
<div class="txtpyq"><img src="images/txt_pyq.png" alt=""></div>



