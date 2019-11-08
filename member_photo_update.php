<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
$link = connect();

?>
<?php

$upload['success'] = null;
if (isset($_POST['submit'])&&isset($_GET['id'])){
    if(is_uploaded_file($_FILES['file']['tmp_name'])){
        $arr = pathinfo($_FILES['file']['name']);
        $newName=time().rand(1000,9999);
//        随机生成文件名
        $newPath = "uploads/{$newName}.{$arr['extension']}";
        if (move_uploaded_file($_FILES['file']['tmp_name'],$newPath)){
//            移动文件事先就要建好文件夹
            $upload['success'] = 'yes';
            $query = "update sfk_member set photo='{$newPath}' where id={$_GET['id']}";
            execute($link,$query);

        }
        else{
            $upload['success'] = 'no';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
 <script src="js/jquery-1.11.3.min.js"></script>
<style type="text/css">
    body {
    font-size:12px;
	font-family:微软雅黑;
}
h2 {
    padding:0 0 10px 0;
	border-bottom: 1px solid #e3e3e3;
	color:#444;
}
.submit {
    background-color: #3b7dc3;
	color:#fff;
	padding:5px 22px;
	border-radius:2px;
	border:0px;
	cursor:pointer;
	font-size:14px;
}
#main {
	width:80%;
	margin:0 auto;
}
</style>

<script>
    $(function () {
        $("#file").on("change",function () {
                src = URL.createObjectURL(this.files[0]); //转成可以在本地预览的格式
                //直接使用这一条语句就行
                //url.creatObjectUrl转化为本地预览
                // console.log(this.files[0]);
                $("img").attr("src",src);
        })
    })
</script>

</head>
<body>
	<div id="main">
		<h2>更改头像</h2>
		<div>
			<h3>原头像：</h3>
            <img width="120" height="120" src="">
		</div>
		<div style="margin:15px 0 0 0;">
			<form method="post" action="" enctype="multipart/form-data" >
<!--                注意要添加上面enctype，表示要上传文件，不加files超全局变量为空值-->
				<input style="cursor:pointer;" width="100" type="file" name="file" id="file" value=""/><br /><br />
				<input class="submit" type="submit" value="submit" name="submit"/>
			</form>
            <p><?php
                if (isset($_POST['submit'])){
                    if ($upload['success']=='yes'){
                        echo "上传成功";
                    }
                    else{
                        echo "上传失败";
                    }
                }
                ?></p>
		</div>
	</div>
</body>
</html>