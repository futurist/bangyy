<?php
require_once('manager/config.php');
$ID=$GET["id"];
if(!$ID) header("Location: index.php");

$r = q1("select *, channel.name as cname , channel.id as cid from news left join channel on channelID=channel.id where news.id=$ID order by dtime desc ");

$menu = q("select * from menu ");
$menuGroup = $r['menu'];
$event = $r["event"];
$link = $r["link"];

$imgArr = array();
$isTuan = $r['cname']=='旅行团';
if($isTuan) {
	preg_match_all('/src="([^"]+)"/', $r['des'], $imgArr, PREG_PATTERN_ORDER);
} else {
	if($link && strpos( $_SERVER['HTTP_REFERER'], "1111hui.com" ) ){
		header("Location: $link");
		exit();
	}
}

if($event){
	$mem = q1("SELECT count(*)  FROM `members` WHERE event like '%,00369,%' ");
	if( $r['memberNum'] ){
		$mem =  $r['memberNum'];
	}
}


?>
<?php
	require_once("header.php");
?>
<script type="text/javascript">
var extLink = '<?php echo $link; ?>';
if(extLink){
	if( !document.referrer || document.referrer.match(/1111hui\.com/) ){
		//window.location = extLink;
	}

	setTimeout(function(){

	}, 30);
}
</script>
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
  font:32px/1.1 Arial;
}
.meta{
  color:#888;
  padding: 20px 0;
  padding-bottom: 30px;
}
#content img{
	width: 100%!important;
}



<?php if( $isTuan ){ ?>
.wrap{
	background: none;
	font:32px Arial;
	border-bottom:none;
	-webkit-height: calc(100% - 80px);
	height: calc(100% - 80px);
}
.tuan{
	background-image: url(images/tuanbg.gif);
	background-repeat: no-repeat;
	height: 880px;
}
.flag{
	color:white;
	position: absolute;
	top: 78px;
	left: 15px;
	width: 160px;
	height: 40px;
	font-weight: bold;
	text-align: center;
	-webkit-transform: rotate(-45deg);
	-webkit-transform-origin: 50% 50%;
	transform: rotate(-45deg);
	transform-origin: 50% 50%;
}
.Tcount{
	color: white;
	font: 44px Arial;
	height: 108px;
	padding-left: 242px;
	padding-top: 52px;
}
.Ttitle{
	font-size: 32px;
	padding: 0 30px;
	text-indent: 34px;
}
.time_td{
	display: inline-block;
	font-size: 44px;
}
.Ttime{
	padding: 40px 0 0 30px;
	font-size: 30px;
}
.tc{
	margin: 20px 0;
}
ul.showImg{
	position: absolute;
	left: 292px;
	top: 279px;
}
ul.showImg li{
	position: absolute;
	width: 310px;
	height: 230px;
	background-size: cover;
	background-repeat: no-repeat;
}

.swiper-container{
	position: absolute;
	left: 0px;
	top: 125px;
	width:461px;
	height: 127px;
}
ul.showImgThumb li{
	float: left;
	width:147px;
	height:127px;
	background-image: url(images/tuan-s.jpg);
	background-repeat: no-repeat;
	overflow: hidden;
}
ul.showImgThumb li div{
	width: 107px;
	height: 107px;
	margin:10px;
	background-size: cover;
	background-repeat: no-repeat;
}
.left{
	position: absolute;
	left: 0;
	top: 538px;
}
.right{
	position: absolute;
	right: 0;
	top: 538px;
}
.view{
	position: absolute;
	left: 0;
	top: 708px;
	width: 100%;
	height: 72px;
}
.can{
	position: absolute;
	left: 0;
	top: 798px;
	width: 100%;
	height: 72px;
}


<?php } ?>

</style>

<div class="move">

<?php if( !$isTuan ){ ?>

  <div class="article">
    <h2 class="title">
      <?php echo $r["title"];?>
    </h2>
    <?php if( $r["event"] ){ ?>
      <p class="meta regEvent clearfix">
        <i class="pl" style="float:left;"><?php echo "期数: " . $r['event'] . " (" . date('Y-m-d',strtotime($r["dtime"]) );?>)</i><br>
        <a class="" style="float:left;">点击报名</a>
      </p>
    <?php }else{ ?>
      <p class="meta"> <i class="pl">
        <?php echo date('Y-m-d',strtotime($r["dtime"]) );?>         </i></p>
    <?php } ?>
    <div id="content" class="contactus">
      <?php echo $r["content"];?>
      </div>
  </div>

<?php }else{ ?>
	<script type="text/javascript" src="js/timeCountDown.js"></script>

	<link rel="stylesheet" type="text/css" href="js/swiper.min.css">
	<script type="text/javascript" src="js/swiper.jquery.min.js"></script>
	<div class="tuan">
		<div class="flag">第<?php echo $r["event"];?>期</div>
		<div class="Tcount">已参团 <?php echo $mem;?>人</div>
		<div class="Ttitle"><?php echo $r["title"];?></div>

		<div class="Ttime">
			<p class="countTitle">剩余时间：</p>
			<div class="time_x tc lh24 b">
                <div id="hour" class="hour time_td">00</div>时<div id="mini" class="time_td mini ml5">00</div>分<div id="sec" class="sec time_td">00</div>秒
            </div>
			<p>已团<?php echo $mem;?>件</p>
		</div>
		<script type="text/javascript">

		var tuanLink = '<?php echo $r["link"]; ?>';

		var zxx = {
		    $: function(id){
		        return document.getElementById(id);
		    },
		    futureDate: new Date('<?php echo $r["dtime"]; ?>'),
		    obj: function(){
		        return {
		            sec: zxx.$("sec"),
		            mini: zxx.$("mini"),
		            hour: zxx.$("hour")
		        }
		    }
		};
		fnTimeCountDown(zxx.futureDate, zxx.obj());
		</script>

		<ul class="showImg">

		<li style="background-image:url(<?php echo $imgArr[1][0] ?>)"></li>

		</ul>

		<div class="swiper-container">
		<ul class="showImgThumb swiper-wrapper">
	<?php foreach($imgArr[1] as $val){ ?>

		<li class="swiper-slide"><div class="thumb" style="background-image:url(<?php echo $val ?>)"></div></li>

	<?php } ?>
		</ul>
		</div>
		<div class="left"><img src="images/tuan-left.png"></div>
		<div class="right"><img src="images/tuan-right.png"></div>
		<a class="view" href="<?php echo $link; ?>"></a>
		<a class="can regEvent" href="#"></a>
		<script type="text/javascript">
		var swiper = new Swiper('.swiper-container', {
		    nextButton: '.right',
		    prevButton: '.left',
		    slidesPerView: 3,
		    width:461,
		    setWrapperSize:true,
		    centeredSlides:false,
		    spaceBetween: 40,
		    onInit:function  (sw) {
	    		sw.slideTo(0);
		    	$('.left').hide();
		    	$('.right').hide();
		    	if(sw.slides.length>3){
		    		$('.right').show();
		    	}
		    },
		    onProgress:function  (sw,p) {
		    	if(sw.slides.length<=3) return;
		    	$('.left').show();
		    	$('.right').show();
		    	if(p==0) $('.left').hide();
		    	if(p==1) $('.right').hide();
		    },
		    onTap: function (sw, e) {
	    		var imgurl = Zepto(e.target).css('background-image');
	    		if(! Zepto(e.target).hasClass('thumb') )  return;
	    		Zepto('.showImg li').css('background-image', imgurl );
		    }
		});
		</script>


	</div>


<?php } ?>
</div>



<?php
	require_once("footer.php");
?>
