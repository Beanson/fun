<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/9/19
 * Time: 21:25
 */
include 'GDUFS_Login.php';
//��ά����ȥ���ظ�ֵ
function array_unique_fb($array2D)
{
    foreach ($array2D as $v)
    {
        $v = join(",",$v);  //��ά,Ҳ������implode,��һά����ת��Ϊ�ö������ӵ��ַ���
        $temp[] = $v;
    }

    $temp = array_unique($temp);    //ȥ���ظ����ַ���,Ҳ�����ظ���һά����
    foreach ($temp as $k => $v)
    {
        $temp[$k] = explode(",",$v);   //�ٽ��𿪵�����������װ
    }
    return $temp;
}

function abc(){
    echo "s";
}