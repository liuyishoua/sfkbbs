
<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
    $link = connect();
    $query = "select * from sfk_son_module where father_module_id={$_GET['id']}";
    $result = execute($link,$query);
    if (mysqli_affected_rows($link)){
        echo "父板块含有子版块，请先删除子版块";
        exit();
    }

    $query = "delete from sfk_father_module where id='{$_GET['id']}'";
//    echo $_GET;
    execute($link,$query);
    if (mysqli_affected_rows($link)==1){
//    表示数据库更改了一行数据
        exit("删除成功");
//    语句执行到exit停止，页面往上内容一概执行
    }
?>
