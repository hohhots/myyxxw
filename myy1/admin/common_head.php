<?php
  
                /***************************************************************************
		                               common_head.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: common_head.php,v 0.1 2003/05/22  brgd $
		
		 ***************************************************************************/

//��������Ҫ���ඨ���ļ�
	include ('../includes/common.' . $myyEx);
	include ('../includes/phptpl.' . $myyEx);
	include ('../includes/mysql.' . $myyEx);
	include('../includes/constants.' . $myyEx);
//��������Ҫ���ඨ���ļ�����
	if(eregi("cn", $HTTP_GET_VARS['lang']))
	{
		$lang = 'chinese';
	}
	if(eregi("en", $HTTP_GET_VARS['lang']))
	{
		$lang = 'english';
	}
	if(!(eregi("en", $HTTP_GET_VARS['lang'])) && !(eregi("cn", $HTTP_GET_VARS['lang'])))
	{
		header("Location:" . $myyURL . "index.html");
	}
	include('../language/' . $lang . '/langmain.' . $myyEx);
	include('../language/' . $lang . '/langadmin.' . $myyEx);
	
	
	$myysqlobj     = new MyyDb($user,$password,$server,$dbname);
	$myyheadobj    = new MyyClassHead($myyURL,$myysqlobj,$HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_SERVER_VARS,$HTTP_ENV_VARS,$REMOTE_ADDR,$myycookie,$myylang);
	
	
//���session�����⣬����
	if(empty($myyheadobj->myysessionvars['sessionid']))
	{
		$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
	}
//���session�����⣬���ؽ���

	$myydisplayall = new MyyClassDisplay($myyheadobj,$myysqlobj,$myylang);
	$myytemplate   = new IntegratedTemplateExtension("../template/myy1/");


//�������б�����ֵ
	$myydisplayall->myydisplaycat1();
	$myydisplayall->myydisplayposition();
	$myydisplayall->myydisplaybody();
	$myydisplayall->myydisplayfoot();
	$myydisplayall->myydisplayright();
	$myydisplayall->myygetuserinfo();
//�������б�����ֵ����
header('Content-Type: text/html; charset=' . $myydisplayall->myyhead['encode']);
?>