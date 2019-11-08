<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
function isSame(){
    $link = connect();
    $query = "select * from sfk_father_module where module_name='{$_POST['module_name']}'";
    $result = execute($link,$query);
    if(mysqli_num_rows($result)>=1){
        return true;
    }
    else{
        return false;
    }
}
if (isset($_POST['submit']))
{
//    var_dump($_POST['module_name']);
//    var_dump($_POST['sort']);

    if (empty($_POST['module_name'])){
        $modulename['null']='yes';
    }elseif (!is_numeric($_POST['sort'])){
        $sort['num']='not';
    }elseif (isSame()){
        $isSame["module_name"]='equal';
    }
    else{
        $query = "insert into sfk_father_module(module_name,sort) values ('{$_POST['module_name']}','{$_POST['sort']}')";
        $link = connect();
        execute($link,$query);
        $add_module['success']='yes';
    }
//    exit();
}

$template['title']="添加父板块";
?>
<?php include 'inc/header.inc.php'?>
<div id="main">
    <div class="title">添加父版块</div>
    <table class="list">
        <form method="post">
<!--            没有提交地址默认提及到本页面-->
            <table class="au">
                <tr>
                    <td>版块名称</td>
                    <td><input name="module_name" type="text" maxlength="60" size="20"/></td>
<!--                    size表示输入框的宽度-->
                    <td >
                        版块名称不得为空，最大不得超过66个字符
                    </td>
                </tr>
                <tr>
                    <td>排序</td>
                    <td><input name="sort" value="" type="text"/></td>
                    <td>
                        填写一个数字即可
                    </td>
                </tr>
            </table>
            <input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
            <p><?php
                if (isset($modulename['null'])){
                    echo "模块名称不为空";
                } elseif(isset($sort['num'])){
                    echo "排序必须为数字";
                }
                elseif (isset($isSame['module_name'])){
                    echo "模块名{$_POST['module_name']}不能出现多次；";
                }
                else if (isset($add_module['success'])){
                    echo "添加成功,模块名：{$_POST['module_name']}，排序名：{$_POST['sort']}";
                }
                ?></p>
        </form>
    </table>
</div>
<?php include 'inc/footer.inc.php'?>
