<?php
require_once('config.php');

include_once ('ajaxCRUD.class.php');
//if($_SESSION["admin"]!=1)exit();

$date = date("Y-m-d H:i:s");

$table = $_GET["show"];
$rowid = $_GET["id"];
$channelID = $_GET['channelID'];


if(!$table) $table = $_SESSION["table"];
else	$_SESSION["table"] = $table;

if(!$table) $table = "members";


$tableName = getTableComment($table);
$tableRows = getColumnComment($table);

$tblDraw = new ajaxCRUD($tableName, $table, "id", "./");


$phoneManager = q1("select phone from members where role='manager' order by id limit 1 ");

function getLayout($section, $name){
	$layoutRM = q1("select * from layouts where name='$section' ");

	$layoutRM = json_decode($layoutRM["layout"]);

	return json_encode( $layoutRM->{$name} );
}

$rmspID = getLayout("热门商品","rmsp");
//   $sql = "SELECT * FROM log WHERE call_date >= DATE_FORMAT('" . $from . "', '%Y/%m/%d') AND call_date <=  DATE_FORMAT('" . $to . "', '%Y/%m/%d')";
//$sql = "SELECT * FROM log WHERE call_date >= DATE_FORMAT('{$from}', '%Y-%m-%d') AND call_date <=  DATE_FORMAT('{$to} 23:59:59', '%Y-%m-%d %H:%i:%s')";


$_SESSION['ajaxcrud_where_clause'][$table] = "";


?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>板城工业游-后台管理</title>
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <script type="text/javascript" src="js/libs/modernizr.min.js"></script>
	<style type="text/css">
		th a{ color:#333; }
		.add_form{ margin:0px 0 30px 0; }
		.add_form th{ padding-right:10px; text-align: left; }
		div.edui-editor-toolbarbox{ line-height:1; }
		th,td {
			white-space: nowrap;
		}
		td.content{
			max-width: 700px;
			overflow: hidden;
			text-overflow:ellips;
		}
		.result-tab th{
			background: none;
		}
		.edui-default .edui-editor-bottomContainer{
			position: absolute;
			top: 0;
			right: 50px;
		}
	</style>
</head>
<body>
<div class="topbar-wrap white">
	<?php require_once('top.php'); ?>
</div>
<div class="container clearfix">
    <div class="sidebar-wrap">
		<?php require_once('left.php'); ?>
    </div>
    <!--/sidebar-->
    <div class="main-wrap">

        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="index.php">首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><?php echo $tableName;?>管理</span></div>
        </div>
        <div class="search-wrap" style="display:none">
            <div class="search-content">
				<div class="dateRange" style="display:none;">
					<a href="list.php?show=orders&startDate=<?php echo date('Y-m-01', time()); ?>">当月订单</a>  <a href="list.php?show=orders&startDate=<?php echo date('Y-m-d', strtotime('-1 month')); ?>">近30天订单</a>  <a href="list.php?show=orders&startDate=<?php echo date('Y-m-d', strtotime('-3 month')); ?>">近三个月订单</a> <a href="list.php?show=orders">全部订单</a>  时间选择： <input type="text" name="startDate" id="startDate" value=""><input type="text" name="endDate" id="endDate" value="<?php echo $endDate ?>"><input type="button" id="confirmDate" value="确定"><input type="button" id="clearDate" value="清除">
					<div class="datePos" style="position:relative;"></div>
				</div>
            </div>
			<div class="orderStat" style="display:none;">
				当前条件订单统计：<span class="allCount"></span>笔，<span class="allAmount"></span>元
			</div>
        </div>
        <div class="result-wrap">
                <div class="result-title">
                    <div class="result-list" style="display:none;">
                        <a class="topAddNew" href="#"><i class="icon-font"></i>新增</a>
                        <span><b>提示：点击表格内容即可编辑。</b></span>
                    </div>
                </div>


                <div class="result-content">


<?php
	//$tblFriend->modifyFieldWithClass("fldDateMet", "datepicker");
	//$tblDemo->defineCheckbox('fldField2','on','off');
	//$tblDemo->setFileUpload("fldFilename", "uploads/");
	//$tblDemo->formatFieldWithFunction('logo', 'makeImg');
	//$tblDraw->setOrientation("vertical");
	//$tblFriend->modifyFieldWithClass("fldDateMet", "datepicker");

function makeImg($val){
	return  $val ?  "<img style=\"max-width: 640px; max-height: 300px;\" src=\"../uploads/$val\" />" : "";
}

function makeProductID($val){
	global $phoneManager;
	return  $val ?  "<a href=\"../product.php?id=$val&phone=$phoneManager\" target=_blank>$val</a>" : "";
}

function editPass($val){
	return  $val ?  "***********" : "";
}


if($table == "single"){
	if(!$rowid) $tblDraw->addButtonToRow("编辑", "", "id", "openSinglePreview");
	$tblDraw->addButtonToRow("预览", "../single.php", "id", "", "new");

}
if($table == "channel"){
	if(!$rowid) $tblDraw->addButtonToRow("编辑", "", "id", "openChannelPreview");
	$tblDraw->addButtonToRow("预览1", "../nlist1.php", "id", "", "new");
	$tblDraw->addButtonToRow("预览2", "../nlist2.php", "id", "", "new");
	$tblDraw->addButtonToRow("文章列表", "list.php?show=news", "channelID", "", "new");
	$tblDraw->addButtonToRow("添加文章", "list.php?show=news&addNews=1", "channelID", "");

	$tblDraw->defineAllowableValues("how",  array("文字模式","小图列表","大图模式")  );

	$tblDraw->addOrderBy("ORDER BY menu desc");

}

$tblDraw->defineAllowableValues("payment",  array("未选择", "货到付款","微信支付" )  );
//$tblDraw->defineAllowableValues("status",  array("等待用户支付",  "已付款", "等待卖家确认", "卖家已确认","已发货","等待买家确认","交易完成", "交易关闭")  );
$tblDraw->defineAllowableValues("status",  array("未激活",  "已激活", "已失效")  );


if($table == "news") {


	if(!$channelID){
		$tblDraw->addWhereClause( " WHERE   channelID!=2 " );
	}
	if(!$rowid){
		$tblDraw->addButtonToRow("编辑", "", "id", "openNewsPreview");
		$tblDraw->omitField("content");
	} 
	$tblDraw->addButtonToRow("预览", "../article.php", "id", "", "new");

	$tblDraw->defineCheckbox("publish");
	

	$tblDraw->defineRelationship("channelID", "channel", "id","name");
	if($channelID){
		$tblDraw->addWhereClause( " WHERE   channelID=$channelID " );
	}

}

if($table == "members"){
	//$tblDraw->addButtonToRow("统计", "stat.php", "phone", "", "new");
	$tblDraw->omitFieldCompletely("url");
	$tblDraw->omitFieldCompletely("weixin");
	$tblDraw->omitFieldCompletely("photo");
	$tblDraw->omitFieldCompletely("shopName");
	$tblDraw->omitFieldCompletely("geyan");
	$tblDraw->omitFieldCompletely("content");
	$tblDraw->omitFieldCompletely("referer");

}

$tblDraw->defineRelationship("catID", "shop_cat", "id","name");




$tblDraw->setTextareaHeight('des', 80);
$tblDraw->setTextareaHeight('content', 80);

$tblDraw->setFileUpload("picture", "../uploads/", "../uploads/");
$tblDraw->setFileUpload("photo", "../uploads/", "../uploads/");

$tblDraw->formatFieldWithFunction('picture', 'makeImg');
$tblDraw->formatFieldWithFunction('photo', 'makeImg');

$tblDraw->setOrientation("horizontal");

$tblDraw->setInitialAddFieldValue ("dtime",  $date );

$tblDraw->omitFieldCompletely("how");
$tblDraw->omitFieldCompletely("role");
$tblDraw->displayAddFormTop();


$action = $_GET["action"];
$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];
if($startDate){
	$_SESSION["startDate"] = $startDate;
	$tblDraw->addWhereClause( " WHERE   dtime >= DATE_FORMAT('$startDate', '%Y/%m/%d')  " );
}else{
	$_SESSION["startDate"] = null;
}

if($endDate){
	$_SESSION["endDate"] = $endDate;
	$tblDraw->addWhereClause( " WHERE   dtime <=  DATE_FORMAT('$endDate', '%Y/%m/%d')  " );
}else{
	$_SESSION["endDate"] = null;
}


if($table == "logs"){
	$tblDraw->omitFieldCompletely("url");
	$tblDraw->addWhereClause( " WHERE  action='login' " );
	$tblDraw->addAjaxFilterBoxAllFields();
}


if($table == "orders"){
	$tblDraw->addAjaxFilterBox("id");
	$tblDraw->addAjaxFilterBox("name");
	$tblDraw->addAjaxFilterBox("phone");
	$tblDraw->addAjaxFilterBox("payment");
	$tblDraw->addAjaxFilterBox("status");
	$tblDraw->addAjaxFilterBox("referer");
	$tblDraw->addAjaxFilterBox("province");
	$tblDraw->addAjaxFilterBox("city");
	$tblDraw->addAjaxFilterBox("county");
	$tblDraw->addAjaxFilterBox("address");
	$tblDraw->addAjaxFilterBox("pname");
	$tblDraw->addAjaxFilterBox("price");

	$tblDraw->omitFieldCompletely("ip");
	$tblDraw->omitFieldCompletely("wuliu");
	$tblDraw->omitFieldCompletely("comment");

	$tblDraw->formatFieldWithFunction('productID', 'makeProductID');

	$tblDraw->setOrientation("horizontal");

	$tblDraw->disallowAdd();

}

if($table == "menu"){

	$tblDraw->addButtonToRow("预览", "", "id", "openMenuPreview");
	$tblDraw->addButtonToRow("编辑", "", "id", "openMenuContent");

	$tblDraw->addOrderBy("ORDER BY gname,id asc");
	$tblDraw->omitField("id");
	$tblDraw->addOrderBy("ORDER BY gname desc");

}

if(isset( $tableRows['dtime'] ) )
	$tblDraw->addOrderBy("ORDER BY dtime desc");
else
	$tblDraw->addOrderBy("ORDER BY id desc");



if($channelID != "2"){
	$tblDraw->omitField("memberNum");
	$tblDraw->omitField("event");
}


if($table == "redeem"){
	$tblDraw->omitField("id");
	$tblDraw->addOrderBy("ORDER BY award,pass asc");
	$tblDraw->addAjaxFilterBoxAllFields();
}
if($table == "members"){
	$tblDraw->defineAllowableValues("type",  array("普通",  "VIP")  );
	$tblDraw->addAjaxFilterBoxAllFields();
}


if($table == "logs" ){
	$tblDraw->setOrientation("horizontal");

	$tblDraw->turnOffAjaxEditing();

	$tblDraw->disallowAdd();
	$tblDraw->disallowDelete();
}

//$tblDraw->turnOffAjaxADD();


if($rowid){
	$tblDraw->setTextareaHeight('content', 650);
	$tblDraw->setOrientation("vertical");
	$tblDraw->addWhereClause( " WHERE  id=$rowid " );
}


foreach($tableRows as $key=>$val){
	$tblDraw->displayAs($key, strtoupper($val));
}


$tblDraw->setLimit(20);
$tblDraw->showTable();



?>
                </div>


        </div>
    </div>
    <!--/main-->
</div>
<script type="text/javascript">

var table="<?php echo $table; ?>";
var tableName="<?php echo $tableName; ?>";

var addNews = "<?php echo $_GET['addNews']; ?>";
var channelID = "<?php echo $channelID; ?>";
var rowid = "<?php echo $rowid; ?>";

	$('td form *:last').before('<br>').val('取消');
	$('td form').append('<input type="submit" class="editingSize" value="确定">');
	$('[value="Upload"]').next().hide().after('<input type="button"  class="editingSize" value="取消" onclick="$(this).parent().parent().hide();$(this).parent().parent().prev().show();">');
	$('input[value^="Save "]').val('保存');
	$('input[value="Upload"]').val('上传');
	$('input[value="Cancel"]').val('取消');
	$('input[value="Delete"]').val('删除');
	$('input[value="Ok"]').hide();
	$('th:contains("Action")').html('操作');

	$('input[type="checkbox"]').parent().find('[value="确定"]').hide();


if(rowid) $('.result-tab .tha_des,  .result-tab .tha_content').parent().attr( 'width', 640 );

if($('form.ajaxCRUD').size()>0){
	$('form.ajaxCRUD').appendTo('.search-content');
	$('form.ajaxCRUD').find('th').attr('valign', 'top');
	$('.search-wrap').show();
}else{
}


if(channelID && addNews){
	$('[name="channelID"]').val( channelID );
	$('.add_new_btn').click();
}


if($('.add_new_btn').size()>0){
	$('.add_new_btn').hide();
	$('.topAddNew').append(tableName).click(function(){
		$('[name="channelID"]').val( channelID );
		if( $('.add_form').is(':visible') )  $('.cancelAdd').click();
			else $('.add_new_btn').click();
	}	);
}else{
	$('.topAddNew').hide();
}

if(window.location.hash=='#add') {
	$('.add_new_btn').click();
}

if($('.editable').size()!=0 ||$('.add_new_btn').size()>0  ){
	$('.result-list').show();
}

$('[name="Uploader"] [value="取消"]').click(function(e){
	e.preventDefault();
	var el = $(this).closest('div');
	el.hide();
	el.prev().show();
});


</script>





<script type="text/javascript" src="js/editor/ueditor.config.js"></script>
<script type="text/javascript" src="js/editor/ueditor.all.min.js"></script>
<script type="text/javascript">
	var toolbars =  [
                        ["bold", "italic", "underline", "strikethrough", "forecolor", "backcolor", "justifyleft", "justifycenter", "justifyright", "insertunorderedlist", 'fullscreen'],
                        ["emotion", "simpleupload", "insertimage", "insertvideo", "link", "removeformat", "|", "rowspacingtop", "rowspacingbottom", "lineheight"],
						[ "paragraph", "fontsize", 'source', 'undo', 'redo' ],
                        ["inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "splittocells", "splittorows", "splittocols"]
                    ];

		 window.initEditor = function() {

			$('td.content').attr('valign','top');
			//$('td.content>div').css({ 'max-height':"500px", overflow:'hidden' });
			$('td.content .fileOpr').prev().click(function(e){
				e.preventDefault();
				$(this).next().find('.fileEditText').get(0).onclick();
			});
			var $tdc = $('.ajaxCRUD input[value=des], .ajaxCRUD input[value=content] ').parents('td.content');
			$tdc.attr('white-space', 'normal');
			if(rowid) $tdc.find('>div').css({ width:680 });
			$('.ajaxCRUD input[value=des], .ajaxCRUD input[value=content] ').each(function(i,e){
				$(this).prevAll('.editMode').css("visibility", "hidden");
				 $(this).parents('span').prev().click(function(){
					$(this).parents('td.content').find('>div').css({ overflow:'visible' });
					setEditor( $(this).next().get(0) );
				 });
			});

			setInterval(function(){
				var $tb = $('.edui-editor-toolbarbox');
				if($tb.size() && /fixed/i.test( $tb.css('position') )) $tb.css({ left: $tb.parent().offset().left-$(window).scrollLeft() });
			},100);

			function setEditor(el){
				var id = el.id;
				var ue = UE.getEditor(id, { toolbars:toolbars,
				 initialFrameWidth: 680,
				 minFrameHeight: 500,
				 initialFrameHeight: $(el).parent().height(),
				 autoHeightEnabled : false,
				 enableAutoSave: false,
				 saveInterval: 999999,
				 allHtmlEnabled: true,
				 autoSyncData:false,
				 autoClearinitialContent: false,
                    autoFloatEnabled: true,
                    wordCount: false,
                    elementPathEnabled: false,
                    maximumWords: 1e4,
                    focus: false,

				 onready:function(){
							var con = this.container;
							var $form = $(con).prev();
							$form.hide();
							this.setContent( $form.find('textarea').val()||"" );
							$(con).find('.edui-editor-toolbarboxouter').append('<input type="button" class="editingSize"  value="取消" onclick="$(this).parents(\'td.content\').find(\'>div\').css({ overflow:\'hidden\' });$(this).parents(\'span\').hide().prev().show()" />');
							$(con).find('.edui-editor-toolbarboxouter').append('<input type="submit" class="editingSize"  value="确定" onclick="$(this).parents(\'td.content\').find(\'>div\').css({ overflow:\'hidden\' });$(this).parents(\'.edui-editor\').prev().submit()" />');
							this.addListener('contentChange',function(){
								//console.log( this.getContent() );
								  var con = this.container;
									var $form = $(con).prev();
								  $form.find('textarea').val( this.getContent() );
							 })
							this.addListener('blur',function(){
								//console.log( this.getContent() );
								  var con = this.container;
									var $form = $(con).prev();
								  $form.find('textarea').val( this.getContent() );
							 })
							this.addListener('afterSelectionChange',function(){
								//console.log( this.getContent() );
								  var con = this.container;
									var $form = $(con).prev();
									var $toolbar = $(con).find('.edui-editor-toolbarboxouter');
									var offset= $('.edui-editor-imagescale').offset();
									$('.edui-anchor-topright').css({left: 0, top:0 });
									
							 })
								
						},

				 });
			}

		 };
		window.initEditor();

		var txtCon = $('.add_form td [name=content]');
		var el = txtCon.hide().parent();
		el.attr('id', 'addform_tdcontent');
		var ue = UE.getEditor('addform_tdcontent', { toolbars:toolbars,
				 initialFrameWidth: 680,
				 minFrameHeight: 500,
				 initialFrameHeight: $(el).height()+250,
				 autoHeightEnabled : false,
				 enableAutoSave: false,
				 saveInterval: 999999,
				 allHtmlEnabled: true,
				 autoSyncData:false,
				 autoClearinitialContent: false,
                    autoFloatEnabled: true,
                    wordCount: false,
                    elementPathEnabled: false,
                    maximumWords: 1e4,
                    focus: false,
				 onready:function(){
							add_from_editor = this;
							var con = this.container;
							this.addListener('contentChange',function(){
								//console.log( this.getContent() );
								  var con = this.container;
								$('.add_form td [name=content]').val( this.getContent().replace(/"/g,'\\"') );
							 })
							this.addListener('blur',function(){
								//console.log( this.getContent() );
								  var con = this.container;
								$('.add_form td [name=content]').val( this.getContent().replace(/"/g,'\\"') );
							 })
						}

				 });


	$('.add_new_btn').click(function(){
	});

var add_from_editor;

</script>

<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery-ui.css"/>

<style type="text/css">
.topdrag{
	display:none;
	position:fixed; top: 10px; left:500px;
}

.topdrag table{
	border-collapse: collapse;
	border-spacing: 0;
}
.topdrag table td{
	border:1px solid #ccc;
	padding:10px;
	position: relative;
	min-width: 80px;
	height: 40px;
}
.dropbox{
	background:red;
	font:18px/1 Arial;
	padding:10px;
	color:white;
}

body.dragging .topdrag td{
	background:#A33;
	text-align:center;
}

body.dragging .topdrag td.drophover{
	background:red;
}
body.dragging .topdrag td a{
	color:white;
}
.topdrag .red{ color:red; }
.topdrag .close{
	float: right;
	margin-left: 10px;
}
.topdrag .close1{
	float: left;
	margin-right: 10px;
	margin-left: 0px;
}

.topdrag .del {
	position: absolute;
	right: 0;
	top: 0;
	margin: 5px;
}

#orders_filter_form select{ height:25px; }

input[type=checkbox]{
	  width: 30px;
	  height: 30px;
}

</style>
<div class="topdrag">
	<div style="background:#ccc;padding: 5px 10px;"><a href="#" class="close1 close">关闭</a> 拖到格子中添加 <span class="red"></span></div>
	<table cellpadding="2" style="background: #FFF;"><tbody><tr><td class="c1"></td><td class="c2"></td></tr><tr><td class="c3"></td><td class="c4"></td></tr></tbody></table>
</div>
<script type="text/javascript">
var phone = "<?php echo $phoneManager; ?>";

$('.ajaxCRUD tr>td.content:first-child ').draggable({
      cursor: "move",
      cursorAt: { top: 10, left: 10  },
      helper: function( event ) {
      	var el=$(event.target).parent().find('td').first();
        return $( '<div class="dropbox"><span>'+ el.text() +'</span></div>' ).css({opacity:0.8});
      },
	  start: function(event, ui){
		$('body').addClass('dragging');
		if(!$('.topdrag table').is(':visible') ) $('.topdrag .close').click();
	  },
	  stop: function(event, ui){
		$('body').removeClass('dragging');
	  }
});

$('.topdrag').draggable();
$(".topdrag td").droppable({
	tolerance: "pointer",
	hoverClass: "drophover",
	out: function( event, ui ) {
		ui.draggable.data("outed", true);
	},
	drop: function( event, ui ) {
		//console.log( ui.draggable );
		var id = $(ui.draggable).parent().find('td').first().text();
		var dataid= $(".topdrag td").find(".dataid").map(function(){ return $(this).text(); }).toArray();
		if(dataid.indexOf(id)>-1 )return;
		var slot = $(".topdrag td").index(this);
		$.getJSON('data.php', {table: "shop_product", id:id, action:"add", slot:slot }, function(data){
			fillItem(data.slot,data);
		});
	}
});

function fillItem(i, data){
	var str="<div class=dataid>"+ data.id +"</div>";
	if(data.picture) str += '<img src="../uploads/'+ data.picture +'" width=50 height=50>';
	$(".topdrag td").eq(i).html( str + '<div><a href="../product.php?id='+data.id+'&phone='+ phone +'" target=_blank>' + data.name + '</a></div>' );
	$(".topdrag td").eq(i).append('<div class="del">X</div>');
	$(".topdrag td").eq(i).find(".del").click(function(){
		var slot = $(".topdrag td").index( $(this).parent() );
		var id =  $(this).parent().find('.dataid').html() ;
		$.getJSON('data.php', {table: "shop_product", id:id, action:"del", slot:slot }, function(data){
			$(".topdrag td").html('');
			$.each(data,function(i,v){
				fillItem(i, v);
			});
		});
	});
}

$('.topdrag .close').click(function(e){
	e.preventDefault();
	var $el = $('.topdrag table');
	if($el.is(':visible')){
		$el.hide();
		$('.close1,.close').html("展开");
	}else{
		$el.show();
		$('.close1,.close').html("关闭");
	}
});
setTimeout(function(){ $('.topdrag .close').click(); }, 500);

if(table=="shop_product"){
	$('.topdrag .red').html("推荐商品");
	$(".topdrag").show();
	var rmsp = eval('<?php echo $rmspID;?>');
	if(rmsp) {
		$.each(rmsp, function(k,v){
			$.getJSON('data.php', {table: "shop_product", id:v, action:"get" }, function(data){
				fillItem(k,data);
			});
		});
	}
}


</script>


<script>

if(table=='orders'){
	$('.dateRange, .orderStat').show();
}


if(table=='menu'){
	
	function openMenuPreview (id) {
		var $link = $('#menulink'+id+'_show');
		var $input = $link.closest('td').next().find('input').eq(1);
		var t = $link.text();
		window.open( t.match(/^http:\/\/|^\//)? t : '../'+t );
	}
	function openNewsPreview (id) {
		window.location = 'list.php?show=news&id='+id+'&channelID='+channelID;
	}
	function openSinglePreview (id) {
		window.location = 'list.php?show=single&id='+id;
	}
	function openChannelPreview (id) {
		window.location = 'list.php?show=channel&id='+id;
	}

	function openMenuContent (id, isTest) {
		var $link = $('#menulink'+id+'_show');
		var $input = $link.closest('td').next().find('input').last();
		var t = $link.text();

		var m = t.match(/^(.*)\.php\?id=(.*)$/);
		if(m){
			switch(m[1]){
				case "single":
					window.open( "list.php?show=single&id=" + m[2] );
					break;
				case "article":
					window.open( "list.php?show=news&id=" + m[2] );
					break;
				case "nlist1":
				case "nlist2":
				case "nlist3":
					window.open( "list.php?show=news&channelID=" + m[2] );
					break;
			}
		} else {
			$input.hide();
		}
	
	}

	$('#table_menu td:nth-child(3)').each(function  (i,e) {
		var $link = $(this).find('span').first();
		var $input = $link.closest('td').next().find('input').last();
		var t = $link.text();
		console.log(t);

		var m = t.match(/^(.*)\.php\?id=(.*)$/);
		if(m){

		} else {
			$input.hide();
		}
	})
}

var startDate = "<?php echo $startDate ?>";
var endDate = "<?php echo $endDate ?>";

$(function() {
$( "#startDate" ).datepicker({
  defaultDate: startDate? new Date(startDate) : "-1w",
  dateFormat: "yy-mm-dd",
  changeMonth: true,
  numberOfMonths: 2,
  onClose: function( selectedDate ) {
	$( "#endDate" ).datepicker( "option", "minDate", selectedDate );
  }
});
$( "#endDate" ).datepicker({
  defaultDate: endDate? new Date(endDate) : "+1w",
  dateFormat: "yy-mm-dd",
  changeMonth: true,
  numberOfMonths: 2,
  onClose: function( selectedDate ) {
	$( "#startDate" ).datepicker( "option", "maxDate", selectedDate );
  }
});
});
if(startDate) $('#startDate').val(startDate);
if(endDate) $('#endDate').val(endDate);

$('#confirmDate').click(function(){

		window.location = window.location.pathname+"?show="+table+"&startDate="+encodeURIComponent($( "#startDate" ).val())+"&endDate="+encodeURIComponent($( "#endDate" ).val()) ;

});
$('#clearDate').click(function(){

		window.location = window.location.pathname+"?show="+table ;

});


$('#orders_filter_form input').live("change", function(){
	//setTimeout(function(){ updateFilter(); }, 3000);
});
$('#orders_filter_form input, #orders_filter_form select').wrap("<div></div>");

window.updateFilter = function (){
	$.getJSON('orderStat.php?' + $('#orders_filter_form').serialize() , {}, function(data){
		$('.allCount').html(data.c);
		$('.allAmount').html(data.t);
	});
}
updateFilter();



</script>

</body>
</html>