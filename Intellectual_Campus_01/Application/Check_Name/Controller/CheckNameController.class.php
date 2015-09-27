<?php
namespace Check_Name\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class CheckNameController extends Controller {

    /*�Ѵ������Ķ�ά��չʾ��ҳ����*/
    public function qrCodeShow(){
        /*���ܴ�����teacher_id��course_id����*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');

        /*����WeiXinģ��ķ������ж�ά�����*/
        $qr_code=D('WeiXin')->qrCode($course_id);
        /*����CheckName��createForm�������½�һ���Ըÿγ�IDΪ���ֵı�*/
        D('CheckName')->createForm($course_id);

        /*��ҳ���Ͻ������*/
        $this->assign('qr_code',$qr_code);
        $this->assign('teacher_id',$teacher_id);
        $this->assign('course_id',$course_id);
        $this->display('qr_code_show');

    }

    /*��ȡlocalStorage�е���ز����������ж�*/
    public function checkNameValidate(){

        /*����course_id��open_id��������*/
        $course_id=I('course_id');
        $open_id=I('open_id');

        $student_id=M('student_information')->field('student_id')->where("open_id='$open_id'")->select();

        $this->assign('course_id',$course_id);
        $this->assign('open_id',$open_id);
        $this->assign('student_id',$student_id);
        $this->display('Student/student_checkname_return');

    }

    /*ѧ��ɨ��ǩ��û������ʱִ��������*/
    public function upDateCheckNameForm(){

        /*�õ��γ�ID���ּӹ�����Ҫ�ҵ����ű�*/
        $course_id=I('course_id');
        $password=I('password');
        $student_id=I('student_id');
        $check_name_table="check_name_".$course_id;

                         //$data['student_name'] =I('student_name'); �����ٴ���
        $stage=D('Teacher')->teacher_analog_login($student_id,$password);
        if($stage){
            $update=M();
            $update->execute("update $check_name_table set attend_stage='attend' where student_id='$student_id' ")or die("������Ŷ");
        }else{
            $stage=false;
        }
        $this->ajaxReturn($stage);

    }


    /*չʾǩ�����������Ļ��*/
    public function checkResult(){

        /*��ȡ��ʦ���źͿγ̺�*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');
        $check_name_table="check_name_".$course_id;

        /*��ȡɨ��ǩ�����*/
        $check_name_result=M($check_name_table)->select();
        $this->assign('check_name_result',$check_name_result);

        /*�����ڽ�����ݽ��������ҳ����*/
        $this->assign('teacher_id',$teacher_id);
        $this->assign('course_id',$course_id);
        $this->display('check_result');

    }

    /*��ǩ�����������ʦ������*/
    public function sendMail(){

        /*��ȡ��ʦ�Ĺ��źͽ����ı���Ȼ���͸���ʦ����*/
        $teacher_id=I('teacher_id');
        $course_id=I('course_id');

    }
    public function index(){
        echo "strange";
        $check_name_table=check_name_6;
        $student_id="20131003499";
        $update=M();
        $update->execute("update $check_name_table set attend_stage='attend' where student_id='$student_id' ")or die("������Ŷ");
    }
}












