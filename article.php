<?php
require_once('manager/config.php');
$ID=$GET["id"];
if(!$ID) header("Location: index.php");

$r = q1("select *, channel.name as cname , channel.id as cid from news left join channel on channelID=channel.id where news.id=$ID order by dtime desc ");

$menu = q("select * from menu ");
$menuGroup = $r['menu'];


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
  font:32px/1.1 Arial;
}
.meta{
  color:#888;
  padding: 20px 0;
  padding-bottom: 30px;
}

</style>

<div class="move">
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
</div>



<?php
	require_once("footer.php");
?>
