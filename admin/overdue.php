<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$link = connect();
$query = "select * from sfk_member where id={$_GET['id']}";
$result = execute($link,$query);
$data = mysqli_fetch_assoc($result);
setcookie('sfk[name]',$data['name'],time()+1000,"/");
setcookie('sfk[pw]',$data['pw'],time()+1000,"/");
//在PHO手册中知道，后面还可以跟两个值，一个是路径，一个是域，而路径"/"也是相当于域，于是我修改为以上使用！！！
header("Location: ../member.php?id={$data['id']}");
?>