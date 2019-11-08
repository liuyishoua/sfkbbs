<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
$is_login['success'] = null;
if(isset($_POST['submit'])){
	$query="insert into sfk_manage(name,pw,create_time,level) values('{$_POST['name']}',md5({$_POST['pw']}),now(),{$_POST['level']})";
	execute($link,$query);
	if(mysqli_affected_rows($link)==1){
	    $is_login['success'] = 'yes';
	}else{
        echo "添加失败";
        exit();
	}
}
$template['title']='管理员添加页';
$template['css']='style/public.css';
?>
<?php include 'inc/header.inc.php'?>
<div id="main">
	<div class="title" style="margin-bottom:20px;">添加管理员</div>
	<form method="post">
		<table class="au">
			<tr>
				<td>管理员名称</td>
				<td><input name="name" type="text" /></td>
				<td>
					名称不得为空，不得超过32个字符
				</td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input name="pw" type="text" /></td>
				<td>
					不能少于6位
				</td>
			</tr>
			<tr>
				<td>等级</td>
				<td>
					<select name="level">
						<option value="1">普通管理员</option>
						<option value="0">超级管理员</option>
					</select>
				</td>
				<td>
					默认为普通管理员（不具备，后台管理员管理权限）
				</td>
			</tr>
		</table>
		<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
	</form>
    <p> <?php if (isset($is_login['success'])){
        if ($is_login['success']='yes')
            echo "添加成功";
        else{
            echo "添加失败";
        }
        }?></p>
</div>
<?php include 'inc/footer.inc.php'?>