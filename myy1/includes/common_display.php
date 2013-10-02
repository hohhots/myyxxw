<?php
  
                /***************************************************************************
		                                search.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: index.php,v 0.1 2003/04/10  brgd $
		
		 ***************************************************************************/
if ( !defined('IN_MYY') )
{
	die("对不起！无效访问。<p>Sorry! This accessing is not valid! Try again.");
}
//显示友情链接
unset($temp,$temp1,$temp2,$z,$y);
$z = $myydisplayall->myyurlsrowsnum;
$myytemplate->setCurrentBlock("allurls");
	for($i = 0; $i < $z; $i++)
	{
		$myytemplate->setVariable(array(
			"urlname" => '<option value="http://' . ($myydisplayall->myyurls[$i]['url_url']) . '">' . ($myydisplayall->myyurls[$i]['url_name']) . '</option>'
		));
		$myytemplate->parse("allurls");
	}
//显示友情链接结束

//显示搜索表单	
unset($temp,$temp1,$temp2,$z,$y);
$z = $myydisplayall->myycat1rowsnum;
$myytemplate->setCurrentBlock("searchcats");
	for($i = 0; $i < $z; $i++)
	{
		if($myydisplayall->myycat1rows[$i]['cat1_id'] > 1)
		{
			$myytemplate->setVariable(array(
				"searchname" => '<option value="' . $myydisplayall->myycat1rows[$i]['cat1_id'] . '">' . $myydisplayall->myycat1rows[$i]['cat1_name'] . '</option>'
			));
			$myytemplate->parse("searchcats");
		}
	}
//显示搜索表单结束

//显示子目录或文章	
unset($temp,$temp1,$temp2,$z,$y);
$z = $myydisplayall->myyrightrowsnum;

$myytemplate->setCurrentBlock("subnames");
	for($i = 0; $i < $z; $i++)
	{
		$temp1++;
		$temp = $myydisplayall->displaysubname($myyheadobj->HTTP_GET_VARS['mode'], $myydisplayall->myyrightrows[$i], $myyheadobj->myygetvars);
		if(!empty($temp))
		{
			$myytemplate->setVariable(array(
				"subnum"  => $temp1 . '.',
				"subname" => $temp
			));
			$myytemplate->parse("subnames");
		}
	}
//显示子目录或文章结束
?>