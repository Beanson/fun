<?php
namespace Check_Name\Model;
use Think\Model;
class TeacherModel{
    function teacher_analog_login($account,$password){
//      封装数据到数组
        $field=array(
            'username'=>$account,//学号、工号
            'password'=>$password,
            'login-form-type'=> 'pwd',
        );
        //调用公共模块的GDUFS_Login
        $user=new \GDUFS_Login();
        $isLogin = $user->checkField($field, $formUrl);
        return $isLogin;

    }

    function asd($username,$password){
        $field=array(
            'username'=>$username,//学号
            'password'=>$password,
            'login-form-type'=> 'pwd',
        );
        //调用公共模块的GDUFS_Login
        header("Content-type:text/html; charset=utf-8");
        $user=new \GDUFS_Login();
        $isLogin = $user->checkField($field, $formUrl);
        $temp = $user->getCurriculum();
        return $temp;
    }

    function course_identification(){
        teacher_analog_login();

    }



}
