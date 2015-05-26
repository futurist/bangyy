<?php
require_once('manager/config.php');
$ID=$GET["id"];
if(!$ID) header("Location: index.php");
$cat = q1( "select * from channel where id=$ID" );

$menu = q("select * from menu ");
$menuGroup = $cat['menu'];

$orderby = ' order by dtime desc ';
$where = " channelID=$ID ";

$r = q("select *, news.id as nid from news left join channel on channelID=channel.id where $where  $orderby limit 4  ");

?>
<?php
	require_once("header.php");
?>



<div class="con list2">
<ul>
<?php foreach($r as $li){ $li=(object)$li; $li->title = strip_tags($li->title); ?>
<li class="clearfix">
	<p><a href="article.php?id=<?php echo $li->nid;?>"><?php echo $li->shortTitle!=""? $li->shortTitle : cut_str($li->title, 32);?></a>
	<?php if(!$li->event){ ?><br><?php echo date('Y.m.d', strtotime( $li->dtime ) );?><?php } ?></p>
	<img src="uploads/<?php echo $li->picture;?>" width="523">
	<?php if($li->event){ ?>
		<div class="clearfix"><span style="float:left;"><?php echo $li->event; ?></span><a class="regEvent" style="float:right;">点击报名</a></div>
	<?php } ?>
</li>
<?php } ?>

</ul>
</div>


<script type="text/javascript">


function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

(function($){

var loading=false;
function getItems(direction) {
    if (direction === 'down'){
    	if(loading)return;
    	$(".animation_image").show();loading=true;
        $.getJSON('data/get.php',{ start:$('.m-con-2>a').size(), table:'news', where:"<?php echo $where;?>",  orderby:"<?php echo $orderby;?>" }, function(data){
	        $.each(data, function(k,v){
				v.title = strip(v.title);
				v.des = strip(v.des);
		        $('.m-con-2>a').last().after(
		        '<a href="article.php?id='+ v.id +'&phone='+ phone +'"  class="s-babg"> <dl class="prolistbgfff"> <dt> <img src="uploads/'+ v.picture +'" border="0" width="120" height="120" alt="'+ v.title +'"> </dt> <h3 class="s-wc">'+ v.title.substring(0,9) +'</h3> '+ v.des.substring(0,48) +' <span></span> <div class="clear"></div> </dl> </a>'
		          );
	        });
	        Waypoint.destroyAll();
	        if(data.length) $('.m-con-2').waypoint(getItems, opt);
	        $(".animation_image").hide();loading=false;
        });
    }
}


var opt = { offset: 'bottom-in-view' };

$('.m-con-2').waypoint(getItems, opt);


})(Zepto);


	</script>




<?php
	require_once("footer.php");
?>
