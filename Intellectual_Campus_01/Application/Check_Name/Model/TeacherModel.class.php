<?php
namespace Check_Name\Model;
use Think\Model;
class TeacherModel{
    function teacher_analog_login($account,$password){
//      ��װ���ݵ�����
        $field=array(
            'username'=>$account,//ѧ�š�����
            'password'=>$password,
            'login-form-type'=> 'pwd',
        );
        //���ù���ģ���GDUFS_Login
        $user=new \GDUFS_Login();
        $isLogin = $user->checkField($field, $formUrl);
        return $isLogin;

    }

    function asd($username,$password){
        $field=array(
            'username'=>$username,//ѧ��
            'password'=>$password,
            'login-form-type'=> 'pwd',
        );
        //���ù���ģ���GDUFS_Login
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
