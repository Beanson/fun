<?php
namespace Check_Name\Controller;
use Think\Controller;
class IndexController extends Controller {

    /*public function  studentInformationCheck($url, $openid){
        $this->assign('openid', $openid);
        $this->display($url);
    }*/
    public function index(){
        $this->display('Student/student_register');
    }
    public function test(){
        $student_id=I('student_id');
        $this->ajaxReturn($student_id);
    }
}