<?php
/*
    ����������
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

//��֤��Ϣ
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

//���ǩ��
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);//implode �ַ�����Ϻ���  ��echo implode(" ",$arr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }
//��Ӧ��Ϣ
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

//�����¼���Ϣ
    private function receiveEvent($object)
    {
        $openid = $object->FromUserName;

        $content = "";
        switch ($object->Event) {
            case "subscribe"://�¼������ǹ�ע���ں�

                $content = "��ӭʹ���ǻ�У԰ϵͳ ";


                if (!empty($object->EventKey)) {
                    //����û�openid
                    $eventkey = (int)substr("$object->EventKey", 8);//��ȥeventkeyǰ��λ������ά�������"qrscene_id"��"id";
                    //�ٶ�����Ϊ999��Ϊ������ά�롣

                    if ($eventkey != 8888888)   //�����עʱɨ����˽�˶�ά�롣//
                    {
                        $row = $this->bindOpenId($openid, $eventkey);//���û���openid,���һ�ȡ�α������ֺ�;
                        if (($row != "������ʾһ") && ($row != "������ʾ��")) {

                            require ("./Scanner_require_files/Scanner_link_exchange.php");

                            // $content = "�װ���" . $row["name"] . $row["gender"] . ",��ӭ����עCCL����ϵͳ��";
                        } else if ($row == "������ʾһ") $content = "��˽�˶�ά���Ѱ������α�������ɨ�����ڱ��˵�˽�˶�ά�롣";
                        else $content = "��Ǹ�����Ѱ󶨸�����Ϣ������ɨ�������α��Ķ�ά��";
                    } else //�����עʱɨ���ǹ�����ά�루�α���������ɨ˽�˶�ά�����ݣ�������������ָ֤����
                    {

                    }

                }
                break;
            case "unsubscribe":
                $content = "ȡ����ע";
                break;

            case "scancode_push":
            {
                $eventkey = (int)substr("$object->EventKey", 8);//��ȥeventkeyǰ��λ������ά�������"qrscene_id"��"id";
                echo "<script>location.href='http://intellectual-campus.cn-hangzhou.aliapp.com/intellectualCampus/index.php/Check_Name/CheckName/getLocalStorage?open_id=$openid&course_id=$eventkey'</script>";
                echo "<script>alert($eventkey)</script>";
            }
                break;

            case "SCAN"://�ڷ����������õ�΢����ɨ�빤��ɨ��
            {
                $eventkey = (int)substr("$object->EventKey", 8);//��ȥeventkeyǰ��λ������ά�������"qrscene_id"��"id";
                echo "<script>location.href='http://intellectual-campus.cn-hangzhou.aliapp.com/intellectualCampus/index.php/Check_Name/CheckName/getLocalStorage?open_id=$openid&course_id=$eventkey'</script>";
                echo "<script>alert($eventkey)</script>";
            }
                break;


            case "CLICK": //�¼������ǵ���˵�,�ж�eventkeyֵ
                switch ($object->EventKey) {

                    case "meeting_general"; {
                        $openid = $object->FromUserName;
                        $content[] = array("Title" => "����ѶϢ",
                            "Description" => "",
                            "PicUrl" => "http://p0.55tuanimg.com/static/goods/ckeditor/2012/08/30/20/ckeditor_1346331387_1979_wm.jpg",
                            "Url" => "http://create.maka.im/k/XL87FFDD");
                    }
                        break;

                    case "information_detail"; {
                        $openid = $object->FromUserName;
                        $content[] = array("Title" => "��Ϣ��ѯ",
                            "Description" => "",
                            "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                            "Url" => "http://1.2920248385.sinaapp.com/general_codes/display_detail_weixin.php?openid=$openid");
                    }
                        break;


                    case "weixin_wall"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "΢��ǽ",
                            "Description" => "",
                            "PicUrl" => "http://img5.douban.com/view/note/large/public/p24195968.jpg",
                            "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx66488a2bc2152d8d&redirect_uri=http://1.2920248385.sinaapp.com/general_codes/weixinqiang/huiyixuanze.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
                        //����Ҫ��appid
                    }
                        break;


                    case "meeting_detail"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "��������",
                            "Description" => "",
                            "PicUrl" => "http://csi.gdufs.edu.cn/ccl-nlpnabd2015/images/slide2.jpg",
                            "Url" => "http://csi.gdufs.edu.cn/ccl-nlpnabd2015/index.html");
                    }
                        break;

                    case "map"; {
                        $openid = $object->FromUserName;

                        $content[] = array("Title" => "�鿴��ͼ",
                            "Description" => "",
                            "PicUrl" => "http://1.2920248385.sinaapp.com/img/map.png",
                            "Url" => "http://map.baidu.com/");
                    }
                        break;

                    case "weather"; {
                        $content = "���ã������������ڵĳ��С����磺����";
                    }
                        break;

                    case "contact_us"; {
                        $content = "��ӭ��עCCL���飬��ظ����ź͹�����Ա��ϵ��ʽ���£�\n �����飺123 \n �����飺123 \n ־Ը�飺123  \n �����飺123 \n ��ͨ�飺123 \n �����飺123";
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
//**********************************************��openid����****************************************************************
//�״ι�ע���������ݿ⣬��ȡopenid;
    private function bindOpenId($openid,$eventkey)
    {
        include 'link_to_database.php';

        $sql = "select * from personal where open_id ='$openid'";
        $data = mysql_num_rows(mysql_query($sql));//�鿴���ݿ��Ƿ��Ѿ�����������open_id��
        if(!$data)//�����openid�������ݿ���
        {
            $sql2 = "select open_id,name,gender,position_c,position_w,identity from personal where personal_id = '$eventkey'";//�ҵ���id��Ӧ��openid�С�
            $data2 = mysql_fetch_array(mysql_query($sql2));

            if(!$data2["open_id"])//������û���openidΪ�ա�
            {
                $add_openid = "update personal SET open_id = '$openid' WHERE personal_id ='$eventkey'";
                mysql_query($add_openid);//�Ѹ�openidд�����ݿ⡣
                $row = $data2;
            }

            else//�����id�µ�openid��Ϊ��
                $row = "������ʾһ";//��id��Ӧ��openid�ѱ��󶨡�
        }

        else //�����openid�Ѿ����������ݿ���
        {
            $sql2 = "select open_id,name,gender,position_c,position_w,identity from personal where personal_id = '$eventkey'";//�ҵ���id��Ӧ��openid�С�
            $data2 = mysql_fetch_array(mysql_query($sql2));
            if($data2["open_id"] == $openid)
            {
                $add_openid = "update personal SET open_id = '$openid' WHERE personal_id ='$eventkey'";
                mysql_query($add_openid);//�Ѹ�openid���¸���д�����ݿ⡣
                $row = $data2;
            }
            else
            {
                $row = "������ʾ��";
            }//��΢�źŵ�openid�Ѿ��������α���Ϣ��

        }

        return $row;

    }







//**********************************************����ǩ������****************************************************************
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
        //�����Ա���������������ߡ�Ůʿ��
        if ($gender == "men") $gender = "����";
        else if ($gender == "women") $gender = "Ůʿ";


        if ($data)  //���data��Ϊ0��ʾ�ҵ���openid
        {

            if ($pay_or_not == 1) {
                if ($sign_up == 0) //0��ʾδǩ��
                {
                    //$access_token = "";
                    include 'access_token.php';
                    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
                    $result = $this->https_request1($url);
                    $jsoninfo = json_decode($result, true);
                    $headimgurl = $jsoninfo["headimgurl"];
                    $nickname = $jsoninfo["nickname"];
                    mysql_query("insert into sign_up_wall(openid,nickname,headimgurl) values ('$openid','$nickname','$headimgurl')");
                    $sql = "update personal set sign_up = 1 where open_id = '$openid'"; //��is_signup��Ϊ1��ʾ��ǩ��
                    mysql_query($sql);
                    mysql_query("update display_overall set sign_up_or_not=1 where personal_id = '$personal_id' ");

                    require ("./Scanner_require_files/Scanner_sign_meeting_1.php");

                    // $str = "�𾴵�" . $name . $gender . "����ϲ��ǩ���ɹ��������Ʋ���" . ($rate + 1) . "�Ż�" . ($rate + 2) . "�Ź�̨��ȡ������ϣ��ٴθ�л����2015��CCL�����֧�֣�";
                } else
                    require ("./Scanner_require_files/Scanner_sign_meeting_2.php");
                //$str = "�𾴵�" . $name . $gender . "�����Ѿ�ǩ�����ˡ�������δ��ȡ������ϣ������Ʋ���" . ($rate + 1) . "�Ż�" . ($rate + 2) . "�Ź�̨��ȡ,лл���ĺ�����";


            } else {
                require ("./Scanner_require_files/Scanner_sign_meeting_3.php");
                //$str = "�װ���" . $name . $gender . ",�ܱ�Ǹ������δ����CCL������ã������Ʋ����ɷѹ�̨��ɽɷ�������лл���ĺ������������������㣬��б�Ǹ��";
            }
        }
        else //���û���ҵ�openid��
        {
            $href = "http://1.2920248385.sinaapp.com/general_codes/id_check.php?openid=$openid";
            $str = "��ӭ��ע2015 CCL����ϵͳ��\n��Ŀǰ�ĵ�½״̬Ϊ�ο͡������������α�������ɨ��α�˽�˶�ά�룬���ߵ���������֤�����������֤\n" . "<a href = '$href'
                    >�����֤</a>";
        }
        return $str;


    }


//**********************************************�λ���ǩ������ ***************************************************************

    private function signUp_2($openid,$meeting_name)
    {
        include 'link_to_database.php';


        $judge1=mysql_query("select * from personal where open_id='$openid' ");
        $data =mysql_num_rows($judge1);


        if($data)  //���data��Ϊ0��ʾ�ҵ���openid
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
            /*���֮ǰע��ʱ��û��ѡ��û��飬����ʱɨ���˸û���Ķ�ά����ִ�и����*/
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


            //�����Ա���������������ߡ�Ůʿ��
            if( $gender == "men")  $gender = "����";
            else if( $gender == "women")  $gender = "Ůʿ";


            if( $sign_up_meeting==0) //0��ʾδǩ��
            {
                $sql = "update attend set sign_up_meeting= 1 where personal_id='$personal_id' and meeting_name='$meeting_name' "; //��is_signup��Ϊ1��ʾ��ǩ��

                mysql_query ( $sql );

                require('Exchange_meeting.php');

                //$str="�װ���". $name.$gender.",��ϲ��".$meeting_name."ǩ���ɹ���";
                require ("./Scanner_require_files/Scanner_every_meetings_1.php");
            }
            else{
                require('Exchange_meeting.php');
                require ("./Scanner_require_files/Scanner_every_meetings_2.php");
                //$str = "�װ���".$name.$gender."��".$meeting_name."���Ѿ�ǩ�����ˡ�";
            }
        }


        else //���û���ҵ�openid
        {
            $href = "http://1.2920248385.sinaapp.com/general_codes/id_check.php?openid=$openid";
            $str = "��ӭ��ע2015 CCL����ϵͳ��\n��Ŀǰ�ĵ�½״̬Ϊ�ο͡������������α�������ɨ��α�˽�˶�ά�룬���ߵ���������֤�����������֤\n" . "<a href = '$href'
                    >�����֤</a>";
        }
        return $str;

    }

//**********************************************������Ϣ ***************************************************************

//�����ı���Ϣ
    private function receiveText($object)
    { /*
        $rr=$object->Content;
        $msgType = "text";
        if(mb_substr($rr,0,2,'utf-8')=="����"){
           
            $cityname = mb_substr($rr,2,5,'utf-8');
            $url = "http://v.juhe.cn/weather/index?format=2&cityname=".$cityname."&key=173938698b23760a3b327d28c77635e9";
            $str=file_get_contents($url);
            $de_json=json_decode($str, true);
            $content = "���У�".$de_json['result']['today']['city']."\n���ڣ�".$de_json['result']['today']['date_y'].$de_json['result']['today']['week']."\n��ǰ�¶ȣ�".$de_json['result']['sk']['temp']."��\n��ǰ������".$de_json['result']['sk']['wind_strength'].$de_json['result']['sk']['wind_direction']."\n����������".$de_json['result']['today']['temperature']."\n�����¶ȣ�".$de_json['result']['today']['weather']."";
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
                   $content = "���ã�������˵�ѡ�лл��";
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

//����ͼƬ��Ϣ
    private function receiveImage($object)
    {

        //$content = array("MediaId"=>$object->MediaId);
        //$result = $this->transmitImage($object, $content);
        $content = "����ͼƬ���յ�!";
        $result = $this->transmitText($object,$content) ;
        return $result;
    }

//����λ����Ϣ
    private function receiveLocation($object)
    {
        //$content = "�㷢�͵���λ�ã�γ��Ϊ��".$object->Location_X."������Ϊ��".$object->Location_Y."�����ż���Ϊ��".$object->Scale."��λ��Ϊ��".$object->Label;
        //$result = $this->transmitText($object, $content);
        $content = "����λ�����յ�!";
        $result = $this->transmitText($object,$content);
        return $result;
    }

//����������Ϣ
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "��ղ�˵���ǣ�".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

//������Ƶ��Ϣ
    private function receiveVideo($object)
    {
        // $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        //$result = $this->transmitVideo($object, $content);
        $content = "������Ƶ���յ�!";
        $result = $this->transmitText($object,$content);
        return $result;
    }


//**********************************************�ظ���Ϣ ***************************************************************




//�ظ��ı���Ϣ
    private function transmitText($object, $content)
    {
        //���ɶ�ά��
        $str = "�ѳɹ����ɶ�ά��";
        $str2 = "�ѳɹ������˵�";

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

//�����Զ���˵�����;
    /* private function jsonMenu()
     {

         include 'jsonmenu.php';//ʹ���Զ���˵���
         $result = $this->https_request($url, $jsonmenu);
         var_dump($result);


     }*/


//���ɶ�ά�뺯��
    private function qrCode()
    {
        //$access_token="";
        include 'access_token.php';//��ȡaccess_token��

        include 'SaeDataBase.php';//�������ݿ��ļ�;


        $sql="SELECT volunteer_id FROM volunteer where qrCode = ' ' ";//��ȡ���˵�id
        //ql = " select * from sae_login";
        $result = mysql_query ( $sql );


        while($row = mysql_fetch_array($result))
        {
            $id = (int)$row["volunteer_id"]; //������id�󶨵���ά����
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




//�ظ�ͼ����Ϣ
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
//���´������Ϊ����


//�ظ�ͼƬ��Ϣ
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

//�ظ�������Ϣ
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

//�ظ���Ƶ��Ϣ
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



//�ظ�������Ϣ
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

//��־��¼
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
