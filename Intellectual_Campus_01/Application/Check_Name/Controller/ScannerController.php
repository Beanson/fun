<?php
/*
    方倍工作室
    http://www.cnblogs.com/txw1958/
    CopyRight 2014 All Rights Reserved
*/

define("TOKEN", "weiphp");
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{

//验证消息
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

//检查签名
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);//implode 字符串组合函数  如echo implode(" ",$arr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }
//响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;

                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

//接收事件消息
    private function receiveEvent($object)
    {
        $openid = $object->FromUserName;

        $content = "";
        switch ($object->Event) {
            case "subscribe"://事件类型是关注公众号

                $content = "欢迎使用智慧校园系统 ";


                if (!empty($object->EventKey)) {
                    //获得用户openid
                    $eventkey = (int)substr("$object->EventKey", 8);//除去eventkey前八位，即二维码参数中"qrscene_id"的"id";
                    //假定参数为999的为公共二维码。

                    if ($eventkey != 8888888)   //如果关注时扫的是私人二维码。//
                    {
                        $row = $this->bindOpenId($openid, $eventkey);//绑定用户的openid,并且获取嘉宾的名字和;
                        if (($row != "错误提示一") && ($row != "错误提示二")) {

                            require ("./Scanner_require_files/Scanner_link_exchange.php");

                            // $content = "亲爱的" . $row["name"] . $row["gender"] . ",欢迎您关注CCL会议系统。";
                        } else if ($row == "错误提示一") $content = "该私人二维码已绑定其他嘉宾，请您扫描属于本人的私人二维码。";
                        else $content = "抱歉，您已绑定个人信息，请勿扫描其他嘉宾的二维码";
                    } else //如果关注时扫的是公共二维码（嘉宾可能忘记扫私人二维码绑定身份），则进行身份认证指引。
                    {

                    }

                }
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;

            case "scancode_push":
            {
                $eventkey = (int)substr("$object->EventKey", 8);//除去eventkey前八位，即二维码参数中"qrscene_id"的"id";
                echo "<script>location.href='http://intellectual-campus.cn-hangzhou.aliapp.com/intellectualCampus/index.php/Check_Name/CheckName/getLocalStorage?open_id=$openid&course_id=$eventkey'</script>";
                echo "<script>alert($eventkey)</script>";
            }
                break;

            case "SCAN"://在服务号外面调用的微信总扫码工具扫码
            {
                $eventkey = (int)substr("$object->EventKey", 8);//除去eventkey前八位，即二维码参数中"qrscene_id"的"id";
                echo "<script>location.href='http://intellectual-campus.cn-hangzhou.aliapp.com/intellectualCampus/index.php/Check_Name/CheckName/getLocalStorage?open_id=$openid&course_id=$eventkey'</script>";
                echo "<script>alert($eventkey)</script>";
            }
                break;


            case "CLICK": //事件类型是点击菜单,判断eventkey值
                switch ($object->EventKey) {

                    case "meeting_general"; {
                        $openid = $object->FromUserName;
                        $content[] = array("Title" => "会议讯息",
                            "Description" => "",
                            "PicUrl" => "http://p0.55tuanimg.com/static/goods/ckeditor/2012/08/30/20/ckeditor_1346331387_1979_wm.jpg",
                            "Url" => "http://create.maka.im/k/XL87FFDD");
                    }
                        break;

                    case "information_detail"; {
                        $openid = $object->FromUserName;
                        $content[] = array("Title" => "信息查询",
                            "Description" => "",
                            "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                            "Url" => "http://1.2920248385.sinaapp.com/general_codes/display_detail_weixin.php?openid=$openid");
                    }
                        break;


                    case "weixin_wall"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "微信墙",
                            "Description" => "",
                            "PicUrl" => "http://img5.douban.com/view/note/large/public/p24195968.jpg",
                            "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx66488a2bc2152d8d&redirect_uri=http://1.2920248385.sinaapp.com/general_codes/weixinqiang/huiyixuanze.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
                        //这里要改appid
                    }
                        break;


                    case "meeting_detail"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "会议详情",
                            "Description" => "",
                            "PicUrl" => "http://csi.gdufs.edu.cn/ccl-nlpnabd2015/images/slide2.jpg",
                            "Url" => "http://csi.gdufs.edu.cn/ccl-nlpnabd2015/index.html");
                    }
                        break;

                    case "map"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "查看地图",
                            "Description" => "",
                            "PicUrl" => "http://1.2920248385.sinaapp.com/img/map.png",
                            "Url" => "http://map.baidu.com/");
                    }
                        break;

                    case "weather"; {
                        $content = "您好，请输入您所在的城市。例如：广州";
                    }
                        break;

                    case "contact_us"; {
                        $content = "欢迎关注CCL会议，相关负责部门和工作人员联系方式如下：\n 宣传组：123 \n 会务组：123 \n 志愿组：123  \n 秘书组：123 \n 交通组：123 \n 后勤组：123";
                    }
                        break;

                    default:
                        $content = "receive a new event: " . $object->Event;
                        break;
                }


        }

        if (is_array($content)) $result = $this->transmitNews($object, $content);
        else $result = $this->transmitText($object, $content);
        return $result;
    }
//**********************************************绑定openid函数****************************************************************
//首次关注后，连接数据库，获取openid;
    private function bindOpenId($openid,$eventkey)
    {
        include 'link_to_database.php';

        $sql = "select * from personal where open_id ='$openid'";
        $data = mysql_num_rows(mysql_query($sql));//查看数据库是否已经存在这样的open_id；
        if(!$data)//如果该openid不在数据库里
        {
            $sql2 = "select open_id,name,gender,position_c,position_w,identity from personal where personal_id = '$eventkey'";//找到该id对应的openid列。
            $data2 = mysql_fetch_array(mysql_query($sql2));

            if(!$data2["open_id"])//如果该用户的openid为空。
            {
                $add_openid = "update personal SET open_id = '$openid' WHERE personal_id ='$eventkey'";
                mysql_query($add_openid);//把该openid写入数据库。
                $row = $data2;
            }

            else//如果该id下的openid不为空
                $row = "错误提示一";//该id对应的openid已被绑定。
        }

        else //如果该openid已经存在于数据库里
        {
            $sql2 = "select open_id,name,gender,position_c,position_w,identity from personal where personal_id = '$eventkey'";//找到该id对应的openid列。
            $data2 = mysql_fetch_array(mysql_query($sql2));
            if($data2["open_id"] == $openid)
            {
                $add_openid = "update personal SET open_id = '$openid' WHERE personal_id ='$eventkey'";
                mysql_query($add_openid);//把该openid重新覆盖写入数据库。
                $row = $data2;
            }
            else
            {
                $row = "错误提示二";
            }//该微信号的openid已经绑定其他嘉宾信息。

        }

        return $row;

    }







//**********************************************主会签到函数****************************************************************
    private function signUp($openid)
    {
        include 'link_to_database.php';

        $judge1 = mysql_query("select * from personal where open_id='$openid' ");

        $data = mysql_num_rows($judge1);
        for ($i = 0; $i < $data; $i++) {
            $result = (mysql_fetch_assoc($judge1));
            $name = $result['name'];
            $gender = $result['gender'];

            $identity=$result['identity'];
            $position_c=$result['position_c'];
            $position_w=$result['position_w'];

            $sign_up = $result['sign_up'];
            $personal_id = $result['personal_id'];
            $pay_or_not = $result['pay_or_not'];
            $rate = $result['rate'];
        }
        //根据性别输出“先生”或者“女士”
        if ($gender == "men") $gender = "先生";
        else if ($gender == "women") $gender = "女士";


        if ($data)  //如果data不为0表示找到该openid
        {

            if ($pay_or_not == 1) {
                if ($sign_up == 0) //0表示未签到
                {
                    //$access_token = "";
                    include 'access_token.php';
                    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
                    $result = $this->https_request1($url);
                    $jsoninfo = json_decode($result, true);
                    $headimgurl = $jsoninfo["headimgurl"];
                    $nickname = $jsoninfo["nickname"];
                    mysql_query("insert into sign_up_wall(openid,nickname,headimgurl) values ('$openid','$nickname','$headimgurl')");
                    $sql = "update personal set sign_up = 1 where open_id = '$openid'"; //将is_signup设为1表示已签到
                    mysql_query($sql);
                    mysql_query("update display_overall set sign_up_or_not=1 where personal_id = '$personal_id' ");

                    require ("./Scanner_require_files/Scanner_sign_meeting_1.php");

                    // $str = "尊敬的" . $name . $gender . "，恭喜您签到成功！请您移步至" . ($rate + 1) . "号或" . ($rate + 2) . "号柜台领取相关资料，再次感谢您对2015年CCL会议的支持！";
                } else
                    require ("./Scanner_require_files/Scanner_sign_meeting_2.php");
                //$str = "尊敬的" . $name . $gender . "，您已经签过到了。若您还未领取相关资料，请您移步至" . ($rate + 1) . "号或" . ($rate + 2) . "号柜台领取,谢谢您的合作！";


            } else {
                require ("./Scanner_require_files/Scanner_sign_meeting_3.php");
                //$str = "亲爱的" . $name . $gender . ",很抱歉，您尚未缴纳CCL会议费用，请您移步至缴费柜台完成缴费手续，谢谢您的合作。若给您带来不便，深感抱歉！";
            }
        }
        else //如果没有找到openid。
        {
            $href = "http://1.2920248385.sinaapp.com/general_codes/id_check.php?openid=$openid";
            $str = "欢迎关注2015 CCL会议系统！\n您目前的登陆状态为游客。若您是受邀嘉宾，请您扫描嘉宾私人二维码，或者点击“身份验证”进行身份验证\n" . "<a href = '$href'
                    >身份验证</a>";
        }
        return $str;


    }


//**********************************************次会议签到函数 ***************************************************************

    private function signUp_2($openid,$meeting_name)
    {
        include 'link_to_database.php';


        $judge1=mysql_query("select * from personal where open_id='$openid' ");
        $data =mysql_num_rows($judge1);


        if($data)  //如果data不为0表示找到该openid
        {
            for($i=0;$i<$data;$i++){
                $result=(mysql_fetch_assoc($judge1));
                $personal_id=$result['personal_id'];
                $personal_id=(int)$personal_id;
                $name=$result['name'];
                $gender=$result['gender'];

                $identity=$result['identity'];
                $position_c=$result['position_c'];
                $position_w=$result['position_w'];
                $pay_or_not=$result['pay_or_not'];
            }

            $judge1=mysql_query("select * from attend where personal_id='$personal_id'and meeting_name='$meeting_name' ");
            $data2 =mysql_num_rows($judge1);
            /*如果之前注册时候没有选择该会议，而这时扫描了该会议的二维码则执行该语句*/
            if($data2==0){

                if($meeting_name=="A_meeting") $meeting_id=1;
                if($meeting_name=="B_meeting") $meeting_id=2;
                if($meeting_name=="C_meeting") $meeting_id=3;

                mysql_query("insert into attend(meeting_id,meeting_name,personal_id,name,sign_up_meeting) values('$meeting_id','$meeting_name','$personal_id','$name',0) ");
                $sign_up_meeting=0;
            }else{

                for($i=0;$i<$data2;$i++){
                    $result=(mysql_fetch_assoc($judge1));
                    $sign_up_meeting=$result['sign_up_meeting'];
                }
            }


            //根据性别输出“先生”或者“女士”
            if( $gender == "men")  $gender = "先生";
            else if( $gender == "women")  $gender = "女士";


            if( $sign_up_meeting==0) //0表示未签到
            {
                $sql = "update attend set sign_up_meeting= 1 where personal_id='$personal_id' and meeting_name='$meeting_name' "; //将is_signup设为1表示已签到

                mysql_query ( $sql );

                require('Exchange_meeting.php');

                //$str="亲爱的". $name.$gender.",恭喜您".$meeting_name."签到成功！";
                require ("./Scanner_require_files/Scanner_every_meetings_1.php");
            }
            else{
                require('Exchange_meeting.php');
                require ("./Scanner_require_files/Scanner_every_meetings_2.php");
                //$str = "亲爱的".$name.$gender."，".$meeting_name."您已经签过到了。";
            }
        }


        else //如果没有找到openid
        {
            $href = "http://1.2920248385.sinaapp.com/general_codes/id_check.php?openid=$openid";
            $str = "欢迎关注2015 CCL会议系统！\n您目前的登陆状态为游客。若您是受邀嘉宾，请您扫描嘉宾私人二维码，或者点击“身份验证”进行身份验证\n" . "<a href = '$href'
                    >身份验证</a>";
        }
        return $str;

    }

//**********************************************接收消息 ***************************************************************

//接收文本消息
    private function receiveText($object)
    { /*
        $rr=$object->Content;
        $msgType = "text";
        if(mb_substr($rr,0,2,'utf-8')=="天气"){
           
            $cityname = mb_substr($rr,2,5,'utf-8');
            $url = "http://v.juhe.cn/weather/index?format=2&cityname=".$cityname."&key=173938698b23760a3b327d28c77635e9";
            $str=file_get_contents($url);
            $de_json=json_decode($str, true);
            $content = "城市：".$de_json['result']['today']['city']."\n日期：".$de_json['result']['today']['date_y'].$de_json['result']['today']['week']."\n当前温度：".$de_json['result']['sk']['temp']."度\n当前天气：".$de_json['result']['sk']['wind_strength'].$de_json['result']['sk']['wind_direction']."\n今日天气：".$de_json['result']['today']['temperature']."\n今日温度：".$de_json['result']['today']['weather']."";
            $result = $this->transmitText($object, $content);
            
            */
        $keyword = trim($object->Content);
        $url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword);
        $output = file_get_contents($url);
        $content = json_decode($output, true);

        $result = $this->transmitNews($object, $content);
        return $result;


        /*
       }

       else{
           switch ($object->Content)
           {
               default:
                   $content = "您好，请输入菜单选项，谢谢！";
                   break;
           }
           if(is_array($content)){
               if (isset($content[0]['PicUrl'])){
                   $result = $this->transmitNews($object, $content);
               }else if (isset($content['MusicUrl'])){
                   $result = $this->transmitMusic($object, $content);
               }
           }else{
               $result = $this->transmitText($object, $content);
           }

       }

       return $result;*/
    }

//接收图片消息
    private function receiveImage($object)
    {

        //$content = array("MediaId"=>$object->MediaId);
        //$result = $this->transmitImage($object, $content);
        $content = "您的图片已收到!";
        $result = $this->transmitText($object,$content) ;
        return $result;
    }

//接收位置消息
    private function receiveLocation($object)
    {
        //$content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        //$result = $this->transmitText($object, $content);
        $content = "您的位置已收到!";
        $result = $this->transmitText($object,$content);
        return $result;
    }

//接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

//接收视频消息
    private function receiveVideo($object)
    {
        // $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        //$result = $this->transmitVideo($object, $content);
        $content = "您的视频已收到!";
        $result = $this->transmitText($object,$content);
        return $result;
    }


//**********************************************回复消息 ***************************************************************




//回复文本消息
    private function transmitText($object, $content)
    {
        //生成二维码
        $str = "已成功生成二维码";
        $str2 = "已成功更换菜单";

        if($content == $str) { $this->qrCode();}
        if($content == $str2) { $this->jsonMenu();}


        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

//生成自定义菜单函数;
    /* private function jsonMenu()
     {

         include 'jsonmenu.php';//使用自定义菜单；
         $result = $this->https_request($url, $jsonmenu);
         var_dump($result);


     }*/


//生成二维码函数
    private function qrCode()
    {
        //$access_token="";
        include 'access_token.php';//获取access_token；

        include 'SaeDataBase.php';//连接数据库文件;


        $sql="SELECT volunteer_id FROM volunteer where qrCode = ' ' ";//获取个人的id
        //ql = " select * from sae_login";
        $result = mysql_query ( $sql );


        while($row = mysql_fetch_array($result))
        {
            $id = (int)$row["volunteer_id"]; //将个人id绑定到二维码里
            $qrcode = '{ "action_name": "QR_LIMIT_SCENE","action_info": {"scene": {  "scene_id": '.$id.'  }  }}' ;
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
            $result2 = $this->https_request($url,$qrcode);
            $jsoninfo = json_decode($result2, true);
            $ticket = $jsoninfo["ticket"];
            $qrCodeUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
            // $sql2="update sae_login set password = '$qrCodeUrl' where id = $row['id']";
            $sql2 = "UPDATE volunteer SET qrCode = '$qrCodeUrl' WHERE volunteer_id = $id ";
            //  $sql2 = "insert into sae_login (username,password) values ('DDddd','CCccc')";
            $result3 = mysql_query ( $sql2 ) ;
            // return $ticket;

        }


        //return  $id;

    }



    function https_request($url,$data = null ){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function https_request1($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }




//回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }


    /*
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }
*/

//**************************************************************************************************************************88
//以下代码均作为备用


//回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

//回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

//回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }



//回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

//日志记录
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
}
?>
