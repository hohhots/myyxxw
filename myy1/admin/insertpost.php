<?php
  
                /***************************************************************************
		                               insertpost.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: delete.php,v 0.1 2003/05/07  brgd $
		
		 ***************************************************************************/

//所有对其它页面的访问必须首先通过index.php
	define ('IN_MYY', true);  
	error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime 

//导入php文件后缀变量
	include ('../includes/extension.inc');
//导入php文件后缀变量结束
  
include ('common_head.' . $myyEx);

if(empty($myyheadobj->HTTP_POST_VARS['posttitle']))
{
	$myyheadobj->HTTP_POST_VARS['posttitle'] = $myylang['defaulttitle'];
}
//插入文章
	if(empty($myyheadobj->HTTP_GET_VARS['postmode']))
	{
		//title,text
		$tempinsert['title'] = $myyheadobj->HTTP_POST_VARS['posttitle'];
		$tempinsert['text']  = $myyheadobj->HTTP_POST_VARS['posttext'];
		//title,text END
	
		//cat_id,in_cat_id
		if(!empty($myyheadobj->myygetvars['cat']))
		{
			$tempinsert['cat_id']    = $myyheadobj->myygetvars['cat'];
			$tempinsert['in_cat_id'] = $myyheadobj->myygetvars['id'];
		}
		else
		{
			$sql = 'SELECT * FROM ' . $myylang['text_table'] . ' WHERE text_id=' . $myyheadobj->myygetvars['id'];
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die('Sorry, wrong happens。insertpost.php 1');
			}
			while( $temp[] = $myysqlobj->sql_fetchrow($result) );
		
			$tempinsert['cat_id']    = $temp[0]['cat_id'];
			$tempinsert['in_cat_id'] = $temp[0]['in_cat_id'];
		}
		//cat_id,in_cat_id END
		
		//user_id
		$tempinsert['user_id'] = $myyheadobj->myysessionvars['userid'];
		//user_id END
		
		//text_id
	
		//text_id END
		$sql = "SELECT MAX(text_id) FROM " . $myylang['text_table'];
		if( !($result = $myysqlobj->sql_query($sql)) )
		{	
			die('Sorry, wrong happens。insertpost.php 2');
		}
		while($tempmax[] = ($myysqlobj->sql_fetchrow($result)));
	
		$temptextid = $tempmax[0][0] + 1;
		$temptitle  = $tempinsert['title'];
		$temptext   = $tempinsert['text'];
	
		$sql = "INSERT INTO " . $myylang['text_table'] . "  ( text_id , cat_id , in_cat_id , title , text , user_id , post_date ) 
			 VALUES ($temptextid," . $tempinsert['cat_id'] . "," . $tempinsert['in_cat_id'] . ",'$temptitle','$temptext'" . "," . $tempinsert['user_id'] . ",CURDATE())";
		if( !($result = $myysqlobj->sql_query($sql)) )
		{	
			die('Sorry, wrong happens。insertpost.php 3');
		}
		$myyheadobj->myyredirection($temptextid);
	}
//插入文章结束

//插入目录
	$tempcatnameval = $myyheadobj->HTTP_POST_VARS['posttitle'];
	$tempdescatval  = $myyheadobj->HTTP_POST_VARS['posttext'];
	$tempinsert['descat']  = $myyheadobj->HTTP_POST_VARS['posttext'];
	if($myyheadobj->HTTP_GET_VARS['postmode'] == 'cat')
	{
		if($myyheadobj->myysessionvars['userid'] != 1)
		{
			$myyheadobj->myyredirection();
		}
		if(empty($myyheadobj->HTTP_GET_VARS['postcat']))
		{
			if(!empty($myyheadobj->myygetvars['cat']))
			{
				$tempinsert['cat_id']    = $myyheadobj->myygetvars['cat'] + 1;
				$tempcatid1val = $myyheadobj->myygetvars['id'];
			}
			else
			{
				$sql = 'SELECT * FROM ' . $myylang['text_table'] . ' WHERE text_id=' . $myyheadobj->myygetvars['id'];
				if( !($result = $myysqlobj->sql_query($sql)) )
				{	
					die('Sorry, wrong happens。insertpost.php 4');
				}
				while( $temp[] = $myysqlobj->sql_fetchrow($result) );
			
				$tempinsert['cat_id']    = $temp[0]['cat_id'] + 1;
				$tempcatid1val = $temp[0]['in_cat_id'];
			}
			
			$result = mysql_list_tables($myysqlobj->dbname);
    
			if (!$result) {
				print "DB Error, could not list tables\n";
				print 'MySQL Error: ' . mysql_error();
				exit;
			}
	    		
	    		$temptable = $myylang['cat_table'] . $tempinsert['cat_id'];
	    		$tempcatid = 'cat' . $tempinsert['cat_id'] . '_id';
	    		$tempcatid1 = 'cat' . ($tempinsert['cat_id'] - 1) . '_id';
	    		$tempcatname = 'cat' . $tempinsert['cat_id'] . '_name';
	    		$tempdescat = 'descat' . $tempinsert['cat_id'];
	    		$tableexist = 0;
	    		while ($row = mysql_fetch_row($result)) {
				if($row[0] == $temptable)
				{
					$tableexist = 1;
				}
			}
			if($tableexist == 0)
			{
				$sql = "CREATE TABLE $temptable (
   					$tempcatid  tinyint(3) UNSIGNED NOT NULL,
   					$tempcatname varchar(255) NOT NULL,
   					$tempcatid1  tinyint(3) UNSIGNED NOT NULL,
   					$tempdescat  text,
   					est_date date NOT NULL,
   					mod_date date NOT NULL,
   					mod_userid smallint(5) UNSIGNED,
   					PRIMARY KEY ($tempcatid))";
   				if( !($result = $myysqlobj->sql_query($sql)) )
				{	
					die('Sorry, wrong happens。insertpost.php 5');
				}
   				
			}
		//搜索类表里最大ID号
			$sql = "SELECT MAX($tempcatid) FROM " . $temptable;
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die('Sorry, wrong happens。insertpost.php 6');
			}
			while($tempmax[] = ($myysqlobj->sql_fetchrow($result)));
			if(!empty($tempmax[0][0]))
			{
				$tempcatidval = $tempmax[0][0] + 1;
			}
			else
			{
				$tempcatidval = 1;
			}
		//搜索类表里最大ID号结束
		
			$tempdescatval = $tempinsert['descat'];
			$sql = "INSERT INTO $temptable ( $tempcatid , $tempcatname , $tempcatid1 , $tempdescat , est_date ) 
				 VALUES ($tempcatidval,'$tempcatnameval',$tempcatid1val,'$tempdescatval',CURDATE())";
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die('Sorry, wrong happens。insertpost.php 7');
			}
			$tempmyyurl = 'c' . $tempinsert['cat_id'] . $tempcatidval;
			$myyheadobj->myyredirection($tempmyyurl);
		}
		
	//插入目录1
		if($myyheadobj->HTTP_GET_VARS['postcat'] == '1')
		{
		//搜索类表里最大ID号
			$temptable = $myylang['cat_table'] . '1';
			$sql = "SELECT MAX(cat1_id) FROM " . $temptable;
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die('Sorry, wrong happens。insertpost.php 8');
			}
			while($tempmax[] = ($myysqlobj->sql_fetchrow($result)));
			if(!empty($tempmax[0][0]))
			{
				$tempcatidval = $tempmax[0][0] + 1;
			}
			else
			{
				$tempcatidval = 1;
			}
		//搜索类表里最大ID号结束

			$sql = "INSERT INTO $temptable ( cat1_id , cat1_name , descat1 , est_date ) 
				 VALUES ($tempcatidval,'$tempcatnameval','$tempdescatval',CURDATE())";
			if( !($result = $myysqlobj->sql_query($sql)) )
			{	
				die('Sorry, wrong happens。insertpost.php 9');
			}
			$tempmyyurl = 'c1' . $tempcatidval;
			$myyheadobj->myyredirection($tempmyyurl);
		}
	//插入目录1结束
	}
//插入目录结束

?>