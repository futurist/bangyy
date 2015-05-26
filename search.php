<?php
require_once('manager/config.php');


$key = $GET["key"];
$phone = $GET["phone"];


$products = q("select *, shop_product.id as sid, shop_product.name as p_name, shop_cat.name as cat_name from shop_product left join shop_cat on catID=shop_cat.id where shop_product.name like '%$key%' order by dtime desc ");

$article = q("select *, channel.name as cname , channel.id as cid , news.id as nid  from news left join channel on channelID=channel.id where title like '%$key%' order by dtime desc ");

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
		<div class="tname"><?php echo count($products);?>个商品中包含<strong style="color:red"><?php echo $key;?></strong></div>
		<div class="m-con-2">

		<?php foreach($products as $li){ $li=(object)$li; ?>
						<a href="product.php?id=<?php echo $li->id;?>&phone=<?php echo $phoneGet;?>"  class="s-babg">
			<dl class='prolistbgfff'>
				<dt> <img src='uploads/<?php echo $li->picture;?>' border='0' width='120' alt='<?php echo $li->p_name;?>'> </dt>
				<h3 class="s-wc"><?php echo $li->p_name;?></h3>
				<?php echo $li->des;?>
				<span></span>
				<div class="clear"></div>
			</dl>
			</a><div class="clear"></div>
			<?php } ?>


		</div>

	</div>
	<!--底部开始-->

	<!--展示方式2：图片加标题加描述加简介 1行1列  开始-->
	<div class="g-bd">
		<div class="tname"><?php echo count($article);?>篇文章中包含<strong style="color:red"><?php echo $key;?></strong></div>
		<div class="m-con-2">
		<?php foreach($article as $li){ $li=(object)$li; ?>
						<a href="article.php?id=<?php echo $li->id;?>&phone=<?php echo $phoneGet;?>"  class="s-babg">
			<dl class='prolistbgfff'>
				<dt> <img src='uploads/<?php echo $li->picture;?>' border='0' width='120' height='120' alt='<?php echo $li->title;?>'> </dt>
				<h3 class="s-wc"><?php echo $li->title;?></h3>
				<?php echo $li->des;?>
				<span></span>
				<div class="clear"></div>
			</dl>
			</a>
			<?php } ?>


				<div class="animation_image" style="display:none" align="center">
				<img src="style/ajax_loader.gif">
				</div>

						<div class="clear"></div>
		</div>
	</div>
	<!--展示方式2：图片加标题加描述加简介 1行1列  结束-->
	<!--产品列表结束-->


  <!--配套产品-->
<?php	require_once("rmsp.php");?>

<?php
	require_once("footer.php");
?>