<?php
namespace Check_Name\Model;
use Think\Model;
header("Content-type:text/html;charset=utf-8");

class CheckNameModel{

    function find_teacher_information(){

    }

    function send_mail_to_teacher(){


    }
    function upDateCheckNameForm($student_id,$course_id,$open_id,$student_name){

    }
    public function createForm($course_id){
        $create_table=new Model();
        /*新建表*/
            $check_name_table="check_name_".$course_id;
            $str="create table $check_name_table(
                student_id VARCHAR (32) NOT NULL ,
                student_name VARCHAR (32) ,
                attend_stage VARCHAR (32) ,
                primary key(student_id)
                )ENGINE=InnoDB DEFAULT CHARSET=utf8 ";
            $create_table->execute($str);
        /*把之前原始数据插入表中*/
        $select_course=$course_id."_course";
        $student_string=$create_table->query("select $select_course from course_student ");
        $student_string=$student_string[0][$select_course];
        $student_id_sum = explode(',',$student_string);


        /*先把部分学生信息插入表中*/
        for($index=0;$index<count($student_id_sum);$index++){
            $student_id=$student_id_sum[$index];
            $student_name=$create_table->query("select student_name from student_information where student_id='$student_id' ");
            $student_name=$student_name[0]['student_name'];
            $create_table->execute("insert into $check_name_table VALUES('$student_id','$student_name','absent')");
        }


    }
}