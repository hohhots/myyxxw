<?php
  
                /***************************************************************************
		                                photos.php
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

$myydisplayall->myygetphotos();

$myytemplate->loadTemplatefile('admin/photos_body.htm');
$myytemplate->setVariable(array(
	"myyencode"  => $myydisplayall->myyhead['encode'],
	"myytitle"   => $myydisplayall->myyhead['title'],
	"firstdir"   => $myydisplayall->myybody['firstdir'],
	"parentsdir" => $myydisplayall->myybody['parentsdir'],
));

$myytemplate->setCurrentBlock("photosdirs");
	$z = count($myydisplayall->myyphoto);
	for($i = 0; $i < $z; $i++)
	{
		$myytemplate->setVariable(array(
			"photosdir" => $myydisplayall->myyphoto[$i],
		));
		$myytemplate->parse("photosdirs");
	}

$x = count($myydisplayall->myyphotof);
$z = (($x + ($x % 2)) / 2);
$myytemplate->setCurrentBlock("photorow");
for($i = 0; $i < $z; $i++)
{	
	$myytemplate->setCurrentBlock("photos");
		for($j = 0; $j < 2; $j++)
		{	
			if(($y = (2 * $i + $j)) > $x)
			{
				break 2;	
			}
			$myytemplate->setVariable(array(
				"photo" => $myydisplayall->myyphotof[$y],
				"detail"=> $myydisplayall->myyphotod[$y]
			));
			$myytemplate->parse("photos");
		}
	unset($j);
	$myytemplate->parse("photorow");
}

$myytemplate->show();
?>