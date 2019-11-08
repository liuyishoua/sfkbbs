<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	echo "id参数错误，删除失败";
}
$query="delete from sfk_manage where id={$_GET['id']}";
execute($link,$query);
if(mysqli_affected_rows($link)==1){
	echo "恭喜你删除成功";
}else{
	echo "删除失败";
}
?>