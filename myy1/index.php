<?php
  
                /***************************************************************************
		                                index.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: index.php,v 0.1 2003/04/10  brgd $
		
		 ***************************************************************************/
//所有对其它页面的访问必须首先通过index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//导入php文件后缀变量
	include ('includes/extension.inc');
//导入php文件后缀变量结束
  
//导入所需要的类定义文件
	include ('includes/common.' . $myyEx);
	include ('includes/phptpl.' . $myyEx);
	include ('includes/mysql.' . $myyEx);
	include('includes/constants.' . $myyEx);
//导入所需要的类定义文件结束

//定义对象并输入语言定义
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
	
	include('language/' . $lang . '/langmain.' . $myyEx);
	
	$myysqlobj     = new MyyDb($user,$password,$server,$dbname);
	$myyheadobj    = new MyyClassHead($myyURL,$myysqlobj,$HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_SERVER_VARS,$HTTP_ENV_VARS,$REMOTE_ADDR,$myycookie,$myylang);
	
	
	if(($myyheadobj->myysessionvars['sessionid']) != '')
	{
		include('language/' . $lang . '/langadmin.' . $myyEx);
	}
	
			
	$myydisplayall = new MyyClassDisplay($myyheadobj,$myysqlobj,$myylang);
	$myytemplate   = new IntegratedTemplateExtension("template/myy1/");
//定义对象并输入语言定义结束

//计算所有变量的值
	$tempmode = $myyheadobj->HTTP_GET_VARS['mode'];
	$myydisplayall->myydisplaycat1();
	$myydisplayall->myydisplayposition();
	$myydisplayall->myydisplaybody();
	$myydisplayall->myydisplayfoot();
	$myydisplayall->myydisplayright();
	$myydisplayall->myygetuserinfo();
	if($tempmode  == 'search')
	{
		$myydisplayall->myydisplaysearch();
	}
	
//计算所有变量的值结束
	
//输出head信息
	include('myyhead.' . $myyEx);
//输出head信息结束
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
//输出body信息
		

	if(empty($tempmode))
	{
		include('myybody.' . $myyEx);
	}
	if($tempmode == 'login')
	{
		include('login.' . $myyEx);
	}
	if($tempmode == 'resume')
	{
		include('resume.' . $myyEx);
	}
	if($tempmode == 'search')
	{
		include('search.' . $myyEx);
	}
	if($tempmode == 'post')
	{
		include('post.' . $myyEx);
	}
//输出body信息结束

//输出foot信息
	include('myyfoot.' . $myyEx);
//输出foot信息结束
?>