<?php
require_once('manager/config.php');
?>
<?php
$phoneSession = $_SESSION["phone"];
$phoneGet = $GET["phone"];
$preview = $GET["preview"];
$ajaxAction = $_REQUEST["ajaxAction"];
$action = $_REQUEST["action"];


if($phoneSession && !$preview && ($ajaxAction || $action ||  $phoneGet==$phoneSession) ){

	include_once ('manager/ajaxCRUD.class.php');

	$table = $GET["show"];
	if(!$table) $table = "members";


	$tblDraw = new ajaxCRUD($table, $table, "id", "manager/");

?>
<!DOCTYPE HTML>
<html>
	<head>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes;" name="viewport" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=yes">

		<?php $tblDraw->insertHeader(); ?>

		<!-- these js/css includes are ONLY to make the calendar widget work (fldDateMet);
			 these includes are not necessary for the class to work!! -->
		<link rel="stylesheet" href="manager/includes/jquery.ui.all.css">
		<script src="manager/includes/jquery.ui.core.js"></script>
		<!--script src="manager/includes/jquery.ui.widget.js"></script-->
		<script src="manager/includes/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
</script>
<style>
#floatReq{ position:absolute; }
#selReq{ font-size:12px; }
.ajaxCRUD td a {border:none}
input.delete{ border:none;background:none;cursor:pointer; }
.btn{background: #eee;}

.vertical td.content>div{ border: none; background:none; }

#table_members>thead{ display: none; }

.mobile .vertical.ajaxCRUD .sep td{ border: none; }
.mobile .vertical td.content{ width: auto;border: none; }


</style>
</head>
<body>
<h2 align=center>会员后台</h2>
<div id="floatReq" style="display:none"><select id="selReq"></select></div>
<p align='center'>
	<a href="index.php?phone=<?php echo $phoneSession;?>">首页</a>
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="member.php?preview=1&phone=<?php echo $phoneSession;?>">预览</a>
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="data/exit.php?redirect=..%2Findex.php%3Fphone%3D<?php echo $phoneSession;?>">退出</a></p>
<?php
	//$tblFriend->modifyFieldWithClass("fldDateMet", "datepicker");
	//$tblDemo->defineCheckbox('fldField2','on','off');
	//$tblDemo->setFileUpload("fldFilename", "uploads/");
	//$tblDemo->formatFieldWithFunction('logo', 'makeImg');
	//$tblDraw->setOrientation("vertical");
	//$tblFriend->modifyFieldWithClass("fldDateMet", "datepicker");

function makeImg($val){
	return  $val ?  "<img src=\"uploads/$val\" />" : "";
}
function editPass($val){
	return  $val ?  "***********" : "";
}

$tblDraw->addWhereClause("WHERE phone = '$phoneSession' ");

$tblDraw->setTextareaHeight('des', 80);
$tblDraw->setTextareaHeight('shopName', 80);
$tblDraw->setTextareaHeight('content', 80);

$tblDraw->setFileUpload("picture", "uploads/", "uploads/");
$tblDraw->setFileUpload("photo", "uploads/", "uploads/");



$tblDraw->formatFieldWithFunction('picture', 'makeImg');
$tblDraw->formatFieldWithFunction('photo', 'makeImg');
$tblDraw->formatFieldWithFunction('pass', 'editPass');

$tblDraw->setOrientation("vertical");

$tblDraw->setInitialAddFieldValue ("dtime",  $date );
$tblDraw->omitFieldCompletely("id");
$tblDraw->omitFieldCompletely("role");
$tblDraw->omitFieldCompletely("referer");
$tblDraw->omitFieldCompletely("dtime");
$tblDraw->omitFieldCompletely("ip");

$tblDraw->disallowEdit("phone");
$tblDraw->disallowEdit("weixin");

$tblDraw->disallowAdd();
$tblDraw->disallowDelete();

$tblDraw->displayAs("name", "我的姓名");
$tblDraw->displayAs("pass", "我的密码");
$tblDraw->displayAs("phone", "我的电话");
$tblDraw->displayAs("weixin", "我的微信");
$tblDraw->displayAs("shopName", "我的微店");
$tblDraw->displayAs("geyan", "我的格言");
$tblDraw->displayAs("photo", "照片");

$tblDraw->displayAs("content", "个人<br>空间");

$tblDraw->showTable();


$Orders = q1("SELECT count(*) FROM `orders` WHERE 1 and referer='$phoneSession' and status='交易完成' ");
$Sells = q1("SELECT sum(num) FROM `orders` WHERE 1 and referer='$phoneSession' and status='交易完成' ");
$Amout = q1("SELECT sum(total) FROM `orders` WHERE 1 and referer='$phoneSession' and status='交易完成' ");

$regStat = q1("SELECT count(*) FROM `logs` WHERE action='reg' and referer='$phoneSession' ");
$shareFriendStat = q1("SELECT count(*) FROM `logs` WHERE action='shareApp' and referer='$phoneSession' ");
$shareTimelineStat = q1("SELECT count(*) FROM `logs` WHERE action='shareTimeline' and referer='$phoneSession' ");
$shareWeiboStat = q1("SELECT count(*) FROM `logs` WHERE action='shareWeibo' and referer='$phoneSession' ");

?>

<div align="center">


	<p><span>累计完成销售笔数：</span><?php echo $Orders;?> 笔</p>
	<p><span>累计完成销售金额：</span><?php echo $Amout;?> 元</p>



	<p><span>注册人数：</span><?php echo $regStat;?> 次</p>
	<p><span>发送给朋友：</span><?php echo $shareFriendStat;?> 次</p>
	<p><span>分享到朋友圈：</span><?php echo $shareTimelineStat;?> 次</p>
</div>


<script type="text/javascript" src="manager/js/editor/ueditor.config.js"></script>
<script type="text/javascript" src="manager/js/editor/ueditor.all.min.js"></script>

<script type="text/javascript">

	var isWeixin = /MicroMessenger|mobile/i.test(navigator.userAgent);
	window.setImgDlgPos=function(){
		setTimeout(function(){
			$("#edui_fixedlayer").css({ position:"absolute", top: $(window).scrollTop() });
		$("#filePickerReady, #dndArea").css({ width:"320px" });
		$("#filePickerReady>div").css({ left:"0px" });
		},500)

	}

	setInterval(function(){
		$('.savingAjaxWithBackground').html('已更新');
	}, 1000)

	$('td form *:last').before('<br>').val('取消');
	$('td form').append('<input type="submit" class="editingSize" value="确定">');
	$('[value="Upload"]').next().hide().after('<input type="button"  class="editingSize" value="取消" onclick="$(this).parent().parent().hide();$(this).parent().parent().prev().show();">');
	$('input[value^="Save "]').val('保存');
	$('input[value="Upload"]').val('上传');
	$('input[value="Cancel"]').val('取消');
	$('input[value="Delete"]').val('删除');
	$('input[value="Ok"]').hide();
	$('input[name=submit]').addClass('editingSize');
	$('th:contains("Action")').html('操作');
	var toolbars =  [
                        ["bold", "italic", "underline", "forecolor", "backcolor", "justifyleft", "justifycenter", "justifyright", "insertunorderedlist", "insertorderedlist"],
                        ["emotion", "simpleupload", "insertimage", "insertvideo", "link", "removeformat", "|", "rowspacingtop", "rowspacingbottom", "lineheight"],
						[ "paragraph", "fontsize" ],
                        ["inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "splittocells", "splittorows", "splittocols"]
                    ];

       //    isWeixin=true;
	if(isWeixin){
		toolbars =  [
                        ["|", "bold","|", "italic","|", "forecolor","|",  "link", "|", "insertimage", "|" ]
                    ];
       $('body').addClass("mobile");
	   $("#members_row_1 th").first().wrapInner('<div style="width:30px;"></div>');
	} else {
		$('#table_members>thead').show();
	}

	toolbars2 =  [
                        ["|", "bold","|", "italic","|", "forecolor","|",  "link", "|"]
                    ];

	//window.onerror = function(e){alert(e);}

		function onEdit(){

			//alert( this.getContent() );
		  var con = this.container;
			var $form = $(con).prev();
			var max = this.getOpt("maximumWords");
		  $form.find('textarea').val( this.getContent() );
		}

		var opt = { toolbars:toolbars,
		 initialFrameWidth: 300,
		 minFrameHeight: isWeixin?30:500,
		 autoHeightEnabled : isWeixin?false:true,
		 enableAutoSave: false,
		 saveInterval: 999999,
		 autoSyncData:false,
			imagePopup: true,
		 autoClearinitialContent: false,
            autoFloatEnabled: isWeixin?false:true,
            wordCount: false,
            elementPathEnabled: false,
            maximumWords: 300,
            focus: true,
		 onready:function(){
					var con = this.container;
					var $form = $(con).prev();
					$form.hide();
					if(isWeixin) {
						var a = $(con).find('.edui-editor-iframeholder');
						a.after(a.prev());
						$(con).find('.edui-editor-bottomContainer').hide();
						$(con).find('.edui-toolbar').append('<input type="button" class="editingSize" value="取消" style="margin-left:5px" onclick="$(this).parents(\'span\').hide().prev().show()" />');
						$(con).find('.edui-toolbar').prepend('<input type="submit" class="editingSize" value="确定" style="margin-left:5px" onclick="$(this).parents(\'.edui-editor\').prev().submit()" />');
					} else {
						$(con).find('.edui-editor-toolbarboxinner').append('<input type="button" class="editingSize" value="取消" style="margin-left:5px" onclick="$(this).parents(\'span\').hide().prev().show()" />');
						$(con).find('.edui-editor-toolbarboxinner').append('<input type="submit" class="editingSize" value="确定" style="margin-left:5px" onclick="$(this).parents(\'.edui-editor\').prev().submit()" />');
					}


					this.setContent( $form.find('textarea').val()||"" );
					this.addListener('blur',onEdit)
					this.addListener('contentChange',onEdit)
				}
		}

		 setTimeout( function(){

			$('.ajaxCRUD input[value=des] ').each(function(i,e){

				setEditor( $(this).parents('span').get(0), 300);

			});

			$('.ajaxCRUD input[value=shopName] ').each(function(i,e){

				setEditor( $(this).parents('span').get(0), 300, toolbars2);

			});

			$('.ajaxCRUD input[value=content] ').each(function(i,e){

				setEditor( $(this).parents('span').get(0), 1000 );

			});



			function setEditor(el, max, bar){
				var id = el.id;
				opt.initialFrameHeight = isWeixin? $(window).height()-320 : $(el).parent().height()+50;
				opt.maximumWords = max;
				if(bar) opt.toolbars = bar;
				var ue = UE.getEditor(id, opt );
			}

		 }, 30);
	$('.add_new_btn').click(function(){
	});

	$('span.editable a').live("click",function(e){
		e.preventDefault();
	});

	//$("#membersshopName5_show").parents('tr').after('<tr><th></th><td style="padding: 5px 0 30px 0;"><span><i>(填写微店名称,请勿超过10字)</i></span></td></tr>');

</script>

	</body>
</html>



<?php

}else{

$mem = q1("select * from members where phone = '$phoneGet' ");

?>
<?php
	require_once("header.php");
?>
	<!--返回-->
	<div class="g-back"> <a href="javascript:history.go(-1)" class="u-bp f-fl">返回</a> <a href="index.php?phone=<?php echo $phoneGet;?>"  class="u-bh f-fr">首页</a> </div>
  <div class="search">
    <form action="search.php" method="get">
      <input type="text" name="key">
      <input type="hidden" name="phone" value="<?php echo $phoneGet;?>">
      <input type="submit" value="">
    </form>
  </div>
	<!--搜索-->
	<!--详细页-->
	<?php
	if( $phoneSession && $preview){?>
		<!--div align="center" style="padding:10px 0;"><a href="member.php?phone=<?php echo $phoneGet;?>"><input type="button" value="进入编辑" class=red_btn /></a></div-->
	<?php }
	?>
	<div class="g-bd">
		<!--导航-->
		<div class="m-con2">
			<!--标题-->
			<div class="u-mtit">
				<h1 class="s-tit">茶顾问</h1>
				<div id="content" class="contactus"><img src="uploads/<?php echo $photo;?>" style="margin-right: 10px; float: left;" width="100" />
				<div>我的名字：<?php echo $name;?></div>
				<div>我的电话：<?php echo $mem["phone"];?></div>
				<div>我的微信：<?php echo $mem["weixin"];?></div>
				<div>加入时间：<?php echo $dtime;?></div><br />
				<div>我的格言：<?php echo $geyan;?></div>

				<div class="clear"></div>
				</div>



				<div style="padding-top: 15px;">
				<h3><b>我的微店:</b></h3>
				<hr style="1px solid #ccc; margin:10px 0; width:80%;" />
				<?php echo $mem["shopName"];?>
				</div>

				<div style="padding-top: 30px;">
				<h3><b>个人空间:</b></h3>
				<hr style="1px solid #ccc; margin:10px 0; width:80%;" />
				<?php echo $mem["content"];?>
				</div>
				<!--分页-->
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<!--配套产品-->
		<?php	require_once("rmsp.php");?>
	<!--底部开始-->

<?php
	require_once("footer.php");
?>

<?php
	}
?>
