<?php
  
                /***************************************************************************
		                                modify.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: modify.php,v 0.1 2003/05/08  brgd $
		
		 ***************************************************************************/
//���ж�����ҳ��ķ��ʱ�������ͨ��index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//����php�ļ���׺����
	include ('../includes/extension.inc');
//����php�ļ���׺��������
  
include ('common_head.' . $myyEx);

$myytemplate->loadTemplatefile('admin/modify_body.htm');
$myytemplate->setVariable(array(
	"myyencode"   => $myydisplayall->myyhead['encode'],
	"myytitle"    => $myydisplayall->myyhead['title'],
	"photossrc"   => 'photos.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'] ,
	"home"        => '<a href="../index.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '">' . $myylang['return'] . '</a>',
	"modifytitle"   => $myylang['posttitle'],
 	"modifyart"     => $myylang['postart'],
	"modifyaction"  => 'updatemodify.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'],
	"service"     => $myydisplayall->myyfoot['service'],
	"pmvalue"     => $myylang['pmvalue'],
	"mtitlevalue" => $myydisplayall->myybody['texttitle'],
	"mtextvalue" => $myydisplayall->myybody['text'],
	"myyphotos"  => $myylang['myyphotos'],
));

$myytemplate->show();
?>