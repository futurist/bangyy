<?php
require_once('manager/config.php');

$cat = q( "select * from shop_cat order by id" );

$rr=array();
foreach($cat as $c)
$rr[] = q("select *, shop_product.id as sid, shop_product.name as p_name, shop_product.picture as p_picture, shop_cat.name as cat_name from shop_product left join shop_cat on catID=shop_cat.id where catID=". $c["id"] ." order by dtime desc limit 2 ");

?>
<?php	
	require_once("header.php");
?>
	<!--头部结束--> 
	<!--返回上一步-->
	<div class="g-back"> <a href="javascript:history.go(-1)" class="u-bp f-fl">返回</a> <a href="index.php?phone=<?php echo $phoneGet;?>"  class="u-bh f-fr">首页</a> </div>
  <div class="search">
    <form action="search.php" method="get">
      <input type="text" name="key">
      <input type="hidden" name="phone" value="<?php echo $phoneGet;?>">
      <input type="submit" value="">
    </form>
  </div>
	<!--返回结束--> 
	<div class="g-se">
		<div class="clear"></div>
	</div>
	<!--搜索结束--> 
	<!--展示方式2：图片加标题加描述加简介 1行1列  开始-->
	<div class="g-bd">

	<?php foreach($rr as $r){ if(count($r)>0 ){ ?>
	
				<div class="tname"><a href="plist.php?id=<?php echo $r[0]["catID"];?>&phone=<?php echo $phoneGet;?>">更多&gt;&gt;</a><strong><?php echo $r[0]["cat_name"];?></strong></div>
		<div class="m-con-2">
		
		<?php foreach($r as $li){ $li=(object)$li; ?>
						<a href="product.php?id=<?php echo $li->sid;?>&phone=<?php echo $phoneGet;?>"  class="s-babg">
			<dl class='prolistbgfff'>
				<dt> <img src='uploads/<?php echo $li->p_picture;?>' border='0' width='120' alt='<?php echo $li->p_name;?>'> </dt>
				<h3 class="s-wc"><?php echo $li->p_name;?></h3>
				<?php echo $li->des;?>
				<span></span>
				<div class="clear"></div>
			</dl>
			</a>
			<?php } ?>
			
						<div class="clear"></div>
		</div>
	<?php } } ?>

			</div>
	<!--底部开始--> 
  <!--配套产品-->
<?php	require_once("rmsp.php");?>

<?php	
	require_once("footer.php");
?>
