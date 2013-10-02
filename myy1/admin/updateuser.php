<?php
  
                /***************************************************************************
		                                updateuser.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: updateuser.php,v 0.1 2003/05/08  brgd $
		
		 ***************************************************************************/
//所有对其它页面的访问必须首先通过index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//导入php文件后缀变量
	include ('../includes/extension.inc');
//导入php文件后缀变量结束
  
include ('common_head.' . $myyEx);

if($this->myygetvars['lang'] == 'english')
{
	$username = 'user_enname';
	$resume   = 'resumeen';
}
else
{
	$username = 'user_cnname';
	$resume   = 'resumecn';
}

$usernameval = $myyheadobj->HTTP_POST_VARS['username'];
$resumeval   = $myyheadobj->HTTP_POST_VARS['resume'];

$sql = "UPDATE " . $myylang['user_table'] . " 
	SET $username = '$usernameval', $resume = '$resumeval'" . "
	WHERE user_id = " .  $myyheadobj->myysessionvars['userid'];

if( !($result = $myysqlobj->sql_query($sql)) )
{	
	die("Sorry,can't update,try later. :updatuser.php 1");
}

$temp = $myyheadobj->HTTP_GET_VARS['myy'] . '&mode=resume&userid=' . $myyheadobj->myyuserinfo[0]['user_id'];
$myyheadobj->myyredirection($temp);

?>