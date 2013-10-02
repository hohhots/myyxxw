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

$myytemplate->loadTemplatefile('search_body.htm');
$myytemplate->setVariable(array(
	"searchaction"=> $myydisplayall->myybody['searchaction'],
	"postarticle" => $myydisplayall->myybody['postarticle'],
	"newcat"      => $myydisplayall->myybody['newcat'],
	"search"      => $myydisplayall->myybody['search'],
	"searchall"   => $myydisplayall->myybody['searchall'],
	"urls"        => $myydisplayall->myybody['urls'],
	"searchkey"   => $myydisplayall->myybody['searchkey'],
	"resultsnum"  => $myydisplayall->myybody['resultsnum'],
	"pagenum"     => $myydisplayall->myybody['pagenum'],
	"jump"        => $myydisplayall->myybody['jump'],
	"jumpaction"  => $myydisplayall->myybody['jumpaction'],
	"display"     => $myydisplayall->myybody['display'],
	"display2"    => $myydisplayall->myybody['display2'],
	"myyup"       => $myydisplayall->myyup,
	"myydown"     => $myydisplayall->myydown
));

include ('includes/common_display.' . $myyEx);
unset($temp,$temp1,$temp2,$z,$y);
$z = $myydisplayall->myysearchnum;
$y = $myydisplayall->myysearchstart + 1;
$myytemplate->setCurrentBlock("results");
//显示搜索结果	

	for($i = 0; $i < $z; $i++)
	{
		//显示搜索链接
		$id = $myydisplayall->myysearch[$i]['text_id'];
		$x = $myydisplayall->getposition('',$id);
		$w = $x['myyposition'];
		for($j = 1; $j<$x['myypositionnum']; $j = $j + 2)
		{	
			$temp .= $w[0][$j] . ' &gt; ';
			if(($j + 2) > $x['myypositionnum'])
			{
				$temp = '<a href=index.' . $myyEx . '?myy=' . $w[0][$j - 1] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '>' . $temp . '</a>';
			}
		}
		$num = '<strong>' . $y . '.&nbsp;</strong> ';
		$resultposition = $temp;
		//显示搜索链接结束
		
		//显示搜索片断
		$temp = $myydisplayall->myysearch[$i]['text'];
		$littlecut = substr(trim(strip_tags($temp)),0,180) . '....';
		//显示搜索片断结束
		
		//显示作者
		$author = '<a href="index.php?myy=' . $myydisplayall->myysearch[$i]['text_id'] . '&mode=resume&userid=' . $myydisplayall->myysearch[$i]['user_id'] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '">' . $myylang['writer'] .$myydisplayall->myysearch[$i]['user_id'] . '</a>';
		//显示作者结束
		
		//显示发表日期
		$postdate = $myylang['postdate'] . $myydisplayall->myysearch[$i]['post_date'];
		//显示发表日期结束
		
		$myytemplate->setVariable(array(
			"num"            => $num,
			"resultposition" => $resultposition,
			"littlecut"      => $littlecut,
			"author"         => $author,
			"postdate"       => $postdate,
		));
		$y++;
		$myytemplate->parse("results");
		unset($temp);
	}
unset($x,$y,$z,$id,$w,$j);
//显示搜索结果结束

$myytemplate->show();
?>