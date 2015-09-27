<?php
namespace Check_Name\Model;
use Think\Model;

class WeiXinModel{

    function access_token(){
        $appid = "wx66488a2bc2152d8d";
        $appsecret = "1be1dfc77aec81bb845d8a7f95715fa5";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo =json_decode($output, true);
        $access_token = $jsoninfo["access_token"];

        return $access_token;
    }


    public function qrCode($course_id)
    {
        /*生成二维码的必须用到的access_token*/
        $access_token=$this->access_token();

            $id = $course_id;
            $qrcode = '{ "action_name": "QR_LIMIT_SCENE","action_info": {"scene": {  "scene_id": '.$id.'  }  }}' ;
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
            $result2 = $this->https_request($url,$qrcode);
            $jsoninfo = json_decode($result2, true);
            $ticket = $jsoninfo["ticket"];
            $qrCodeUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);

            return $qrCodeUrl;
    }

    /*一个request函数，调用来取得二维码相关信息*/
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

}