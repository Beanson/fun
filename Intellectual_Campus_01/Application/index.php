<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/9/25
 * Time: 10:52
 */
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// ��������ģʽ ���鿪���׶ο��� ����׶�ע�ͻ�����Ϊfalse
define('APP_DEBUG',True);

//define('BIND_MODULE','Check_Name');

// ����ThinkPHP����ļ�
require '../thinkphp_3.2.3_full/ThinkPHP/ThinkPHP.php';

// ��^_^ ���治��Ҫ�κδ����� ������˼�