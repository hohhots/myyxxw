<?php
  
                /***************************************************************************
		                                resume.php
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

$myytemplate->loadTemplatefile('resume_body.htm');
$myytemplate->setVariable(array(
	"searchaction"=> $myydisplayall->myybody['searchaction'],
	"postarticle" => $myydisplayall->myybody['postarticle'],
	"newcat"      => $myydisplayall->myybody['newcat'],
	"searchaction"=> $myydisplayall->myybody['searchaction'],
	"search"      => $myydisplayall->myybody['search'],
	"searchall"   => $myydisplayall->myybody['searchall'],
	"urls"        => $myydisplayall->myybody['urls'],
	"username"    => $myydisplayall->myyuserdata['username'],
	"resume"      => $myydisplayall->myyuserdata['resume'],
	"modifyuser"  => $myydisplayall->myybody['modifyuser'],
	"myyup"       => $myydisplayall->myyup,
	"myydown"     => $myydisplayall->myydown
));
	
include ('includes/common_display.' . $myyEx);
$myytemplate->show();
?>