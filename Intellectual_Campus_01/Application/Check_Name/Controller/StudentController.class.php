<?php
namespace Check_Name\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class StudentController extends Controller {

        public function studentRegister(){
            $open_id=I('open_id');
            $this->assign('open_id',$open_id);
            $this->display('student_register');
    }
        public function test(){

            $open_id=I('open_id');
            $student_id=I('student_id');
            //echo $open_id;
            //echo $student_id;
            $model=M();
            $model->execute("update student_information set open_id='$open_id' where student_id='$student_id'");

            $this->ajaxReturn(true);
        }

    /*ѧ��ɨ��ǩ��û������ʱִ��������*/
    public function upDateCheckNameForm(){

        /*�õ��γ�ID���ּӹ�����Ҫ�ҵ����ű�*/
        $course_id=I('course_id');
        $check_name_table="check_name_".$course_id;

        $student_id=I('student_id');
        //$data['student_name'] =I('student_name'); �����ٴ���

        $update=new Model();
        $update->execute("update $check_name_table set attend_stage='attend' where student_id='$student_id' ");
        //$update->execute("update $check_name_table set attend_stage='attend' where student_id=$student_id ");
        $this->ajaxReturn(1);

    }

}