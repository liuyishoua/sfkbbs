<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$template['title'] = "注册页面";
$template['css'] = 'style/register.css';
$link = connect();

$member_id = is_login($link);
//因为有三种状态，分别由true，false，以及null代替
?>

<?php
//对数据的有效性进行验证！！！
$isok = true;
$name['user'] = true;
$pw['user'] = true;
$confirm_pw['user'] = true;
$same_name['user'] = true;
$vcode['user'] = true;
if(isset($_POST['submit'])){
    $query = "select * from sfk_member where name='{$_POST['name']}'";
    $result = execute($link,$query);
    if (mysqli_num_rows($result)){
        $same_name['user'] = false;
        $isok = false;
    }
    if(mb_strlen($_POST['name'])>32||empty($_POST['name'])){
        $isok = false;
        $name['user'] = false;
    }
    if(mb_strlen($_POST['pw'])<6||empty($_POST['pw'])){
        $isok = false;
        $pw['user'] = false;
    }
    if(mb_strlen($_POST['confirm_pw'])<6||empty($_POST['confirm_pw'])||$_POST['pw']!=$_POST['confirm_pw']){
        $isok = false;
        $confirm_pw['user'] = false;
    }
    if (strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
        $isok = false;
        $vcode['user'] = false;
    }
    if ($isok){
        $_POST = escape($link,$_POST);
//        本质数组，将数组值进行转义
        $link=connect();
        $query="insert into sfk_member(name,pw,register_time) values('{$_POST['name']}',md5('{$_POST['pw']}'),now())";
        execute($link,$query);
        if(mysqli_affected_rows($link)==1){

//            传入cokkie内的密码使用md5方式进行编码！！！
        }
    }
}
?>

<?php include_once "inc/header.inc.php"; ?>
<div id="register" class="auto">
    <h2>欢迎注册成为 私房库会员</h2>
    <form method="post">
        <label>用户名：<input type="text" name="name"  /><span>*用户名不得为空，并且长度不得超过32个字符</span></label>
        <label>密码：<input type="password" name="pw"  /><span>*密码不得少于6位</span></label>
        <label>确认密码：<input type="password" name="confirm_pw"  /><span>*请输入与上面一致</span></label>
        <label>验证码：<input name="vcode" name="vocode" type="text"  /><span>*请输入下方验证码</span></label>
        <img class="vcode" src="inc/vcode.inc.php" name="vcode"/>
        <label style="color: red;margin-bottom: 10px"><?php
            if (isset($_POST['submit'])){
                if($name['user']==false)
                    echo "用户名不满足要求,";
                if($pw['user']==false)
                    echo "密码不得少于6位,";
                if($confirm_pw['user']==false)
                    echo "确认密码错误.";
                if ($same_name['user']==false){
                    echo "用户名不能重复";
                }
                if ($vcode['user']==false){
                    echo "验证码错误";
                }
            }
            ?>
            <span></span></label>
        <div style="clear:both;"></div>
        <input class="btn" name="submit" type="submit" value="确定注册" />
        <label style="font-size: 20px;color: blue">
            <?php
            if (isset($_POST['submit'])) {
                if ($isok==true)
                    echo "注册成功，用户名：{$_POST['name']}";
                elseif($isok==false){
                    echo "注册失败";
                }
            }
            ?>
        </label>
    </form>
</div>
<?php include_once "inc/footer.inc.php"?>
