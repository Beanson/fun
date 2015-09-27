<?php
namespace Check_Name\Model;
use Think\Model;

class IndexModel {
    function studentInformationCheck(){
        echo "
            <script type=\"text/javascript\">

                /*查看是否在localStorage中存入学号，如果存入则表明已经注册过了，则跳转的页面不一样 */
                var student_id=localStorage.getItem('student_id');

            /*当前时间如果少于时隔之前签到时间往后一天的时间范围内而且是同一节课和不同的open_id的话就表示签到失败*/
            if(course_id==course_id_former){
                if(current_time<sign_time+86400000){
                    if(open_id!=open_id_former){
                        window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameFailed\";  //签到失败，作弊形式。
                    }else{
                        window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameRepeat\";  //重复签到。
                    }
                }

            }
            window.location.href=\"http://localhost:8089/intellectual_campus/index.php/CheckName/checkNameSuccess\";  //签到成功。

            </script>
        ";
    }
}