<?php
require_once('config.php');
if($_SESSION["admin"]!=1)exit();

$oldpass = $POST["oldpass"];
$newpass = $POST["newpass"];
$action = $POST["action"];
$msg = "";

if($action=="changepass"){

	$r = q1("select * from members where role='manager' limit 1 ");
	if($r['pass'] == $oldpass){
		$id = $r["id"];
		q("update members set pass='$newpass' where id=$id ");
		$msg = "密码已更新";
	}else{
		$msg = "原密码错误";
	}

}
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
			<?php echo $msg; ?>
		</div>

        <div class="result-wrap">
            <div class="result-content">
                <form action="password.php" method="post" id="myform" name="myform" enctype="multipart/form-data">
                    <table class="insert-tab" width="100%">
                        <tbody>
                            <tr>
                                <th><i class="require-red">*</i>原密码：</th>
                                <td>
                                    <input class="common-text required" id="oldpass" name="oldpass" size="50" value="" type="text">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="require-red">*</i>新密码：</th>
                                <td>
                                    <input class="common-text required" id="newpass" name="newpass" size="50" value="" type="text">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="require-red">*</i>确认新密码：</th>
                                <td>
                                    <input class="common-text required" id="newpass2" name="newpass2" size="50" value="" type="text">
                                </td>
                            </tr>

                            <tr>
                                <th></th>
                                <td>
                                	<input type="hidden" name="action" value="changepass">
                                    <input id="submit1" class="btn btn-primary btn6 mr10" value="提交" onclick="return check();" type="submit">
                                </td>
                            </tr>
                        </tbody></table>
                </form>
            </div>
        </div>

    </div>
    <!--/main-->

    <script type="text/javascript">
		var submiting = false;
    	function getEl(id){
	    	return document.getElementById(id);
    	}
	    function check(){
	    	if(submiting)return;
		    if( getEl("oldpass").value=="" || getEl("newpass").value=="" || getEl("newpass2").value=="" ){
			    alert("请填写完整");
			    return false;
		    }else if( getEl("newpass").value != getEl("newpass2").value ) {
		    	alert("两次新密码不一致");
			    return false;
		    }else{
		    	submiting = true;
			    return true;
		    }
	    }
    </script>

</div>
</body>
</html>