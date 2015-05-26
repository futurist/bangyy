<?php
require_once('manager/config.php');
$ID=$GET["id"];
if(isset($require_id)){
  $ID = $require_id;
}
if(!$ID) header("Location: index.php");

$r = q1("select * from single where id=$ID order by dtime desc ");

if($require_id){
  echo $r['content'];
  return;
}

$menu = q("select * from menu ");
$menuGroup = $r['menu'];

?>
<?php
	require_once("header.php");
?>
<style type="text/css">
  .wrap{
    padding:0;
    margin:0;
  }
</style>
<?php echo $r["content"];?>


<?php
	require_once("footer.php");
?>
