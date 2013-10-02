<?php
  
                /***************************************************************************
		                               updatemodify.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: updatemodify.php,v 0.1 2003/05/07  brgd $
		
		 ***************************************************************************/
//所有对其它页面的访问必须首先通过index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//导入php文件后缀变量
	include ('../includes/extension.inc');
//导入php文件后缀变量结束
  
include ('common_head.' . $myyEx);

//修改相应文章的标题和内容

$title = $myyheadobj->HTTP_POST_VARS['modifytitle'];
$text  = $myyheadobj->HTTP_POST_VARS['modifytext'];

if($myyheadobj->myygetvars['cat'] == '')
{
	//如果不属于用户，不能修改信息
	if(($myyheadobj->myysessionvars['userid'] == $myydisplayall->myytext[0]['user_id']) || $myyheadobj->myysessionvars['userid'] == 1)
	{
		$sql = "UPDATE " . $myylang['text_table'] . " 
			SET title = '$title', text = '$text'" . "
			WHERE text_id = " . $myyheadobj->HTTP_GET_VARS['myy'];
		if( !($result = $myysqlobj->sql_query($sql)) )
		{	
			die("Sorry,can't update,try later. :updatemodify.php 1");
		}
		$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
	}
	//如果不属于用户，不能修改信息结束
}
else
{
	//如果不是用户1不能修改信息
	if($myyheadobj->myysessionvars['userid'] != 1)
	{
		$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
	}
	//如果不是用户1不能修改信息结束
	$tempid   = 'cat' . $myyheadobj->myygetvars['cat'] . '_id';
	$tempname = 'cat' . $myyheadobj->myygetvars['cat'] . '_name';
	$tempdesc = 'descat' . $myyheadobj->myygetvars['cat'];
	
	$sql = "UPDATE " . $myylang['cat_table'] . $myyheadobj->myygetvars['cat'] . " 
		SET $tempname = '$title', $tempdesc = '$text'
		WHERE $tempid = " . $myyheadobj->myygetvars['id'];
	if( !($result = $myysqlobj->sql_query($sql)) )
	{	
		die("Sorry,can't update,try later. :updatemodify.php 2");
	}
}

$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
//修改相应文章的标题和内容结束
?>