<?php
  
                /***************************************************************************
		                                delete.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: delete.php,v 0.1 2003-6-9 23:28  brgd $
		
		 ***************************************************************************/

//���ж�����ҳ��ķ��ʱ�������ͨ��index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//����php�ļ���׺����
	include ('../includes/extension.inc');
//����php�ļ���׺��������
  
include ('common_head.' . $myyEx);

//�ж��Ƿ�ͬ��ɾ��
	if($myyheadobj->HTTP_POST_VARS['delete'] != 'yes')
	{
		if(empty($myyheadobj->HTTP_POST_VARS['delete']) || ($myyheadobj->HTTP_POST_VARS['delete'] == 'no'))
		{
			$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
		}
	}
//�ж��Ƿ�ͬ��ɾ������

//ɾ��Ŀ¼
	//�������Ŀ¼���û�1������ȥɾ���ļ�
	if(!empty($myyheadobj->myygetvars['cat']) && ($myyheadobj->myysessionvars['userid'] == 1))
	{
	//�������Ŀ¼���ļ��ܾ�ɾ��
		if($myydisplayall->myyrightrowsnum != 0)
		{
			$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);
		}
	//�������Ŀ¼���ļ��ܾ�ɾ������
	//������ɾ����ҳ
		if(($myyheadobj->myygetvars['cat'] == 1) && ($myyheadobj->myygetvars['id'] == 1))
		{
			$myyheadobj->myyredirection();
		}
	//������ɾ����ҳ����
	//�����Ŀ¼1ֱ��ɾ��
		if($myyheadobj->myygetvars['cat'] == 1)
		{
			$temptableval = $myylang['cat_table'] . '1';
			$tempcatidval = $myyheadobj->myygetvars['id'];
			
			$sql = "DELETE FROM $temptableval WHERE cat1_id=$tempcatidval";
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't delete,try later. :delete.php -1");
			}
			
			$myyheadobj->myyredirection();
		}
	//�����Ŀ¼1ֱ��ɾ������
	//ɾ����1Ŀ¼
		$temptableval = $myylang['cat_table'] . $myyheadobj->myygetvars['cat'];
		$tempcatid    = 'cat' . $myyheadobj->myygetvars['cat'] . '_id';
		$tempcatidval = $myyheadobj->myygetvars['id'];
		
		$sql = "SELECT * FROM $temptableval WHERE $tempcatid=$tempcatidval";
		if( !($result = $myysqlobj->sql_query($sql)) )
		{
			die("Sorry,can't selest,try later. :delete.php 0");
		}
		while($temp[] = ($myysqlobj->sql_fetchrow($result)));
		$tempcatid1  = 'cat' . ($myyheadobj->myygetvars['cat'] - 1) . '_id';
		$tempincatid = $temp[0][$tempcatid1];
		
		$sql = "DELETE FROM $temptableval WHERE $tempcatid=$tempcatidval";
		if( !($result = $myysqlobj->sql_query($sql)) )
		{	
			die("Sorry,can't delete,try later. :delete.php 1");
		}
		
		$myyheadobj->myyredirection('c' . ($myyheadobj->myygetvars['cat'] - 1) . $tempincatid);
	//ɾ����1Ŀ¼����
	}
//ɾ��Ŀ¼����
//ɾ������
	else
	{
	
		$tempdel = $myydisplayall->myytext[0]['text_id'];
		if(($myyheadobj->myysessionvars['userid'] == $myydisplayall->myytext[0]['user_id']) || ($myyheadobj->myysessionvars['userid'] == 1))
		{
			$sql = "SELECT MAX(text_id) FROM " . $myylang['text_table'];
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't delete,try later. :delete.php 2");
			}
			while($tempmax[] = ($myysqlobj->sql_fetchrow($result)));
			$tempmax = $tempmax[0][0];
			
			$sql = "SELECT * FROM " . $myylang['text_table'] . " WHERE text_id=" . $tempmax;
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't delete,try later. :delete.php 3");
			}
			while($tempmaxval[] = ($myysqlobj->sql_fetchrow($result)));
			$tempmaxval1[0] = $tempmaxval[0]['cat_id'];
			$tempmaxval1[1] = $tempmaxval[0]['in_cat_id'];
			$tempmaxval1[2] = addslashes($tempmaxval[0]['title']);
			$tempmaxval1[3] = addslashes($tempmaxval[0]['text']);
			$tempmaxval1[4] = $tempmaxval[0]['user_id'];
			$tempmaxval1[5] = $tempmaxval[0]['post_date'];
			
			$sql = "UPDATE " . $myylang['text_table'] . " 
				SET cat_id = $tempmaxval1[0], in_cat_id = $tempmaxval1[1], title = '$tempmaxval1[2]', text = '$tempmaxval1[3]', user_id = $tempmaxval1[4], post_date = $tempmaxval1[5]" . "
				WHERE text_id = " . $myyheadobj->HTTP_GET_VARS['myy'];
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't update,try later. :delete.php 4");
			}
			
			$sql = "DELETE FROM " . $myylang['text_table'] . " WHERE text_id=" . $tempmax;
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't delete,try later. :delete.php 5");
			}
			
			$myyheadobj->myyredirection('c' . $myydisplayall->myytext[0]['cat_id'] . $myydisplayall->myytext[0]['in_cat_id']);
		}
	}
//ɾ�����½���
$myyheadobj->myyredirection($myyheadobj->HTTP_GET_VARS['myy']);

?>