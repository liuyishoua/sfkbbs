<?php
include_once "config.inc.php";
include_once "mysql.inc.php";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title><?php echo $template['title']; ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="style/public.css" />
    <link rel="stylesheet" href="<?php echo $template['css'];?>">
    <?php
    if (isset($template['css1'])){
        echo "<link rel=\"stylesheet\" href=\"{$template['css1']}\">";
    }
    ?>
    <script src="../js/jquery-1.11.3.min.js"></script>
    <?php
    if (basename($_SERVER['SCRIPT_NAME'])=='login.php'||basename($_SERVER['SCRIPT_NAME'])=='register.php'){
        $html = <<<A
    <script>
        $(function () {
            $("img").click(function () {
                $(this).attr("src","inc/vcode.inc.php");
            //    点击更新验证码操作！！！
            })
        })
    </script>
A;
        echo $html;
//        $_SERVER['SCRIPT_NAME']返回的是当前目录文件路径
    }
    ?>
</head>

<body>
<div class="header_wrap">
    <div id="header" class="auto">
        <div class="logo">liuyishou</div>
        <div class="nav">
            <a class="hover" href="../index.php">首页</a>
        </div>
        <div class="serarch">
            <form>
                <input class="keyword" type="text" name="keyword" placeholder="搜索其实很简单" />
                <input class="submit" type="submit" name="submit" value="" />
            </form>
        </div>
        <div class="login">
            <?php
            if (!$member_id){
                echo "<a href=\"../login.php\">登录</a>";
                echo " <a href=\"../register.php\">注册</a>";
            }
            else{
                $link = connect();
                $query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}'";
                $result = execute($link,$query);
                $result_data = mysqli_fetch_assoc($result);
                echo "<a href='member.php?id={$result_data['id']}' style='color: whitesmoke;font-size: 15px;float:left;'>您好!".$_COOKIE['sfk']['name']."</a>".'<a href="login_out.php" style="font-size: 15px;float: right">|退出</a>';
            }
            ?>
        </div>
    </div>
</div>
<div style="margin-top:55px;"></div>
