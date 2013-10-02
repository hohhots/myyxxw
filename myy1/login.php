<?php
  
                /***************************************************************************
		                                login.php
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

$myytemplate->loadTemplatefile('login_body.htm');
$myytemplate->setVariable(array(
	"searchaction"=> $myydisplayall->myybody['searchaction'],
	"search"      => $myydisplayall->myybody['search'],
	"searchall"   => $myydisplayall->myybody['searchall'],
	"urls"        => $myydisplayall->myybody['urls'],
	"loginuserid"    => $myylang['loginuserid'],
	"loginuserpass"  => $myylang['loginuserpass'],
	"login"       => $myylang['login'],
	"action1"     => 'index.php?myy=' . $myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $myyheadobj->myygetvars['alllang'],
	"myyup"       => $myydisplayall->myyup,
	"myydown"     => $myydisplayall->myydown
));
	
include ('includes/common_display.' . $myyEx);
$myytemplate->show();
?>