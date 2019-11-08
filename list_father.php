<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$template['title'] = "父板块列表";
$template['css'] = "style/list.css";
$link = connect();
$member_id = is_login($link);
?>

<!--一般传入的数据需要进行验证，还有提交的数据也要进行验证-->
<?php
$father_data = null;
if (isset($_GET['id'])||$_GET['id']=64){
//    默认进入汽车父板块！！！
    $query = "select * from sfk_father_module where id='{$_GET['id']}'";
    $father_result = execute($link,$query);
    if (mysqli_num_rows($father_result)==1){
        $father_data = mysqli_fetch_assoc($father_result);
    }
}

$son_id = null;
$son_all_module = null;
if (isset($_GET['id'])){
    $query = "select * from sfk_son_module where father_module_id='{$_GET['id']}'";
    $son_result = execute($link,$query);
    while ($son_data = mysqli_fetch_assoc($son_result)){
        $son_id.=$son_data['id'].",";
        $son_all_module.="<a href='list_son.php?id={$son_data['id']}'>".$son_data['module_name']." </a>";
    }
//    trim不改变son_id的值
    $son_id = trim($son_id,',');
    if ($son_id==''){
        $son_id = 0;
    }
}
$query = "select count(*) from sfk_content where module_id in ({$son_id})";
//count(*)如果没找到，则返回count的值为0
$query1 = "select count(*) from sfk_content where module_id in ({$son_id}) and time > CURRENT_DATE ";
$result_count = execute($link,$query);
$count_today = execute($link,$query1);
$data_count = mysqli_fetch_row($result_count);
$data_count_today = mysqli_fetch_row($count_today);
//var_dump($data_count[0]);



?>
<?php include_once 'inc/header.inc.php'?>;
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="<?php echo "list_father.php?id=".$father_data['id'];?>"><?php echo $father_data['module_name'];?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo $father_data['module_name'];?></h3>
            <div class="num">
                今日：<span><?php echo $data_count_today[0];?></span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $data_count[0];?></span>
                <div class="moderator"> 子版块： <?php echo $son_all_module;?></div>
            </div>
            <div class="pages_wrap">
                <a class="btn publish" href="publish.php?id=<?php echo $father_data['id']?>"></a>
                <div class="pages">
                    <?php
                    $page = page($data_count[0],3,3);
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
sfk_content.module_id in ({$son_id}) and 
sfk_son_module.id=sfk_content.module_id {$page['limit']}";
$result_content = execute($link,$query);

//        $data_content = mysqli_fetch_all($result_content);
//        all返回的全部是以数字下标为基础的
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
            <a class="btn publish" href="publish.php?id=<?php echo $father_data['id']?>"></a>
            <div class="pages">
                <?php
                $page = page($data_count[0],3,3);
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