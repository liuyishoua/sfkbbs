<?php
function is_login($link){
//    传入参数需要link，应为内部需要访问数据库操作
    if (isset($_COOKIE['sfk']['name'])&&isset($_COOKIE['sfk']['pw'])){
        $query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}' and pw='{$_COOKIE['sfk']['pw']}'";
        $result = execute($link,$query);
        $data = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result)==1){
            return $data['id'];
        }
        else{
            return false;
        }
    }
    else{
        return false;
//        不存在以及不正确都返回false
    }
}
function check_user($member_id,$content_member_id){
    if($member_id==$content_member_id){
        return true;
    }else{
        return false;
    }
}
function is_manage_login($link){
    if (isset($_SESSION['manage']['name'])){
//        存在再判断，否则sql语句出现‘’，就会报错
//        迎进思维，出现错误，可以通过if语句进行判断，消除错误
        $query = "select * from sfk_manage where name='{$_SESSION['manage']['name']}'";
        $result = execute($link,$query);
        if (mysqli_num_rows($result)==1){
            $result_data = mysqli_fetch_assoc($link,$query);
            return $result_data['id'];
        }
    }
}
?>
