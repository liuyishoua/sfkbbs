<?php
include_once "../inc/config.inc.php";
include_once "../inc/mysql.inc.php";
$link = connect();
?>
<?php
if (isset($_GET['id'])){
    $link = connect();
    $query = "delete from sfk_son_module where id='{$_GET['id']}'";
    $result = execute($link,$query);
    if (mysqli_affected_rows($link)==1){
        echo "删除成功";
    }
}
else{
    echo "删除失败";
}
?>
