<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
$link=connect();
$template['title']="子版块列表";
?>
<?php
$sort['error'] = null;
if (isset($_POST['submit'])){
    foreach ($_POST['sort'] as $key=>$val){
        if (!is_numeric($val)){
            $sort['error'] = "yes";
        }
        $query[] = "update sfk_son_module set sort={$val} where id='{$key}'";
    }
    if ($sort['error']==null){
        execute_multi($link,$query,$error);
        $sort['error'] = "no";
    }
}
?>

<?php include 'inc/header.inc.php '?>
    <div id="main">
    <div class="title">子版块列表</div>
    <form action="" method="post">
        <table class="list">
            <tr>
                <th>排序</th>
                <th>版块名称</th>
                <th>所属父板块</th>
                <th>版主</th>
                <th>操作</th>
            </tr>
            <?php
                $query = "select * from sfk_son_module";
                $son_result = execute($link,$query);
                while ($son_data_result = mysqli_fetch_assoc($son_result)){
                    $link = connect();
                    $query = "select * from sfk_father_module where id = '{$son_data_result['father_module_id']}'";
                    $father_module_name_result = execute($link,$query);
                    $father_module_name_data = mysqli_fetch_assoc($father_module_name_result);
                    $father_module_name = $father_module_name_data['module_name'];
    //                这里进行多表查询能大大简化代码！！！
                    $html = <<<A
<tr>
    <td><input class="sort" type="text" name="sort[{$son_data_result['id']}]" value="{$son_data_result['sort']}"/></td>
    <td>{$son_data_result['module_name']}[id:{$son_data_result['id']}]</td>
    <td>{$father_module_name}</td>
    <td>{$son_data_result['member_id']}</td>
    <td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="son_module_update.php?id={$son_data_result['id']}">[编辑]</a>&nbsp;&nbsp;<a href="son_module_delete.php?id={$son_data_result['id']}">[删除]</a></td>
</tr>
A;
                    echo $html;

                }
            ?>
        </table>
        <input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="排序" />
    </form>
    <?php
        if (isset($sort['error'])){
            if ($sort['error']=='no'){
                echo "排序成功";
            }
            elseif ($sort['error']=='yes'){
                echo "排序参数必须为数字！！！";
            }
        }
    ?>
<?php include 'inc/footer.inc.php'?>