<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
$link=connect();
$template['title']="用户列表";
?>
<?php
$delete['success'] = null;
if (isset($_GET['id'])){
    $query = "delete from sfk_member where id={$_GET['id']}";
    $result = execute($link,$query);
    if (mysqli_affected_rows($link)==1){
        $delete['success'] ='yes';
    }
    else{
        $delete['success'] = 'no';
    }

}
?>
<?php include 'inc/header.inc.php '?>

<div id="main">
    <div class="title">子版块列表</div>
    <form action="" method="post">
        <table class="list">
            <tr>
                <th>用户名</th>
                <th>md5密码</th>
                <th>注册时间</th>
                <th>头像</th>
                <th>操作</th>
            </tr>
            <?php
            $query = "select * from sfk_member";
            $result = execute($link,$query);
            while ($data = mysqli_fetch_assoc($result)){
            ?>
            <tr>
                <td><a href="overdue.php?id=<?php echo $data['id']?>"><?php echo $data['name']?></a></td>
                <td><?php echo $data['pw']?></td>
                <td><?php echo $data['register_time']?></td>
                <td><img width="30" height="30" src="<?php echo "../{$data['photo']}"?>"></td>
                <td>&nbsp;&nbsp;<a href="users_list.php?id=<?php echo $data['id']?>">[删除]</a></td>
            </tr>
            <?php }?>
        </table>
        <p><?php
            if (isset($delete['success'])){
                if ($delete['success']=='yes'){
                    echo "删除成功";
                }
                else{
                    echo "删除失败";
                }
            }
            ?></p>
    </form>
<?php include 'inc/footer.inc.php'?>
