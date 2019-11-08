<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$template['title']='帖子发布页';
$template['css']='style/publish.css';

$insert['success'] = null;
$title_len['error'] = null;
$title_null['null'] = null;
if (!$member_id=is_login($link)){
    header("Location: login.php");
}
if (isset($_POST['submit'])){
//    因为存在输入，这里可以对输入的内容进行一部分的校验
    if (mb_strlen($_POST['title'])>=64){
        $title_len['error'] = "yes";
    }
    else if ($_POST['title']==''){
        $title_null['null'] = 'yes';
    }
    else{
        $query = "insert into sfk_content(module_id, title, content, time, member_id) values (
'{$_POST['module_id']}','{$_POST['title']}','{$_POST['content']}',now(),'{$member_id}')";
        execute($link,$query);
        if (mysqli_affected_rows($link)==1){
            $insert['success'] = 'yes';
        }
        else{
            $insert['success'] = 'no';
        }
    }
}

?>

<?php include 'inc/header.inc.php'?>
    <div id="position" class="auto">
        <a href="index.php">首页</a> &gt; 发布帖子
    </div>
    <div id="publish">
        <form method="post">

            <select name="module_id">
<!--                里面再多，只选取一个模块的module_id-->
                <?php
                if (parse_url($_SERVER['HTTP_REFERER'])['path']=='/list_father.php'){
                    $query = "select * from sfk_father_module where id={$_GET['id']} order by sort desc ";
                }
                else
                    $query = "select * from sfk_father_module order by sort desc ";

                $father_result = execute($link,$query);
                while ($father_data = mysqli_fetch_assoc($father_result)){
                    echo "<optgroup label='{$father_data['module_name']}'>";
//                    这里添加label标签就是显示前端的数据
                    $query = "select * from sfk_son_module where  father_module_id='{$father_data['id']}'";
                    $son_result = execute($link,$query);
                    while ($son_data = mysqli_fetch_assoc($son_result)){
                        if (isset($_GET['id'])&&$_GET['id']==$son_data['id'])
                        echo "<option selected='selected' value='{$son_data['id']}'>{$son_data['module_name']}</option>";
                        else
                            echo "<option value='{$son_data['id']}'>{$son_data['module_name']}</option>";
                    }
                    echo "</optgroup>";
                }
                ?>
            </select>
            <input class="title" placeholder="请输入标题" name="title" type="text" />
            <a><?php
                if (isset($title_len['error'])){
                    echo "标题不能超过64个字符";
                }
                elseif (isset($title_null['null'])){
                    echo "标题不得为空";
                }
                ?></a>
            <textarea name="content" class="content"></textarea>
            <input class="publish" type="submit" name="submit" value="" />
            <div style="clear:both;"></div>
            <p style="color: blue;margin-top: 10px;font-size: 16px">
                <?php
                if (isset($insert['success'])){
                    if ($insert['success']=='yes'){
                        echo "发帖成功";
                    }
                    else{
                        echo "添加子版块失败";
                    }
                }
                ?>
            </p>
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>