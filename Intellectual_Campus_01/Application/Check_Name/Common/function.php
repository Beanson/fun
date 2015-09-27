<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/9/19
 * Time: 21:25
 */
include 'GDUFS_Login.php';
//二维数组去掉重复值
function array_unique_fb($array2D)
{
    foreach ($array2D as $v)
    {
        $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        $temp[] = $v;
    }

    $temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
    foreach ($temp as $k => $v)
    {
        $temp[$k] = explode(",",$v);   //再将拆开的数组重新组装
    }
    return $temp;
}

function abc(){
    echo "s";
}