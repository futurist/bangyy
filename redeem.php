<?php
require_once('manager/config.php');

$phone = $POST["phone"];
$pass = $POST["pass"];
$shipMan = $POST["shipMan"];
$shipPhone = $POST["shipPhone"];
$address = $POST["address"];
$action = $POST["action"];
$msg = "";
$try = $_SESSION["trytime"];
if(!$try)$try = 0;

$msg = "填写以下信息以兑换礼品";

if($try<5 && $action=="redeem"){
	$_SESSION["trytime"] = $try+1;

	$r = q1("select * from redeem where pass='$pass' limit 1 ");
	if($r['status']=='未激活'){
		q("update redeem set phone='$phone', address='$address', shipPhone='$shipPhone', shipMan='$shipMan', status='已激活' where pass='$pass' " );
		$_SESSION["trytime"] = null;
		$msg = "恭喜您已兑换奖品：<font color=red>". $r['award'] ."</font>，<br>工作人员会尽快联系您。";
	}else{
		$msg = "卡号错误或已激活，还可尝试". (5-$try)."次";
	}

}

if($try>=5){
	$msg = "已超过重试次数，今日无法登录";
}


?>
<?php
	require_once("header.php");
?>

<style type="text/css">
.wrap{
  height: -webkit-calc(100% - 98px);
  height: calc(100% - 98px);
  overflow-x: hidden;
  padding:0px;
  margin:0px;
  background: white;
  border-bottom: 20px solid white;
}

.article{
  background: white;
  padding: 20px;
  font:28px/1 Arial;
}
.meta{
  color:#888;
  padding: 20px 0;
  padding-bottom: 30px;
}
.admin_items li{
	padding: 10px 0;
}
.admin_items input[type=text]{
	display: inline-block;
	line-height: 1.5;
	height: 38px;
	width: 380px;
	font:28px/38px Arial;
	border-radius: 8px;
	border:1px solid #888;
}
.admin_items input[type=submit]{
	display: inline-block;
	width: 160px;
	height: 60px;
	font:28px/44px Arial;
	margin-left:180px;
	border-radius: 8px;
	border:1px solid #888;
}
label { display: inline-block; height: 38px; line-height: 38px; width: 170px; text-align: right; }​


</style>

<div class="article">

<?php if($phoneSession) { ?>

    <div align="center" style="padding: 30px;font-weight: bold;">
	    <?php echo $msg; ?>
    </div>

	<form action="redeem.php" method="post">
        <ul class="admin_items">
            <li>
                <label for="shipMan">收货人：</label>
                <input type="text" name="shipMan" value="" id="shipMan" size="40" class="input_style" />
            </li>
            <li>
                <label for="shipPhone">收货电话：</label>
                <input type="text" name="shipPhone" value="" id="shipPhone" size="40" class="input_style" />
            </li>
            <li>
                <label for="address">收货地址：</label>
                <input type="text" name="address" value="" id="address" size="40" class="input_style" />
            </li>
            <li>
                <label for="pass">卡号：</label>
                <input type="text" name="pass" value="" id="pass" size="40" class="input_style" />
            </li>
            <li style="">
                <input type="hidden" name="phone" value="<?php echo $phoneSession; ?>" id="pass" size="40" />
                <input type="hidden" name="action" value="redeem">
                <input type="submit" tabindex="3" value="提交" onclick="return check()" class="btn btn-primary" />
            </li>
        </ul>
    </form>

    <script type="text/javascript">
		var submiting = false;
    	function getEl(id){
	    	return document.getElementById(id);
    	}
	    function check(){
	    	if(submiting)return false;
	    	var tel=getEl("shipPhone").value;
	    	var man=getEl("shipMan").value;
	    	var address=getEl("address").value;
		    if( getEl("phone").value=="" || getEl("pass").value=="" || getEl("address").value=="" || getEl("shipPhone").value==""  || getEl("shipMan").value=="" ){
			    alert("请填写完整");
			    return false;
		    }

		    if( ! (/^(0|86|17951)?(1[3-9][0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/).test(tel) && ! (/^１[０１２３４５６７８９]{10}$/).test(tel) && ! (/^400\d{7}$/).test(tel)  ){
				alert("请填写正确的手机号");
				return false;
			  }

		    if( man.length<2 ){
				alert("请填写正确的收货人");
				return false;
			  }

		    if( address.length<9 ){
				alert("请填写正确的收货地址");
				return false;
			  }

	    	submiting = true;
		    return true;
		    
	    }
    </script>

<?php }else{ ?>
	
	<div style="text-align:center; padding:50px 0;"><a class="loginLink" href="">请登录后兑换</a></div>

<?php } ?>
</div>



<?php
	require_once("footer.php");
?>
