<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
//include_once 'inc/vcode.inc.php';
$template['title'] = "欢迎登陆";
$template['css'] = 'style/register.css';
$link = connect();
$login_info['success'] = null;
$vcode['user'] = null;
$member_id=is_login($link);

?>

<?php
if (isset($_POST['submit'])){
    $query = "select * from sfk_member where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
    $result = execute($link,$query);
    $data = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result)==1){
        $login_info['success'] = "yes";
    }
    else{
        $login_info['success'] = 'no';
    }
    if (strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
        $login_info['success'] = 'no';
        $vcode['user'] = 'no';
    }
    else{
        $vcode['user'] = 'yes';
    }
    if($login_info['success']=='yes'){
        setcookie('sfk[name]',$_POST['name']);
//            进行setcookie
//        使用这个直接赋值没有用。
        setcookie('sfk[pw]',md5($_POST['pw']));
    }
}
?>

<?php include_once 'inc/header.inc.php'?>

    <div id="register" class="auto">
        <h2>欢迎会员登录&nbsp;&nbsp;<?php if (isset($_SERVER['HTTP_REFERER'])&&
                (parse_url($_SERVER['HTTP_REFERER'])['path']=='/list_son.php'||parse_url($_SERVER['HTTP_REFERER'])['path']=='/list_father.php'))
            echo "登陆后才能发帖";?></h2>
<!--        这个表示的上一个页面牛逼了！！！只有是在页面点击过去的才能激发，直接输入网址是没有用的-->
        <form method="post">
            <label>用户名：<input type="text" name="name"  /><span></span></label>
            <label>密码：<input type="password" name="pw"  /><span></span></label>
            <label>验证码：<input name="vcode" name="vocode" type="text"  /><span></span></label>
            <img class="vcode" src="inc/vcode.inc.php" name="vcode"/>
            <div style="clear:both;"></div>
            <label style="color: red;margin-bottom: 10px">
                <?php
                if (isset($login_info['success'])){
                    if($vcode['user']=='no'){
                        echo "验证码错误";
                    }
                    else{
                        if ($login_info['success']=='yes'){
                            header("Location: index.php");
//                        登陆成功跳转到index.php页面
                        }
                        elseif ($login_info['success']=='no'){
                            echo "用户名或密码错误，登陆失败";
                        }
                    }
                }
                ?>
                <span></span></label>
            <input class="btn" name="submit" type="submit" value="确定登陆" />
            <label style="font-size: 20px;color: blue">
            </label>
        </form>
    </div>
<?php include_once 'inc/footer.inc.php'?>