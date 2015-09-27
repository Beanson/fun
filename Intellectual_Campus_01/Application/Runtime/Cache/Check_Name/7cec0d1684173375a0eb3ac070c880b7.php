<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="/Intellectual_Campus_01/Application/../Thinkphp_3.2.3_Full/Public/Js/bootstrap.min.js"></script>
    <link href="/Intellectual_Campus_01/Application/../Thinkphp_3.2.3_Full/Public/Css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<table class="table table-bordered">
<?php if(is_array($course)): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><form action="<?php echo U('CheckName/qrCodeShow');?>" method="post">

            <tr>
                <td class="active"><?php echo ($vo["course_name"]); ?></td>
                <td class="success"><?php echo ($vo["course_time"]); ?></td>
                <input type="hidden" value="<?php echo ($vo["course_id"]); ?>" name="course_id">
                <input type="hidden" value="<?php echo ($teacher_id); ?>" name="teacher_id">
                <td class="warning"><input type="submit" value="确定"></td>
            </tr>

    </form><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</body>
</html>