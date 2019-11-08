<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$template['title'] = "板块展示页";
$template['css'] = "style/publish.css";
$link = connect();
if (!$member_id = is_login($link)){
    header("Location: login.php");
}
//这句验证要加上，确保导航栏的正确性，未登陆时能观看别人的贴子以及回复，但是自己不能回复，直接跳到登陆页面

?>

<?php
if (!isset($_GET['id'])){
    $_GET['id'] = 25;
}
$query = "select sfk_father_module.module_name fmodule_name,sfk_son_module.module_name smodule_name
 ,sfk_reply.content,sfk_content.title,sfk_father_module.id fid,sfk_son_module.id sid,sfk_content.id cid
  from sfk_reply,sfk_content,sfk_son_module,sfk_father_module where 
sfk_reply.id={$_GET['id']} and sfk_reply.content_id=sfk_content.id and sfk_content.module_id=
sfk_son_module.id and sfk_son_module.father_module_id=sfk_father_module.id";
//四个表在一起查询，速度可能会变慢,而且容易出错
$result_all = execute($link,$query);
$data_all = mysqli_fetch_assoc($result_all);
$data_all['content'] = nl2br($data_all['content']);

$query = "select * from sfk_content,sfk_member where member_id=sfk_member.id and sfk_content.id={$data_all['cid']}";
$publish_member_result = execute($link,$query);
$publish_member_data = mysqli_fetch_assoc($publish_member_result);

$query = "select * from sfk_content,sfk_member where sfk_content.member_id=sfk_member.id and sfk_content.id={$_GET['id']}";
$member_result = execute($link,$query);
$member_data = mysqli_fetch_assoc($member_result);

?>

<?php

$reply_content['null'] = null;
if (isset($_POST['submit'])){
    if ($_POST['content']==''){
        $reply_content['null'] = "yes";
    }
    else{
        $query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}'";
        $result_member = execute($link,$query);
        $data_member = mysqli_fetch_assoc($result_member);
//    获取回复引用的id号
        $query = "insert into sfk_reply(content_id,quote_id, content, time, member_id) values (
'{$data_all['cid']}','{$_GET['id']}','{$_POST['content']}',now(),'{$data_member['id']}')";
        execute($link,$query);
        $reply_content['null'] = 'no';
    }
}
?>

<?php include_once 'inc/header.inc.php'?>;

    <div id="position" class="auto">
        <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_all['fid']?>"><?php echo $data_all['fmodule_name']?></a> &gt;
        <a href="list_son.php?id=<?php echo $data_all['sid']?>"><?php echo $data_all['smodule_name']?></a> &gt;
        <a href="show.php?id=<?php echo $data_all['cid']?>"><?php echo $data_all['title']?></a>
    </div>
    <div id="publish">
        <div><?php echo $publish_member_data['name']?>: <?php echo $data_all['title']?></div>
<!--        发帖人的id-->
        <div class="quote">
            <p class="title">引用1楼 <?php echo $member_data['name']?> 发表的: </p>
<!--            被引用回复人的id-->
            内容<?php echo $data_all['content']?>
        </div>
        <form method="post">
            <textarea name="content" class="content"></textarea>
            <input class="reply" type="submit" name="submit" value="" />
            <div style="clear:both;"></div>
        </form>
        <p><?php
            if (isset($reply_content['null'])){
                if ($reply_content['null']=='yes'){
                    echo "内容不得为空";
                }
                else if ($reply_content['null']=='no'){
                    echo "成功添加";
                }
            }
            ?></p>
    </div>

<?php include_once "inc/footer.inc.php"?>