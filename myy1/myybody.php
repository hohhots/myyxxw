<?php
  
                /***************************************************************************
		                                myybody.php
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

$myytemplate->loadTemplatefile('index_body.htm');
$myytemplate->setVariable(array(
	"postarticle" => $myydisplayall->myybody['postarticle'],
	"newcat"      => $myydisplayall->myybody['newcat'],
	"modify"      => $myydisplayall->myybody['modify'],
	"delete"      => $myydisplayall->myybody['delete'],
	"searchaction"=> $myydisplayall->myybody['searchaction'],
	"search"      => $myydisplayall->myybody['search'],
	"searchall"   => $myydisplayall->myybody['searchall'],
	"urls"        => $myydisplayall->myybody['urls'],
	"texttitle"   => $myydisplayall->myybody['texttitle'],
	"text"        => $myydisplayall->myybody['text'],
	"postdate"    => $myydisplayall->myybody['postdate'],
	"writer"      => $myydisplayall->myybody['writer'],
	"username"    => $myylang['username'],
	"userpass"    => $myylang['userpass'],
	"myyup"       => $myydisplayall->myyup,
	"myydown"     => $myydisplayall->myydown
));

include ('includes/common_display.' . $myyEx);
$myytemplate->show();
?>