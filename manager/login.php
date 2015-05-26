<?php
require_once('config.php');

$phone = $POST["phone"];
$pass = $POST["pass"];
$action = $POST["action"];
$msg = "";
$try = $_SESSION["trytime"];
if(!$try)$try = 0;
$_SESSION["trytime"] = $try+1;

if($try<5 && $action=="login"){

	$r = q1("select * from members where role='manager' limit 1 ");
	if($r['pass'] == $pass && $r['phone'] == $phone){
		$_SESSION["trytime"] = null;
		$_SESSION["admin"] = 1;
		header("Location: index.php");
		exit();
	}else{
		$msg = "手机或密码错误，还可尝试". (5-$try)."次";
	}

}

if($try>=5){
	$msg = "已超过重试次数，今日无法登录";
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>板城工业游-后台管理</title>
    <link href="css/admin_login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="admin_login_wrap">
    <h1>后台管理</h1>
    <div align="center">
	    <?php echo $msg; ?>
    </div>
    <div class="adming_login_border">
        <div class="admin_input">
            <form action="login.php" method="post">
                <ul class="admin_items">
                    <li>
                        <label for="user">手机：</label>
                        <input type="text" name="phone" value="admin" id="phone" size="40" class="admin_input_style" />
                    </li>
                    <li>
                        <label for="pwd">密码：</label>
                        <input type="password" name="pass" value="admin" id="pass" size="40" class="admin_input_style" />
                    </li>
                    <li>
                        <input type="hidden" name="action" value="login">
                        <input type="submit" tabindex="3" value="提交" onclick="return check()" class="btn btn-primary" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <p class="admin_copyright"><a tabindex="5" href="../" target="_blank">返回首页</a> &copy; 2015 </p>
</div>
    <script type="text/javascript">
		var submiting = false;
    	function getEl(id){
	    	return document.getElementById(id);
    	}
	    function check(){
	    	if(submiting)return;
		    if( getEl("phone").value=="" || getEl("pass").value=="" ){
			    alert("请填写完整");
			    return false;
		    }else{
		    	submiting = true;
			    return true;
		    }
	    }
    </script>

</body>
</html>