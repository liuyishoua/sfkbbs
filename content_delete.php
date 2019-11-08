<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
if(!$member_id=is_login($link)){
    header("Location: login.php");
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    echo "id参数不合法";
    exit();
}

$query="select member_id from sfk_content where id={$_GET['id']}";
$result_content=execute($link, $query);
if(mysqli_num_rows($result_content)==1){
    $data_content=mysqli_fetch_assoc($result_content);
    if(check_user($member_id,$data_content['member_id'])){
        $query="delete from sfk_content where id={$_GET['id']}";
        execute($link, $query);
        if(mysqli_affected_rows($link)==1){
            echo "删除成功";
        }else{
            echo "对不起删除失败";
        }
    }else{
        echo "这个帖子不属于你，你没有权限!";
    }
}else{
    echo "帖子不存在";
}
?>