<?php
namespace Check_Name\Model;
use Think\Model;

class IndexModel {
    function studentInformationCheck(){
        echo "
            <script type=\"text/javascript\">

                /*�鿴�Ƿ���localStorage�д���ѧ�ţ��������������Ѿ�ע����ˣ�����ת��ҳ�治һ�� */
                var student_id=localStorage.getItem('student_id');

            /*��ǰʱ���������ʱ��֮ǰǩ��ʱ������һ���ʱ�䷶Χ�ڶ�����ͬһ�ڿκͲ�ͬ��open_id�Ļ��ͱ�ʾǩ��ʧ��*/
            if(course_id==course_id_former){
                if(current_time<sign_time+86400000){
                    if(open_id!=open_id_former){
                        window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameFailed\";  //ǩ��ʧ�ܣ�������ʽ��
                    }else{
                        window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameRepeat\";  //�ظ�ǩ����
                    }
                }

            }
            window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameSuccess\";  //ǩ���ɹ���

            </script>
        ";
    }
}