<?php
require_once('config.php');

if($_SESSION["admin"]!=1){
	header("Location: login.php");
	exit();
}

$phone = $GET["phone"];
$phoneWhere = $phone? " and phone='$phone' " : "";
$phoneWhere2 = $phone? " and referer='$phone' " : "";

$regStat = q1("select count(*) from members where (role is null or role<>'manager') ");
$newsStat = q1("SELECT count(*) FROM `news` WHERE 1 ");
$singleStat = q1("SELECT count(*) FROM `single` WHERE 1 ");

$orderStat = q1("SELECT count(*) FROM `orders` WHERE 1 $phoneWhere2 ");
$orderStat2 = q1("SELECT count(*) FROM `orders` WHERE 1 and status='交易完成' $phoneWhere2 ");
$orderAmount2 = q1("SELECT sum(total) FROM `orders` WHERE 1 and status='交易完成' $phoneWhere2 ");
$shareFriendStat = q1("SELECT count(*) FROM `logs` WHERE action='shareApp' $phoneWhere2 ");
$shareTimelineStat = q1("SELECT count(*) FROM `logs` WHERE action='shareTimeline' $phoneWhere2 ");
$shareWeiboStat = q1("SELECT count(*) FROM `logs` WHERE action='shareWeibo' $phoneWhere2 ");

$msg=$phone? $phone."的信息统计" : "总的信息统计";

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>板城工业游-后台管理</title>
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <script type="text/javascript" src="js/libs/modernizr.min.js"></script>
</head>
<body><div class="topbar-wrap white">
	<?php require_once('top.php'); ?>
</div>
<div class="container clearfix">
    <div class="sidebar-wrap">
		<?php require_once('left.php'); ?>
    </div>
    <!--/sidebar-->
    <div class="main-wrap">

		<div class="crumb-wrap" align="center">
			<?php echo $msg;?>
		</div>

        <div class="result-wrap">
            <div class="result-content">
                <ul class="sys-info-list">
                    <li>
                        <label class="res-lab">会员数</label><span class="res-info"><?php echo $regStat;?></span>
                    </li>
                    <li>
                        <label class="res-lab">文章数</label><span class="res-info"><?php echo $newsStat;?></span>
                    </li>
                    <li>
                        <label class="res-lab">单页数</label><span class="res-info"><?php echo $singleStat;?></span>
                    </li>


                </ul>
            </div>
        </div>

    </div>
    <!--/main-->

    <script type="text/javascript">
    	function getEl(id){
	    	return document.getElementById(id);
    	}
    </script>

</div>
</body>
</html>