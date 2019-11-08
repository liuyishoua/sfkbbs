<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
    <title><?php
        echo $template['title'];
//        有时候识别不了
        ?></title>
<meta name="keywords" content="后台界面" />
<meta name="description" content="后台界面" />
<link rel="stylesheet" type="text/css" href="style/public.css" />
    <script src="../../js/jquery-1.11.3.min.js"></script>

</head>
<body>
<?php
$link = connect();
$query = "select * from sfk_manage where name='{$_SESSION['manage']['name']}'";
$result = execute($link,$query);
if (mysqli_num_rows($result)!=1){
    header("Location: login.php");
}
?>
<div id="top">
	<div class="logo">
		管理中心
	</div>
	<ul class="nav">
		<li><a href="#" target="_blank">liuyishou</a></li>
	</ul>
	<div class="login_info">
		<a href="../../index.php" style="color:#fff;" target="_blank">网站首页</a>&nbsp;|&nbsp;
		管理员： <?php
        echo $_SESSION['manage']['name'];
        ?> <a href="login_out.php">[注销]</a>
	</div>
</div>
<div id="sidebar">
	<ul>
		<li>
			<div class="small_title">系统</div>
			<ul class="child">
				<li><a href="#">系统信息</a></li>
                <?php
                if ($_SESSION['manage']['level']==0){
//                    超级管理员才有以下操作权限
                ?>
				<li><a <?php
                    if(basename($_SERVER['SCRIPT_NAME'])=="manage.php") {
                        echo "class='current'";
                    }  ?> href="manage.php">管理员</a></li>
				<li><a <?php
                    if(basename($_SERVER['SCRIPT_NAME'])=="manage_add.php") {
                        echo "class='current'";
                    }  ?> href="manage_add.php">添加管理员</a></li>
                <?php }?>
				<li><a href="#">站点设置</a></li>
			</ul>
		</li>
		<li><!--  class="current" -->
			<div class="small_title">内容管理</div>
			<ul class="child">
				<li><a <?php
                    if(basename($_SERVER['SCRIPT_NAME'])=="father_module.php") {
                        echo "class='current'";
                    }  ?> href="father_module.php">父板块列表</a></li>
				<li><a <?php
                    if(basename($_SERVER['SCRIPT_NAME'])=="father_module_add.php") {
                        echo "class='current'";
                    }  ?> href="father_module_add.php">添加父板块</a></li>
                <?php
                if (basename($_SERVER['SCRIPT_NAME'])=='father_module_update.php'){
                echo "<li><a href=\"#\" class='current'>编辑父板块</a></li>";
                }
                ?>
                <li><a href="son_module.php"
                        <?php
                        if (basename($_SERVER['SCRIPT_NAME'])=='son_module.php'){
                            echo "class='current'";
                        }
                        ?>
                    >子板块列表</a></li>

				<li><a href="son_module_add.php"
                        <?php
                        if (basename($_SERVER['SCRIPT_NAME'])=='son_module_add.php'){
                            echo "class='current'";
                        }
                        ?>
                    >添加子板块</a></li>
                <?php
                if (basename($_SERVER['SCRIPT_NAME'])=='son_module_update.php'){
                    echo "<li><a href=\"#\" class='current'>编辑子版块</a></li>";
                }
                ?>
				<li><a href="#">帖子管理</a></li>
			</ul>
		</li>
		<li>
			<div class="small_title">用户管理</div>
			<ul class="child">
				<li><a href="users_list.php"
                        <?php
                        if (basename($_SERVER['SCRIPT_NAME'])=='users_list.php'){
                            echo "class='current'";
                        }
                        ?>
                    >用户列表</a></li>
			</ul>
		</li>
	</ul>
</div>