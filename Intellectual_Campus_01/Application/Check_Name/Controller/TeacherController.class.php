<?php
namespace Check_Name\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class TeacherController extends Controller{

    public function validate(){

        $teacher_id=I('teacher_id');
        $password=I('password');

        //echo $teacher_id;
        //调用model层的方法来验证
        $check=D('Teacher');
        $Login_Result=$check->teacher_analog_login($teacher_id,$password);
        if($Login_Result){
            $this->redirect('courseDisplay',array('teacher_id' => $teacher_id));
        }
        else{
            echo "<script>alert(\"对不起，您输入的账号密码有误，请重新输入，谢谢！\")</script>";
            $this->redirect('courseDisplay',array('teacher_id' => $teacher_id));
        }
    }

    public function courseDisplay(){

        $teacher_id=I('teacher_id');
        $model=M();
        $teacher_name=$model->query("select teacher_name from teacher_information where teacher_id='$teacher_id' ");
        $temp=$teacher_name[0][teacher_name];
        $course=$model->query("select course_id ,course_name ,course_time from course_form where course_teacher='$temp'");
        $this->assign('teacher_id',$teacher_id);
        $this->assign('course',$course);
        $this->display('course_identification');
    }

    function teacherLogin(){
        $this->display('teacher_login');
    }

}