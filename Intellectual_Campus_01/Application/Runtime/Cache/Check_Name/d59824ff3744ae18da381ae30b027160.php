<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<div id="student_information">
    <form>
                 <input type="hidden" value="<?php echo ($open_id); ?>" id="open_id" name="open_id">
        1学生学号：<input type="text" id="student_id" name="student_id"><br>
        1学生密码：<input type="password" >
                 <input type="button" id="submit" value="确认" >
    </form>


</div>
<script src="/Intellectual_Campus_01/Application/Public/Js/jquery-1.11.3.min.js"></script>
<script src="/Intellectual_Campus_01/Application/Public/Js/jquery.cookie.js"></script>
<script type="text/javascript">

    $(document).ready(function(){

        var student_id=$.cookie('student_id');
        if(student_id==""||student_id==null){

        }else{
            $('#student_information').text("学号为："+student_id);
        }

        $("#submit").click(function(){

            student_id=$('#student_id').val();
            var open_id=$('#open_id').val();

            //alert(student_id);
            $.cookie('student_id',student_id,{expire:365});
            $.cookie('open_id',open_id,{expire:365});

            $.post("<?php echo U('test');?>",
                    {
                        "student_id":student_id,
                        "open_id":open_id,
                    },
                    function(student_id){
                        //alert(student_id);
                        $('#student_information').text("学号为："+student_id);
                    });
                    //.success(function() { alert("second success"); })
                   // .error(function() { alert("error"); })
                   // .complete(function() { alert("complete"); });

        });

    });

    /*获取学生ID， 判断该学生是否已签到*/
 /*   $(document).ready(function() {
        var student_id = localStorage.getItem('student_id');
        if (student_id == ""||student_id==null) {
            $("#submit").click(function(){
                var student_id=$("#student_id").val();
                var open_id=$("#open_id").val();
                localStorage.setItem('student_id',student_id);
                alert("您的学生ID为"+student_id)
                $.post("<?php echo U('test');?>",
                        {
                            "student_id":student_id,
                            "open_id":open_id
                        },
                        function(student_id){
                            alert(student_id);
                            $("#student_information").html(student_inforamtion.html);
                            localStorage.setItem('student_id_id3',student_id);
                        })
                       .success(function() { alert("second success"); })
                        .error(function() { alert("error"); })
                        .complete(function() { alert("complete"); });

            });
        } else {
             $("#student_information").html(student_inforamtion.html);
            alert("您的学生ID为"+student_id)
        }

    });
  */
</script>
</body>
</html>