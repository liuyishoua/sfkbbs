<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$template['title']='帖子发布页';
$template['css']='style/publish.css';
if (!$member_id=is_login($link)){
    header("Location: login.php");
}

?>

<?php
//获取页面所需数据
$query = "select sfk_father_module.module_name,sfk_son_module.module_name as smodule_name ,sfk_content.title ,
sfk_father_module.id as father_id,sfk_son_module.id as son_id,sfk_content.content,sfk_content.time,sfk_content.times,sfk_content.id cid
 from sfk_father_module,sfk_son_module,sfk_content where sfk_content.module_id=sfk_son_module.id and 
sfk_son_module.father_module_id=sfk_father_module.id and sfk_content.id={$_GET['id']}";
//四个表在一起查询，速度可能会变慢,而且容易出错
$result_all = execute($link,$query);
$data_all = mysqli_fetch_assoc($result_all);
$query = "select * from sfk_content,sfk_member where sfk_content.member_id=sfk_member.id and sfk_content.id={$_GET['id']}";
$member_result = execute($link,$query);
$member_data = mysqli_fetch_assoc($member_result);
$data_all['content'] = nl2br($data_all['content']);

//获取回复人的id号
$query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}'";
$member_id_result = execute($link,$query);
$member_id_data = mysqli_fetch_assoc($member_id_result);

//执行sql存入所需数据
$reply['success'] = null;
$content['null'] = null;
if (isset($_POST['submit'])){
    if ($_POST['content']==''){
        $content['null'] = 'yes';
    }
    else{
        $query = "insert into sfk_reply(content_id, content, time, member_id) values (
'{$_GET['id']}','{$_POST['content']}',now(),'{$member_id_data['id']}'
)";
        execute($link,$query);
        $reply['success'] = 'yes';
    }
}

?>

<?php include 'inc/header.inc.php'?>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_all['father_id']?>">
        <?php echo $data_all['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $data_all['son_id']?>">
        <?php echo $data_all['smodule_name']?></a>
    &gt; <a href="show.php?id=<?php echo $data_all['cid']?>"><?php echo $data_all['title']?></a>
</div>
<div id="publish">
    <div>回复：由 <?php echo $member_data['name']?> 发布的 <?php echo $data_all['title']?></div>
    <form method="post">
        <textarea name="content" class="content"></textarea>
        <input class="reply" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
        <p style="font-size: 16px"><?php if (isset($reply['success'])) echo "回复成功！";
        elseif (isset($content['null'])) echo "内容不得为空";?></p>
    </form>
</div>
<?php include 'inc/footer.inc.php'?>
