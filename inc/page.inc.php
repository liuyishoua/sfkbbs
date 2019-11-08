<?php
function page($count,$page_size,$btn_num,$page='p'){
    if (!isset($_GET[$page])||!is_numeric($_GET[$page])||$_GET[$page]<1){
        $_GET[$page] = 1;
//        如果不存在，赋值为1
    }
    if ($count==0){
        $data = array(
            'limit'=>'',
            'html'=>''
        );
        return $data;
    }
    $page_all = ceil($count/$page_size);
    if ($_GET[$page]>$page_all){
        $_GET[$page] = $page_all;
//当前页数超过总页数，将当前页数设置为总页数
    }


//  构造url
    /*   var_dump($_SERVER['SCRIPT_NAME']);
    输出'/test.php'
   */
    $current_url = $_SERVER['REQUEST_URI'];
    $arr_current = parse_url($current_url);
    /*var_dump($arr_current);
  'path' => string '/test.php' (length=9)
  'query' => string 'page=8' (length=6)
    */
    $current_path = $arr_current['path'];
    $url = null;
    /*    var_dump($current_url);
      输出'/test.php?page=8'
    */
    if (isset($arr_current['query'])){
        parse_str($arr_current['query'],$arr_query);
        /* http://localhost/test.php?page=8&id=2
        var_dump($arr_query);
     'page' => string '8' (length=1)
      'id' => string '2' (length=1)*/
        unset($arr_query[$page]);
        if (empty($arr_query)){
            $url = "{$current_path}?{$page}=";
        }
        else{
            $other = (http_build_query($arr_query));
//        改函数用于将字符转化为字符串
            $url = "{$current_path}?{$other}&{$page}=";
        }
    }
    else{
        $url = "{$current_path}?{$page}=";
    }


    $start = ($_GET[$page]-1)*$page_size;
    $limit = "limit {$start},{$page_size}";
    $html = null;
    if ($btn_num>=$page_all){
        for ($i=1;$i<=$page_all;$i++){
            if ($_GET[$page]==$i){
                $html[$i]="<span>{$i}</span>&nbsp;&nbsp;";
            }
            else{
                $html[$i]="<a href='{$url}{$i}'>{$i}</a>&nbsp;&nbsp;";
            }
        }
    }
    else{
        $num_left = floor(($btn_num-1)/2);
        $start = $_GET[$page] - $num_left;
        $end = $start+$btn_num-1;
        if ($start<=1){
            $start = 1;
//            判断如果初始start小于1
        }
        if ($end>=$page_all){
            $start = $page_all-$btn_num+1;
//            判断如果结束end大于最大页码数量
        }
        for ($i=1;$i<=$btn_num;$i++){
            if ($_GET[$page] == $start){
                $html[$start]="<span>{$start}</span>&nbsp;&nbsp;";
            }
            else{
                $html[$start]="<a href='{$url}{$start}'>{$start}</a>&nbsp;&nbsp;";
            }
            $start++;
        }
        if ($btn_num>=3){
            reset($html);
            $key_first = key($html);
//            获取单元键值！！！
            end($html);
            $key_end = key($html);
            if ($key_first!=1){
//                只对内部数组做出更改，不添加数组长度，保证了完整性！！！
                array_shift($html);
                array_unshift($html,"<a href='{$url}1'>1...</a>&nbsp;&nbsp;");
            }
            if ($key_end!=$page_all){
                array_pop($html);
                array_push($html,"<a href='{$url}{$page_all}'>...{$page_all}</a>&nbsp;&nbsp;");
            }
        }
    }
//    增添上一页下一页，其不在传入参数btn_num里面
    if ($_GET[$page]!=1){
        $pre = $_GET[$page]-1;
        array_unshift($html,"<a href='{$url}{$pre}'>«上一页</a>&nbsp;&nbsp;");
    }
    if ($_GET[$page]!=$page_all){
        $next= $_GET[$page]+1;
        array_push($html,"<a href='{$url}{$next}'>下一页»</a>&nbsp;&nbsp;");
      }
//    php大多函数不改变变量内部的值！！！

//    将数组转化为字符串，将这个放在后面较好！！！
    $html = implode(" ",$html);
    $data = array(
        'limit'=>$limit,
        'html'=>$html
    );
    return $data;
}
//$page = page(100,10,'page',5);
//var_dump($page['html']);
//echo $page['html'];
//echo "当前页面为".$_GET['page'];
?>