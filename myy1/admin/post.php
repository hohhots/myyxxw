<?php
  
                /***************************************************************************
		                                post.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: index.php,v 0.1 2003/05/05  brgd $
		
		 ***************************************************************************/
//所有对其它页面的访问必须首先通过index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//导入php文件后缀变量
	include ('../includes/extension.inc');
//导入php文件后缀变量结束

include ('common_head.' . $myyEx);
$myytemplate->loadTemplatefile('admin/post_body.htm');
$myytemplate->setVariable(array(
	"myyencode"   => $myydisplayall->myyhead['encode'],
	"myytitle"    => $myydisplayall->myyhead['title'],
	"photossrc"   => 'photos.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'] ,
	"home"        => '<a href="../index.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '">' . $myylang['return'] . '</a>',
	"posttitle"   => $myylang['posttitle'],
 	"postart"     => $myylang['postart'],
	"postaction"  => 'insertpost.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&postmode=' . $myyheadobj->HTTP_GET_VARS['postmode'] . '&postcat=' . $myyheadobj->HTTP_GET_VARS['postcat'] . '&lang=' . $myyheadobj->myygetvars['alllang'],
	"service"     => $myydisplayall->myyfoot['service'],
	"pmvalue"     => $myylang['pmvalue'],
	"myyphotos"  => $myylang['myyphotos'],
));

$myytemplate->show();
?>