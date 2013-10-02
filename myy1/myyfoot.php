<?php 
                /***************************************************************************
		                                myyfoot.php
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
$myytemplate->loadTemplatefile("overall_foot.htm");
	$myytemplate->setVariable(array(
		"companyname" => $myydisplayall->myyfoot['companyname'],
		"service" => $myydisplayall->myyfoot['service'],
		"loginurl" => 'index.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '&mode=login',
	));
	unset($temp,$temp1,$temp2,$z,$y);
	$z = $myydisplayall->myyfootrowsnum;
	$myytemplate->setCurrentBlock("foots");
		for($i = 0; $i < $z; $i++)
		{
			$myytemplate->setVariable(array(
				"footname" => ' [ <a href="index.php?myy=' . $myydisplayall->myyfootrows[$i]['text_id'] . '&lang=' . $myyheadobj->myygetvars['alllang'] . '">' . $myydisplayall->myyfootrows[$i]['title'] . '</a> ] '
			));
			$myytemplate->parse("foots");
		}
$myytemplate->show();
?>