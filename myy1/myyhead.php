<?php
  
                /***************************************************************************
		                                myyhead.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: index.php,v 0.1 2003/04/16  brgd $
		
		 ***************************************************************************/
if ( !defined('IN_MYY') )
{
	die("对不起！无效访问。<p>Sorry! This accessing is not valid! Try again.");
}

header('Content-Type: text/html; charset=' . $myydisplayall->myyhead['encode']);

//输出head信息
	$myytemplate->loadTemplatefile("overall_head.htm");
	
		$myytemplate->setVariable(array(
			"myyencode"      => $myydisplayall->myyhead['encode'],
			"myytitle"       => $myydisplayall->myyhead['title'],
			"myylanguage"    => $myydisplayall->myyhead['language'],
			"myyposition"    => $myydisplayall->myyhead['position'],
			"newcat1"         => $myydisplayall->myybody['newcat1'],
		));
		
		$myytemplate->setCurrentBlock("cat1row");
			for($i = 0; $i<($myydisplayall->myycat1loopnum); $i++)
			{	
				$j = (7 * $i);
				(($j + 7) < ($myydisplayall->myycat1rowsnum)) ? ($z = $j + 7) : ($z = ($myydisplayall->myycat1rowsnum));
				$myytemplate->setCurrentBlock("cat1col");
				
				for($j; $j < $z; $j++)
				{	
					if($j == 1)
					{
						$temp = '<a href=forum/>' . $myylang['forum'] . '</a>';
						$myytemplate->setVariable(array(
							"cat1name" => $temp
						));
						$myytemplate->parse("cat1col");
						continue;
					}
					if($j > 1)
					{
						$k = $j - 1;
					}
					else
					{
						$k = $j;
					}
					
					$temp = '<a href=index.' . $myyEx . '?myy=c1' . ($myydisplayall->myycat1rows[$k]['cat1_id']) . '&lang=' . $myyheadobj->myygetvars['alllang'] . '>' . ($myydisplayall->myycat1rows[$k]['cat1_name']) . '</a>';
					$myytemplate->setVariable(array(
						"cat1name" => $temp
					));
					$myytemplate->parse("cat1col");
				}
				unset($j);
				$myytemplate->parse("cat1row");
			}
		
		unset($j,$z,$y,$i);
		$y = $myydisplayall->myyposition;
		$z = $myydisplayall->myypositionnum - 2;
		$myytemplate->setCurrentBlock("position");
			for($i = 1; $i<$z; $i = $i + 2)
			{	
				$j++;
				$temp = '<a href=index.' . $myyEx . '?myy=c' . $j . $y[0][$i - 1] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '>' . $y[0][$i] . '</a>';
				$myytemplate->setVariable(array(
					"myyposition1" => $temp
				));
				$myytemplate->parse("position");
			}
	$myytemplate->show();

//输出head信息结束
?>