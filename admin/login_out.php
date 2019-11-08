<?php
session_start();
$_SESSION['manage']['name'] = '';
$_SESSION['manage']['pw'] = '';
$_SESSION['manage']['level']='';
$_SESSION['manage']['create_time']='';
//数据库是int，但在这里使用‘’，完全不会影响
header("Location: login.php");
?>