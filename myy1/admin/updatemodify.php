<?php
  
                /***************************************************************************
		                               updatemodify.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: updatemodify.php,v 0.1 2003/05/07  brgd $
		
		 ***************************************************************************/
//���ж�����ҳ��ķ��ʱ�������ͨ��index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//����php�ļ���׺����
	include ('../includes/extension.inc');
//����php�ļ���׺��������
  
include ('common_head.' . $myyEx);

//�޸���Ӧ���µı��������

$title = $myyheadobj->HTTP_POST_VARS['modifytitle'];
$text  = $myyheadobj->HTTP_POST_VARS['modifytext'];

if($myyheadobj->myygetvars['cat'] == '')
{
	//����������û��������޸���Ϣ
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
	//����������û��������޸���Ϣ����
}
else
{
	//��������û�1�����޸���Ϣ
	if($myyheadobj->myysessionvars['userid'] != 1)
	{
		$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
	}
	//��������û�1�����޸���Ϣ����
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
//�޸���Ӧ���µı�������ݽ���
?>