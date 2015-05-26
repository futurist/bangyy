<?php
require_once('manager/config.php');
$menu = q( "select * from menu where gname='首页'" );
$menu = q( "select * from menu " );
$phoneSession = $_SESSION['phone'];
$menuGroup = '首页';

?>
<!DOCTYPE html>
<html lang="ch">
<head>
<title>板城工业游</title>
<meta charset="utf-8"/>
<meta name="keywords" content="板城烧锅"/>
<meta name="description" content="板城烧锅"/>
<meta name="apple-touch-fullscreen" content="yes"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="width=640,user-scalable=no,target-densitydpi=device-dpi">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>

<style type="text/css">
*{
	margin:0; padding:0;
}
html,body{
	width: 100%;
	height:100%
}

*:not(input):not(textarea) {
	-webkit-touch-callout: none !important;
	-webkit-user-select: none !important;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
}
@-webkit-keyframes shineBlue {
from {
-webkit-box-shadow:0 0 20px rgba(0,0,153,0)
}
50% {
-webkit-box-shadow:0 0 40px rgba(83,83,223,100)
}
to {
	-webkit-box-shadow: 0 0 20px rgba(0,0,153,0)
}
}
.shine_blue {
	-moz-border-radius: 50px;
	-webkit-border-radius: 50px;
	border-radius: 50px;
	-webkit-animation-name: shineBlue;
	-webkit-animation-duration: 2s;
	-webkit-animation-iteration-count: infinite
}
@-webkit-keyframes blurAnim {
from {
	-webkit-filter:brightness(0);
}
to {
	-webkit-filter:brightness(3.2);
}
}
.blur{
	-webkit-animation-name: blurAnim;
	-webkit-animation-duration: 2s;
	-webkit-animation-fill-mode: both;
}

@-webkit-keyframes shineAnim {
from {
	opacity:0;
}
50% {
	opacity:1;
}
to {
	opacity:0;
}
}
.shine{
	-webkit-animation-name: shineAnim;
	-webkit-animation-duration: 2s;
	-webkit-animation-fill-mode: both;
	-webkit-animation-iteration-count: infinite
}


.page {
	background-size: 100% auto;
	background-position: center top;
	background-repeat: no-repeat;
}

.phbg {
	position: absolute;
	width: 100%;
	height: 100%;
	background-size: 100% auto;
	background-position: center top;
	background-repeat: no-repeat;
}
#loading {
	background-color: #000;
	position: absolute;
	width: 100%;
	height: 100%;
	z-index: 2;
	text-align: center
}

#loading>p>img{
	position: absolute;
	left:0; top:0;
}
.wineLeft{

	background-position: left top;
	left: 0!important;
}
.wineRight{

	background-position: right top;
	right: 0!important;
}

.nowload{
	position: absolute;
	bottom: 26%;
	color: white;
	text-align: center;
	width: 100%;
	font-size: 16px;
}

.abs{
	position: absolute;
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


.wrap{
	position: relative;
	width: 100%;
	height: 100%;
}

body{
	background-color:black;background-image:url(images/bg.jpg); background-repeat: repeat-y; overflow-x:hidden;
	background-size: cover;
}

.bot{
	position: fixed;
	bottom: 0;
	width: 100%;
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

<script type="text/javascript" src="js/zepto.min.js" ></script>
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

</head>
<body class="app">

<div class="bg"></div>
<div class="txtpyq"><img src="images/txt_pyq.png" alt=""></div>

<div class="wrap">

<?php
$require_id = 1;
require_once("single.php");
?>



<?php
	require_once("footer.php");
?>
