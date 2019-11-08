<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$template['title'] = "板块展示页";
$template['css'] = "style/show.css";
$link = connect();
$member_id = is_login($link);
//这句验证要加上，确保导航栏的正确性，未登陆时能观看别人的贴子以及回复，但是自己不能回复，直接跳到登陆页面

?>
<?php
if (!isset($_GET['id'])){
    $_GET['id'] = 10;
}
$query = "update sfk_content set times=times+1 where id = {$_GET['id']}";
execute($link,$query);
/*var_dump($query);
exit();*/

$query = "select sfk_father_module.module_name,sfk_son_module.module_name as smodule_name ,sfk_content.title ,
sfk_father_module.id as father_id,sfk_son_module.id as son_id,sfk_content.content,sfk_content.time,sfk_content.times,sfk_content.id cid 
from sfk_father_module,sfk_son_module,sfk_content,sfk_member where sfk_content.module_id=sfk_son_module.id and 
sfk_son_module.father_module_id=sfk_father_module.id and sfk_content.id={$_GET['id']}";
//四个表在一起查询，速度可能会变慢,而且容易出错
$result_all = execute($link,$query);
$data_all = mysqli_fetch_assoc($result_all);

$query = "select * from sfk_member,sfk_content where member_id=sfk_member.id and sfk_content.id={$_GET['id']}";
$member_result = execute($link,$query);
$member_data = mysqli_fetch_assoc($member_result);
$data_all['content'] = nl2br($data_all['content']);
//var_dump(htmlspecialchars($data_all['content']));
//函数通过使用html能识别的特殊字符例如&lt来代替<>，防止恶意植入
//exit();
//如果用户发帖使用了换行，这里能够通过添加《br》正确换行

?>
<?php include_once 'inc/header.inc.php'?>;

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_all['father_id']?>"><?php echo $data_all['module_name']?></a> &gt;
    <a href="list_son.php?id=<?php echo $data_all['son_id']?>"><?php echo $data_all['smodule_name']?></a> &gt;
    <a href="show.php?id=<?php echo $data_all['cid']?>"><?php echo $data_all['title']?></a>
</div>
<div id="main" class="auto">
    <div class="wrap1">
        <div class="pages">
            <?php
            //分页函数操作
            $page_size = 3;
            $query = "select count(*) from sfk_reply where content_id={$_GET['id']}";
            $reply_count = num($link,$query);
            $page = page($reply_count,$page_size,5);
            echo $page['html'];
            ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>"></a>
        <div style="clear:both;"></div>
    </div>

    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a href="member.php?id=<?php echo $member_data['member_id']?>">
                    <img width="120" height="120" src="<?php if ($member_data['photo']!='')echo $member_data['photo'];else{echo "style/old.gif";}?>" />
                </a>
            </div>
            <div class="name">
                <a href=""><?php echo $member_data['name']?></a>
            </div>
        </div>
        <div class="right">
            <div class="title">
                <h2><?php echo $data_all['title']?></h2>
                <span>阅读：<?php echo $data_all['times']?>&nbsp;|&nbsp;回复：15</span>
                <div style="clear:both;"></div>
            </div>
            <div class="pubdate">
                <span class="date">发布于：<?php echo $data_all['time']?> </span>
                <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
            </div>
            <div class="content">
                <?php echo $data_all['content']?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>

    <?php
//    $get['p']一定存在，在分页函数内部就惊醒了处理的
    $i=($_GET['p']-1)*$page_size;
//    标识楼主层数
    $query = "select * from sfk_reply where sfk_reply.content_id={$_GET['id']} order by time desc {$page['limit']} ";
    $reply_result = execute($link,$query);
    while ($reply_data = mysqli_fetch_assoc($reply_result)){
        $query = "select * from sfk_reply,sfk_member where member_id=sfk_member.id and sfk_reply.id={$reply_data['id']}";
        $result = execute($link,$query);
        $data = mysqli_fetch_assoc($result);
        $i++;
        if ($reply_data['quote_id']==0){
    ?>
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a href="member.php?id=<?php echo $reply_data['member_id']?>">
<!--                    img没有href标签，在a标签上使用-->
<!--                    图片设置大小可以合理的精心缩放-->
                    <img width="120" height="120" src="<?php if ($data['photo']!='')echo $data['photo'];else{echo "style/old.gif";}?>" />
                </a>
            </div>
            <div class="name">
                <a href=""><?php echo $data['name']?></a>
            </div>
        </div>
        <div class="right">
            <div class="pubdate">
                <span class="date">回复时间：<?php echo $reply_data['time']?></span>
                <span class="floor"><?php echo $i?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $reply_data['id']?>">引用</a></span>
            </div>
            <div class="content">
                <?php echo $reply_data['content']?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
        <?php }else{
            $query = "select * from sfk_reply,sfk_member where sfk_reply.id={$reply_data['quote_id']} and sfk_reply.member_id=sfk_member.id";
            $quote_result = execute($link,$query);
            $quote_data = mysqli_fetch_assoc($quote_result);

            ?>

<!--引用页面回复-->
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a data-uid="2374101" href="member.php?id=<?php echo $reply_data['member_id']?>">
                    <img width="120" height="120" src="<?php if ($data['photo']!='')echo $data['photo'];else{echo "style/old.gif";}?>" />
                </a>
            </div>
            <div class="name">
                <a class="J_user_card_show mr5" data-uid="2374101" href=""><?php echo $data['name']?></a>
            </div>
        </div>
        <div class="right">
            <div class="pubdate">
                <span class="date">回复时间：<?php echo $reply_data['time']?></span>
                <span class="floor"><?php echo $i?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $reply_data['id']?>">引用</a></span>
            </div>
            <div class="content">
                <div class="quote">
                    <h2>引用 1楼 <?php echo $quote_data['name']?> 发表的: </h2>
                    <?php echo $quote_data['content']?>
                </div>
                <?php echo $reply_data['content']?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
        <?php } ?>
    <?php } ?>
    <div class="wrap1">
        <div class="pages">
            <?php echo $page['html'];?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>"></a>
        <div style="clear:both;"></div>
    </div>
</div>
<?php include_once "inc/footer.inc.php"?>