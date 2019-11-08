<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
$template['title']='管理员列表页';
$template['css']='style/public.css';
?>

<?php include 'inc/header.inc.php'?>

<div id="main">
	<div class="title">管理员列表</div>
	<table class="list">
		<tr>
			<th>名称</th>	 	 	
			<th>等级</th>
			<th>创建日期</th>
			<th>操作</th>
		</tr>
		<?php 
		$query="select * from sfk_manage";
		$result=execute($link,$query);
		while ($data=mysqli_fetch_assoc($result)){
			if($data['level']==0){
				$data['level']='超级管理员';
			}else {
                $data['level'] = '普通管理员';
            }
			$delete_url="manage_delete.php?id={$data['id']}";
			
$html=<<<A
			<tr>
				<td>{$data['name']} [id:{$data['id']}]</td>
				<td>{$data['level']}</td>
				<td>{$data['create_time']}</td>
				<td><a href="{$delete_url}">[删除]</a></td>
			</tr>
A;
			echo $html;
		}
		?>
	</table>
</div>
<?php include 'inc/footer.inc.php'?>