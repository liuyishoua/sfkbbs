<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$template['title'] = "子板块列表";
$template['css'] = "style/list.css";
$link = connect();
$member_id = is_login($link);

?>

<?php
$son_data = null;
$father_data = null;
if (isset($_GET['id'])){
    $query = "select * from sfk_son_module where id={$_GET['id']}";
    $son_result = execute($link,$query);
    $son_data = mysqli_fetch_assoc($son_result);
    $query = "select * from sfk_father_module where id='{$son_data['father_module_id']}'";
    $father_result = execute($link,$query);
    $father_data = mysqli_fetch_assoc($father_result);


}else{
//    如果get不存在，则随机进入一个子版块列表页

}
?>

<?php include_once 'inc/header.inc.php'?>;

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $father_data['id'];?>"><?php echo $father_data['module_name'];?></a>
    &gt;<a href="list_son.php?id=<?php echo $son_data['id'];?>"><?php echo $son_data['module_name'];?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo $son_data['module_name']?></h3>
            <div class="num">
                <?php
                $query = "select count(*) from sfk_son_module,sfk_content where sfk_content.module_id=sfk_son_module.id 
and sfk_son_module.id={$_GET['id']}";
                $count_all = num($link,$query);
                $query = "select count(*) from sfk_son_module,sfk_content where sfk_content.module_id=sfk_son_module.id 
and sfk_son_module.id={$_GET['id']} and sfk_content.time > current_date";
                $count_today = num($link,$query);

                $query = "select * from sfk_member,sfk_son_module where sfk_son_module.member_id=sfk_member.id and sfk_son_module.id={$_GET['id']}";
                $member_result = execute($link,$query);
                $member_data = mysqli_fetch_assoc($member_result);
                ?>
                今日：<span><?php echo $count_today?></span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $count_all?></span>
            </div>
            <div class="moderator">版主：<span><?php if ($member_data['name']=='')echo "暂无版主"; else echo $member_data['name']?></span></div>
            <div class="notice"><?php echo $son_data['info']?></div>
            <div class="pages_wrap">
                <a class="btn publish" href="publish.php?id=<?php echo $_GET['id']?>"></a>
                <div class="pages">
                    <?php
                    $page = page($count_all,3,3);
                    echo $page['html'];
                    ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>

        <ul class="postsList">
            <?php
            $query = "select sfk_content.title,sfk_content.id,sfk_member.name,sfk_content.time,sfk_content.times,sfk_content.module_id,
sfk_member.photo,sfk_son_module.module_name from sfk_member,sfk_content,sfk_son_module where 
sfk_content.member_id=sfk_member.id and 
sfk_content.module_id={$_GET['id']} and 
sfk_son_module.id=sfk_content.module_id {$page['limit']}";
            $result_content = execute($link,$query);

            while ($data_content = mysqli_fetch_assoc($result_content)){
                $query = "select * from sfk_reply where content_id={$data_content['id']} order by time desc limit 1";
                $result_last = execute($link,$query);
                $data_last = mysqli_fetch_assoc($result_last);
                if ($data_last==null){
                    $data_last['time'] = "暂无回复";
                }
                $query = "select * from sfk_reply where content_id={$data_content['id']}";
                $result_times = num($link,$query);
                if ($result_times==null){
                    $result_times=0;
                }

                $query = "select sfk_member.id from sfk_member,sfk_content where sfk_member.id=member_id and sfk_content.id={$data_content['id']}";
                $result_member = execute($link,$query);
                $data_member = mysqli_fetch_assoc($result_member);
                ?>
                <li>
                    <div class="smallPic">
                        <a href="member.php?id=<?php echo $data_member['id']?>">
                            <img width="45" height="45" src="<?php if ($data_content['photo']!='')echo $data_content['photo'];else{echo "style/old.gif";}?>">
                        </a>
                    </div>
                    <div class="subject">
                        <div class="titleWrap"><a href="list_son.php?id=<?php echo $data_content['module_id']?>">[<?php echo $data_content['module_name']?>]</a>&nbsp;&nbsp;<h2 ><a href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title']?></a></h2></div>
                        <p>
                            楼主：<?php echo $data_content['name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $data_last['time']?>
                        </p>
                    </div>
                    <div class="count">
                        <p>
                            回复<br /><span><?php echo $result_times?></span>
                        </p>
                        <p>
                            浏览<br /><span><?php echo $data_content['times']?></span>
                        </p>
                    </div>
                    <div style="clear:both;"></div>
                </li>
            <?php }?>
        </ul>

        <div class="pages_wrap">
            <a class="btn publish" href="publish.php?id=<?php echo $_GET['id']?>"></a>
            <div class="pages">
                <?php
                $page = page($count_all,3,3);
                echo $page['html'];
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">版块列表</div>
            <ul class="listWrap">
                <?php
                $query = "select * from sfk_father_module";
                $result_f = execute($link,$query);
                while ($data_f = mysqli_fetch_assoc($result_f)){
                    ?>
                    <li>
                        <h2><a href="list_father.php?id=<?php echo $data_f['id']?>" style="font-size: 15px;color: black"><?php echo $data_f['module_name'];?></a></h2>
                        <ul>
                            <?php
                            $query = "select * from sfk_son_module where father_module_id='{$data_f['id']}'";
                            $result_s = execute($link,$query);
                            while ($data_s = mysqli_fetch_assoc($result_s)){
                                ?>
                                <li><h3><a href="list_son.php?id=<?php echo $data_s['id'];?>"><?php echo $data_s['module_name'];?></a></h3></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?php include_once "inc/footer.inc.php"?>
