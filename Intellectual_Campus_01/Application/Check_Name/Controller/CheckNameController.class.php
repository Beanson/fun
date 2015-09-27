<?php
namespace Check_Name\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class CheckNameController extends Controller {

    /*把带参数的二维码展示到页面中*/
    public function qrCodeShow(){
        /*接受传来的teacher_id和course_id参数*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');

        /*调用WeiXin模块的方法进行二维码输出*/
        $qr_code=D('WeiXin')->qrCode($course_id);
        /*调用CheckName的createForm方法来新建一张以该课程ID为名字的表*/
        D('CheckName')->createForm($course_id);

        /*在页面上进行输出*/
        $this->assign('qr_code',$qr_code);
        $this->assign('teacher_id',$teacher_id);
        $this->assign('course_id',$course_id);
        $this->display('qr_code_show');

    }

    /*获取localStorage中的相关参数并作出判断*/
    public function checkNameValidate(){

        /*接收course_id和open_id两个参数*/
        $course_id=I('course_id');
        $open_id=I('open_id');

        $student_id=M('student_information')->field('student_id')->where("open_id='$open_id'")->select();

        $this->assign('course_id',$course_id);
        $this->assign('open_id',$open_id);
        $this->assign('student_id',$student_id);
        $this->display('Student/student_checkname_return');

    }

    /*学生扫码签到没有作弊时执行以下类*/
    public function upDateCheckNameForm(){

        /*得到课程ID名字加工后即是要找的这张表*/
        $course_id=I('course_id');
        $password=I('password');
        $student_id=I('student_id');
        $check_name_table="check_name_".$course_id;

                         //$data['student_name'] =I('student_name'); 后期再处理
        $stage=D('Teacher')->teacher_analog_login($student_id,$password);
        if($stage){
            $update=M();
            $update->execute("update $check_name_table set attend_stage='attend' where student_id='$student_id' ")or die("不可以哦");
        }else{
            $stage=false;
        }
        $this->ajaxReturn($stage);

    }


    /*展示签到结果到大屏幕上*/
    public function checkResult(){

        /*获取教师工号和课程号*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');
        $check_name_table="check_name_".$course_id;

        /*获取扫码签到结果*/
        $check_name_result=M($check_name_table)->select();
        $this->assign('check_name_result',$check_name_result);

        /*将出勤结果数据进行输出到页面上*/
        $this->assign('teacher_id',$teacher_id);
        $this->assign('course_id',$course_id);
        $this->display('check_result');

    }

    /*把签到结果发到教师邮箱中*/
    public function sendMail(){

        /*获取教师的工号和结果表的表名然后发送给老师邮箱*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');

    }
    public function index(){
        echo "strange";
        $check_name_table=check_name_6;
        $student_id="20131003499";
        $update=M();
        $update->execute("update $check_name_table set attend_stage='attend' where student_id='$student_id' ")or die("不可以哦");
    }
}












