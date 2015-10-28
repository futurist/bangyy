

</section>
<!-- end of wrap -->


<div class="bot">
	<div style="width:100%; height:91px; background-image:url(images/botmenu.png); background-size: cover;" class=""></div>
	<a class="abs" id="b1" href="javascript:history.go(-1)">&nbsp;</a>
	<a class="abs" id="b2" href="./"></a>
	<a class="abs" id="b3" href="#"></a>
	<a class="abs" id="b4" href="#"></a>
</div>

<div class="popmenu">
	<ul>
		<?php foreach($menu as $li){ $li=(object)$li; ?>
			<li>
				<a href="<?php echo $li->link; ?>"><?php echo $li->name; ?></a>
			</li>
		<?php } ?>
		<?php if(!$phoneSession){ ?>
			<li class="sysmenu"><a href="#" class="loginLink">会员登录</a></li>
			<li class="sysmenu"><a href="#" class="regLink">会员注册</a></li>
		<?php } else {?>
			<li class="sysmenu"><a href="#" class="exitLink">退出登录</a></li>
		<?php }?>
	</ul>
</div>
<script type="text/javascript">

var menuJSON = JSON.parse('<?php echo json_encode($menu); ?>');
var menuGroup = '<?php echo $menuGroup; ?>';

function setMenu (name) {
	if(menuJSON){
		var menus = menuJSON.filter(function(v){return v.gname==name});
		var str = menus.map(function (v) {
			return '<li><a href="'+ v.link +'">'+ v.name +'</a></li>';
		});
		$('.popmenu li').not('.sysmenu').remove();
		$('.popmenu ul').prepend( $(str.join('')) );
	}

	var w = $('.popmenu').width();
	//$('.popmenu').css({right: -w, visibility:'visible'}).animate({ right:0 }, 300);
	if(w) $('.popmenu').hide();
	else $('.popmenu').show();
}


$('#b4').on('click', function  (e) {
	e.preventDefault();
	setMenu(menuGroup);
	$('.bot>div').toggleClass('active');
});
</script>




<style type="text/css">
	.phbg{background-color:rgba(255,255,255,0.95);color: #666; font:32px Arial;  }
	.phbg input{ width:80%; border:#ccc solid 1px; height:22px; line-height:22px;}
	.phbg .regbtn{ height: 88px; margin-top:20px; }
	.phbg .loginbtn{ height: 88px; margin-top:20px; }
	.regClose{ color:#666; }
	.phbg input{
		height: 32px;
		padding: 10px 5px;
		font:32px Arial;
	}
</style>
<div class="phbg regCon" style="width:100%; height:100%; position:fixed; top:0; left:0; z-index: 10000000001; display:none; ">
			<div id="" class="reg" align="center" style="width: 310px; margin:auto; padding-top: 10px;">
				<div style="padding: 20px 0; font-size: 32px;font-weight: bold; color:#333;" class="regTitle">请填写您的个人信息</div>
				<div style="padding: 20px 0; font-size: 28px;font-weight: bold; color:#F33;" class="loginLink">已注册会员点此登录</div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">姓名</span><input type="text" name="name" style="width:200px;"></div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">电话</span><input type="text" name="phone" style="width:200px;"></div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">微信</span><input name="weixin" style="width:200px;"></div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">密码</span><input type="password" name="pass" style="width:200px;"></div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px; color:red;"></span></div>
				<div style="padding: 10px 0;">
				<input type="hidden" id="regtype" name="type" value="大众">
				<input class="regbtn" type="button" value="提交" style="font-size:32px; padding:5px;"></div>
				<div style="padding: 5px 0;"><a class="regClose" href="#" onclick="" style="font-size: 32px;font-weight: bold;">关闭</a></div>
			</div>
</div>

<div class="phbg loginCon" style=" width:100%; height:100%; position:fixed; top:0; left:0; z-index: 10000000001; display:none; ">
			<div id="" class="login" align="center" style="width: 310px; margin:auto; padding-top: 30px;">
				<div style="padding: 20px 0; font-size: 32px;font-weight: bold; color:#333;" class="regTitle">请登录</div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">电话</span><input type="text" name="phone" style="width:200px;"></div>
				<div style="padding: 5px 0;"><span style="padding: 0px 10px;">密码</span><input type="password" name="pass" style="width:200px;"></div>
				<div style="padding: 10px 0;"><input class="loginbtn" type="button" value="提交" style="font-size:32px; padding:5px;"></div>
				<div style="padding: 10px 0;"><a class="regClose" href="#" onclick="" style="font-size: 32px;font-weight: bold;">关闭</a></div>
			</div>
</div>



<script type="text/javascript">
var phone="<?php echo $phoneGet;?>";
var phoneSession="<?php echo $phoneSession;?>";
var submitting = false;

var theEvent = '<?php echo $event; ?>';

(function($){

	if(phoneSession){
		$(".regDiv").hide();
		$(".memberDiv").show();
	}else{
		$(".memberBtn").click(function(e){
			e.preventDefault();
			$(".loginCon").show();
		});
	}

	$('.regEvent').on("click",function(e){
		e.preventDefault();

		if(phoneSession && theEvent){
			$.get("data/regevent.php", { phone:phoneSession, event:theEvent }, function(data){
				alert("恭喜您已经成功报名本期活动。工作人员会稍后联系您。");

				if(typeof tuanLink!='undefined') {
					window.location=(tuanLink);
					return;
				}
			});
		}else{
			$('.regLink').click();
		}
	});

	$('.regLink').on("click",function(e){
		e.preventDefault();
		$(".regCon").show();
		$('.popmenu').hide();
	});
	$('.loginLink').on("click",function(e){
		e.preventDefault();
		$(".loginCon").show();
		$('.popmenu').hide();
	});
	$('.exitLink').on("click",function(e){
		e.preventDefault();
		$('.popmenu').hide();
		$.get("data/exit.php", { phone:phone }, function(data){
			//$(".regDiv").show();
			//$(".memberDiv").hide();
			window.location.reload();
		});

	});

  $('.regbtn').on("click", function(){
	  if(submitting) return;
	  var tel = $.trim( $('.regCon input[name=phone]').val() ); //获取手机号
	  var weixin = $.trim( $('.regCon [name=weixin]').val() ); //获取手机号
	  var pass = $.trim( $('.regCon [name=pass]').val() ); //获取手机号

	  if( $('.regCon input[name=name]').val()=="" || $('.regCon input[name=phone]').val()=="" || weixin=="" || pass=="" ){
		alert("请填写完整信息后提交");return;
	  }
		if( ! (/^(0|86|17951)?(1[3-9][0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/).test(tel) && ! (/^１[０１２３４５６７８９]{10}$/).test(tel) && ! (/^400\d{7}$/).test(tel)  ){
			alert("请填写正确的手机号");return;
		  }
		  submitting = true;
		  $('.regCon input').attr("disabled","disabled");

	$.post("data/reg.php", {name:$('.regCon input[name=name]').val(), phone:$('.regCon input[name=phone]').val(),  weixin: weixin,  pass: pass, referer:phone }, function(data){
		submitting=false;
		if(data>0){
			alert("您的电话已有记录，请登录。");
			$('.loginCon').show();
		}else{
			window.location.reload();
		}


		$('.regCon').hide();
			$('.regCon input').removeAttr("disabled");


	});
  });

  $('.loginbtn').on("click", function(){
	  if(submitting) return;
	  var tel = $.trim( $('.loginCon input[name=phone]').val() ); //获取手机号
	  var pass = $.trim( $('.loginCon [name=pass]').val() ); //获取手机号

	  if( $('.loginCon input[name=pass]').val()=="" || $('.loginCon input[name=phone]').val()=="" ){
		alert("请填写完整信息后提交");return;
	  }
		if( ! (/^(0|86|17951)?(1[3-9][0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/).test(tel) && ! (/^１[０１２３４５６７８９]{10}$/).test(tel) && ! (/^400\d{7}$/).test(tel)   ){
			alert("请填写正确的手机号");return;
		  }
		  submitting = true;
		  $('.loginCon input').attr("disabled","disabled");

	$.post("data/login.php", {pass:$('.loginCon input[name=pass]').val(), phone:$('.loginCon input[name=phone]').val(), referer:phone }, function(data){
		submitting=false;
		if(data==0){
			alert("此电话未注册，或密码错误。");
		}else{
			window.location.reload();
		}
		$('.loginCon').hide();
			$('.loginCon input').removeAttr("disabled");


	});
  });





$('.regClose').click(function(e){
	e.preventDefault();
	$(this).parents('.phbg').hide();
	$('.phbg .input').removeAttr("disabled");

});


if(window.location.hash=='#reg2'){
	$('#regtype').val("VIP");
	$('.reg .regTitle').html('填写以下信息注册VIP');
}

if(/#reg/i.test(window.location.hash) ){
	$(".regCon").show();
	$('.popmenu').hide();
}

})(Zepto);




</script>

</body>
</html>
