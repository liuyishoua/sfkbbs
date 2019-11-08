<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$template['title']='主页';
$template['css']='style/index.css';

$member_id = is_login($link);

?>
<?php include 'inc/header.inc.php'?>
    <div id="hot" class="auto">
        <div class="title">热门动态</div>
        <ul class="newlist">
            <!-- 20条 -->
            <li><a href="#">[库队]</a> <a href="#">私房库实战项目录制中...</a></li>

        </ul>
        <div style="clear:both;"></div>
    </div>
<!--如下写法排版更好，更容易理解-->
<?php
$query="select * from sfk_father_module order by sort desc";
$result_father=execute($link, $query);
while($data_father=mysqli_fetch_assoc($result_father)){
    ?>
    <div class="box auto">
        <div class="title">
            <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a>
        </div>
        <div class="classList">
            <?php
            $query="select * from sfk_son_module where father_module_id={$data_father['id']}";
            $result_son=execute($link, $query);
            if(mysqli_num_rows($result_son)){
                while ($data_son=mysqli_fetch_assoc($result_son)){
                    $query="select count(*) from sfk_content where module_id={$data_son['id']} and time > CURDATE()";
//                    在mysql中时间可以进行比较
                    $count_today=num($link,$query);
                    $query="select count(*) from sfk_content where module_id={$data_son['id']}";
                    $count_all=num($link,$query);
                    $html=<<<A
					<div class="childBox new">
						<h2><a href="list_son.php?id={$data_son['id']}">{$data_son['module_name']}</a> <span>(今日帖{$count_today})</span></h2>
						帖子总数：{$count_all}<br />
					</div>
A;
                    echo $html;
                }
            }else{
                echo '<div style="padding:10px 0;">暂无子版块...</div>';
            }
            ?>
            <div style="clear:both;"></div>
        </div>
    </div>
<?php }?>

<?php include 'inc/footer.inc.php'?>