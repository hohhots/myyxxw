<?php
  
                /***************************************************************************
		                               common_head.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: common_head.php,v 0.1 2003/05/22  brgd $
		
		 ***************************************************************************/

//导入所需要的类定义文件
	include ('../includes/common.' . $myyEx);
	include ('../includes/phptpl.' . $myyEx);
	include ('../includes/mysql.' . $myyEx);
	include('../includes/constants.' . $myyEx);
//导入所需要的类定义文件结束
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
	
	
//如果session有问题，返回
	if(empty($myyheadobj->myysessionvars['sessionid']))
	{
		$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
	}
//如果session有问题，返回结束

	$myydisplayall = new MyyClassDisplay($myyheadobj,$myysqlobj,$myylang);
	$myytemplate   = new IntegratedTemplateExtension("../template/myy1/");


//计算所有变量的值
	$myydisplayall->myydisplaycat1();
	$myydisplayall->myydisplayposition();
	$myydisplayall->myydisplaybody();
	$myydisplayall->myydisplayfoot();
	$myydisplayall->myydisplayright();
	$myydisplayall->myygetuserinfo();
//计算所有变量的值结束
header('Content-Type: text/html; charset=' . $myydisplayall->myyhead['encode']);
?>