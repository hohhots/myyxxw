<?php
 
                /***************************************************************************
		                                common.php
		                             -------------------
		    begin                : February Mon 4 2003
		    copyright            : (C) 2003 The nm114.net Group
		    email                : brgd@263.net
		
		    $Id: index.php,v 0.1 2003/04/10 brgd $
		
		 ***************************************************************************/

if ( !defined('IN_MYY') )
{
	die("Sorry! This accessing is not valid! Try again.");
}

class MyyClassHead
{
	var $myyURL;
	var $myysqlobj;
	var $HTTP_GET_VARS = array();
	var $HTTP_POST_VARS = array();
	var $HTTP_COOKIE_VARS = array();
	var $HTTP_SERVER_VARS = array();
	var $HTTP_ENV_VARS = array();
	var $REMOTE_ADDR = array();
	var $myycookie = array();
	var $myylang = array();
	var $myysessionvars = array();//userip,userid,sessionid
	
	var $myygetvars = array();
	var $current_time;
	var $myyuserinfo = array();
	
	//*****************************************************
	function MyyClassHead($myyURL,$myysqlobj,$HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_SERVER_VARS,$HTTP_ENV_VARS,$REMOTE_ADDR,
				$myycookie,$myylang)
	{
		$this->myyURL = $myyURL;
		$this->myysqlobj = $myysqlobj;
		$this->HTTP_GET_VARS = $HTTP_GET_VARS;
		$this->HTTP_POST_VARS = $HTTP_POST_VARS;
		$this->HTTP_COOKIE_VARS = $HTTP_COOKIE_VARS;
		$this->HTTP_SERVER_VARS = $HTTP_SERVER_VARS;
		$this->HTTP_ENV_VARS = $HTTP_ENV_VARS;
		$this->REMOTE_ADDR = $REMOTE_ADDR;
		$this->myycookie = $myycookie;
		$this->myylang = $myylang;
		$this->current_time = time();
		
		for($i = 0; $i < 3; $i++)
		{
			if( !get_magic_quotes_gpc() )
			{
				switch($i)
				{
					case 0:
						$gpcvars = $this->HTTP_GET_VARS;
						break;
					case 1:
						$gpcvars = $this->HTTP_POST_VARS;
						break;
					case 2:
						$gpcvars = $this->HTTP_COOKIE_VARS;
						break;
				}
				if( is_array($gpcvars) )
				{
					while( list($k, $v) = each($gpcvars) )
					{
						if( is_array($gpcvars[$k]) )
						{
							while( list($k2, $v2) = each($gpcvars[$k]) )
							{
								$$gpcvars[$k][$k2] = addslashes($v2);
							}
							@reset($gpcvars[$k]);
						}
						else
						{
							$gpcvars[$k] = addslashes($v);
						}
					}
					@reset($gpcvars);
				}
				switch($i)
				{
					case 0:
						$this->HTTP_GET_VARS = $gpcvars;
						break;
					case 1:
						$this->HTTP_POST_VARS = $gpcvars;
						break;
					case 2:
						$this->HTTP_COOKIE_VARS = $gpcvars;
						break;
				}
			}
		}
		$this->myyanalyzeget();
   	}
   	
   	//****************************************************************
   	function myyanalyzeget()
   	{
   		if(eregi("cn", $this->HTTP_GET_VARS['lang']))
		{
			$this->myygetvars['alllang1'] = 'en';
			$this->myygetvars['alllang'] = 'cn';
			$this->myygetvars['lang'] = 'chinese';
		}
		if(eregi("en", $this->HTTP_GET_VARS['lang']))
		{
			$this->myygetvars['alllang1'] = 'cn';
			$this->myygetvars['alllang'] = 'en';
			$this->myygetvars['lang'] = 'english';
		}
		if(($this->HTTP_GET_VARS['myy']) != '')
   		{
   			if(eregi("c",$this->HTTP_GET_VARS['myy']))
			{
				$this->myygetvars['cat'] = substr($this->HTTP_GET_VARS['myy'],1,1);
				$this->myygetvars['id'] = substr($this->HTTP_GET_VARS['myy'],2);
			}
			else
			{
				$this->myygetvars['id'] = $this->HTTP_GET_VARS['myy'];
			}
			$this->myysession();
		}
   		else
   		{
   			$this->myyredirection();
   		}
   	}
   	//*******************************************************
   	function myyredirection($myyvars = 'c11')
	{
		if(empty($this->myygetvars['alllang']) &&
			($this->myygetvars['alllang'] != 'cn') &&
			($this->myygetvars['alllang'] != 'en'))
		{
			$temp = "index.html";
		}
		else
		{
			if($this->myygetvars['alllang'] == 'cn')
			{
				$temp = 'index.php?myy=' . $myyvars . '&lang=cn';
			}
			if($this->myygetvars['alllang'] == 'en')
			{
				$temp = 'index.php?myy=' . $myyvars . '&lang=en';	
			}
		}
		header('Location:' . $this->myyURL . $temp);
		exit();
	}
	//*******************************************************
	function myygetuserip()
	{
		if( getenv('HTTP_X_FORWARDED_FOR') != '' )
		{
			$client_ip = ( !empty($this->HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $this->HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($this->HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $this->HTTP_ENV_VARS['REMOTE_ADDR'] : $this->REMOTE_ADDR );

			if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip_list) )
			{
				$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
				$client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
			}
		}
		else
		{
			$client_ip = ( !empty($this->HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $this->HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($this->HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $this->HTTP_ENV_VARS['REMOTE_ADDR'] : $this->REMOTE_ADDR );
		}
		$this->myysessionvars['userip'] = ($this->myyencode_ip($client_ip));
	}
	//*******************************************************
	function myyencode_ip($dotquad_ip)
	{
		$ip_sep = explode('.', $dotquad_ip);
		return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
	}
	//*******************************************************
	function myydecode_ip($int_ip)
	{
		$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
		return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
	}
	//*******************************************************
	function myysessionbegin()
	{
		
		unset($temp,$tempuname,$tempupass);
		$tempuname = $this->HTTP_POST_VARS['username'];
		$tempupass = $this->HTTP_POST_VARS['userpass'];

//检查用户号码和口令是否有效	
		if(!ereg("^[0-9]+$",$tempuname))
		{	
			$this->myyredirection($this->HTTP_GET_VARS['myy'] . '&mode=login');
		}
		$sql = "SELECT * FROM " . $this->myylang['user_table'] . " WHERE user_id = $tempuname";
		if( !($result = $this->myysqlobj->sql_query($sql)) )
		{	
			die('Error searching user table:common.php');
		}
		if(($this->myysqlobj->sql_numrows($result)) == 0)
		{	
			$this->myyredirection($this->HTTP_GET_VARS['myy'] . '&mode=login');
		}
		while($temp[] = $this->myysqlobj->sql_fetchrow($result));
		unset($userpasstrue);
		if($temp[0]['user_pass'] == ($tempupass))
		{
			$userpasstrue = 1;
		}
		else
		{
			$this->myyredirection($this->HTTP_GET_VARS['myy'] . '&mode=login');
		}
//检查用户号码和口令是否有效结束

//用户号码和口令有效,设置session
		if($userpasstrue == 1)
		{
			$this->myysessionvars['sessionid'] = md5(uniqid($this->myysessionvars['userip'] . $this->current_time));
			$this->myysessionvars['userid'] = $temp[0]['user_id'];
			
			$temps_id = $this->myysessionvars['sessionid'];
			$temps_user_id = $this->myysessionvars['userid'];
			$temps_start = $this->current_time;
			$temps_ip = $this->myysessionvars['userip'];
			$temps_logged_in = 1;
			
			$sql = "INSERT INTO " . $this->myylang['session_table'] . " (session_id, session_user_id, session_start, session_ip, session_logged_in) 
				VALUES ('$temps_id',$temps_user_id,$temps_start,'$temps_ip',$temps_logged_in)";
			
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('Error inserting sessions table:common.php');
			}
			
			$sql =  "UPDATE " . $this->myylang['user_table'] . "
				SET lastlog_date = CURDATE()
				WHERE user_id = $temps_user_id";
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('Error inserting user_table.common.php');
			}
			
			setcookie($this->myycookie['name'], $this->myysessionvars['sessionid'], 0, $this->myycookie['path'], $this->myycookie['domain'], $this->myycookie['secure']);
		}
//用户号码和口令有效,设置session结束
		
	}
	//*******************************************************
	function myysession()
	{
		unset($temp,$tempsessionid,$this->myysessionvars['sessionid']);
		$tempsessionid = (isset($this->HTTP_COOKIE_VARS[$this->myycookie['name']]) ? $this->HTTP_COOKIE_VARS[$this->myycookie['name']] : '');
		
//如果cookie不存在，设置session

		if($tempsessionid == '')
		{
			if((isset($this->HTTP_POST_VARS['username'])) && (isset($this->HTTP_POST_VARS['userpass'])))
			{
				$this->myygetuserip();
				$this->myysessionbegin();
				return;
			}
		}
//如果cookie不存在，设置session结束
	
//如果cookie存在，处理session
		if( $tempsessionid != '' )
		{
//删除过期session
			$expiry_time = (($this->current_time) - ($this->myycookie['length']));
			$sql = "DELETE FROM " . $this->myylang['session_table'] . " 
				WHERE session_start < $expiry_time";
			if ( !$this->myysqlobj->sql_query($sql) )
			{
				die('删除session出现错误。Error clearing sessions table.');
			}
//删除过期session结束

//如果是注销，消除session	
			if($this->HTTP_GET_VARS['mode'] == 'login')
			{
				if(!isset($this->HTTP_POST_VARS['username']) && !isset($this->HTTP_POST_VARS['userpass']))
				{
					$sql = "DELETE FROM " . $this->myylang['session_table'] . 
						" WHERE session_id = '$tempsessionid'";
					if ( !$this->myysqlobj->sql_query($sql) )
					{
						die('删除session出现错误。Error clearing sessions table.');
					}
					
					$this->myysessionend();
					return;
				}
			}
//如果是注销，消除session结束

//确认session是否有效
			$this->myygetuserip();
			unset($this->myysessionvars['sessionid']);

			$sql = "SELECT * FROM " . $this->myylang['session_table'] . " WHERE session_id = '$tempsessionid'";
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('查询session出现错误。Error searching sessions table.');
			}
			if(($this->myysqlobj->sql_numrows($result)) == 0)
			{	
				setcookie($this->myycookie['name']);
				$this->myyredirection($this->HTTP_GET_VARS['myy'] . '&mode=login');
				return;
			}

			unset($temp);
			while($temp[] = $this->myysqlobj->sql_fetchrow($result));

			$ip_check_s = substr($temp[0]['session_ip'], 0, 6);
			$ip_check_u = substr($this->myysessionvars['userip'], 0, 6);

			if(($temp[0]['session_logged_in'] == 1) && ($ip_check_s == $ip_check_u))
			{
				$this->myysessionvars['sessionid'] = $tempsessionid;
				$this->myysessionvars['userid'] = $temp[0]['session_user_id'];
				
				//确定用户所有信息
				$userid = $temp[0]['session_user_id'];
				$sql = "SELECT * FROM " . $this->myylang['user_table'] . "
					WHERE user_id=$userid";
				if ( !($result = $this->myysqlobj->sql_query($sql)) )
				{
					die('搜索user出现错误。Error searching user table. common.php');
				}
				while($this->myyuserinfo[] = $this->myysqlobj->sql_fetchrow($result));
				if($this->myygetvars['lang'] == 'english')
				{
					$this->myyuserinfo[0]['username'] = $this->myyuserinfo[0]['user_enname'];
					$this->myyuserinfo[0]['resume']   = $this->myyuserinfo[0]['resumeen'];
				}
				else
				{
					$this->myyuserinfo[0]['username'] = $this->myyuserinfo[0]['user_cnname'];
					$this->myyuserinfo[0]['resume']   = $this->myyuserinfo[0]['resumecn'];
				}
				//确定用户所有信息结束
				
				$sql = "UPDATE " . $this->myylang['session_table'] . " 
						SET session_start = $this->current_time 
						WHERE session_id = '$tempsessionid'";
				if ( !$this->myysqlobj->sql_query($sql) )
				{
					die('修改session出现错误。Error modifying sessions table.');
				}
			}
			else
			{
				$this->myysessionend();	
			}
//确认session是否有效结束
		}
//如果cookie存在，处理session结束
	
	}

	//*******************************************************
	function myysessionend()
	{
		setcookie($this->myycookie['name']);
		$this->myyredirection($this->HTTP_GET_VARS['myy']);
	}
}
//##################################################################################
class MyyClassDisplay
{
	var $myyheadobj;
	var $myysqlobj;
	var $myylang;
	
	var $myyhead = array();
		//'encode','title','language','position';
	var $myycat1rows = array();
	var $myycat1rowsnum;
	var $myycat1loopnum;
	
	var $myyposition = array();
	var $myypositionnum;
	
	var $myybody = array();
		/**
		'texttitle','text','postdate','date','writer','userid','modify','display','display2'
		'keyword','search','searchall','postarticle','newcat','newcat1','modifyuser';
		'searchkey','pagenum','jump','jumpaction','resultsposition','littlecut','author'
		'firstdir','parentsdir';
		**/
	var $myybodyfile;
	var $myyurlsrowsnum;
	var $myyurls = array();
	
	var $myytext = array();
	
	var $myyfoot = array();
	var $myyfootrows = array();
	var $myyfootrowsnum = array();
	
	var $temppagenummax = 1;
	var $myydown;
	var $myyup;
	var $allrpnum = 1;
	var $myyrightrows = array();
	var $myyrightrowsnum = array();
	
	var $myyuserdata = array();
		//'username','resume','userid','userpass','joindate','lastlogdate';
	
	var $myysearch = array();
	var $myysearchnum;
	var $myysearchstart;
	
	var $myyphotosnum;
	var $myyphoto = array();
	var $myyphotof = array();
	
	//********************************************************
	function MyyClassDisplay($myyheadobj,$myysqlobj,$myylang)
	{
		$this->myyheadobj = $myyheadobj;
		$this->myysqlobj = $myysqlobj;
		$this->myylang = $myylang;
	}
	//********************************************************
	function myydisplaycat1()
	{
		$this->myyhead['encode']   = $this->myylang['encode'];
		$this->myyhead['title']    = $this->myylang['title'];
		$this->myyhead['language'] = '&nbsp;<a href=' . $this->myyheadobj->myyURL . 'index.php?myy=c11&lang=' . $this->myyheadobj->myygetvars['alllang1'] . '>' . $this->myylang['language'] . '</a>&nbsp;';
		$this->myyhead['position'] = $this->myyheadobj->myysessionvars['userid'] . '&nbsp;' . $this->myylang['position'];
		
		$sql = "SELECT * FROM " .$this->myylang['cat_table'] . "1 c ORDER BY c.cat1_id";
		if( !($result = $this->myysqlobj->sql_query($sql)) )
		{	
			die("Sorry,can't connect to table.CODE common.php:001");
		}
		$this->myycat1rowsnum = $this->myysqlobj->sql_numrows($result) + 1 ;
		while($this->myycat1rows[] = $this->myysqlobj->sql_fetchrow($result));
		($this->myycat1rowsnum % 7) ? ($this->myycat1loopnum = ((($this->myycat1rowsnum - ($this->myycat1rowsnum % 7)) / 7) + 1)) : ($this->myycat1loopnum = ($this->myycat1rowsnum / 7));
	}
	//********************************************************
	function myydisplayposition()
	{
		$cat = $this->myyheadobj->myygetvars['cat'];
		$id = $this->myyheadobj->myygetvars['id'];
		$temp = $this->getposition($cat,$id);
		$this->myytext        = $temp['myytext'];
		$this->myypositionnum = $temp['myypositionnum'];
		$this->myyposition    = $temp['myyposition'];
		unset($cat,$id,$temp);
	}
	//********************************************************
	function myydisplaybody()
	{
		$sql = "SELECT * FROM " . $this->myylang['urls_table'] . " WHERE 1";
		if( !($result = $this->myysqlobj->sql_query($sql)) )
		{	
			die("Sorry,can't connect to table.CODE common.php:005");
		}
		$this->myyurlsrowsnum = $this->myysqlobj->sql_numrows($result);
		while($this->myyurls[] = ($this->myysqlobj->sql_fetchrow($result)));
	}
	//********************************************************
	function myydisplayright()
	{
		if( !empty($this->myyheadobj->myygetvars['cat']) )
		{
			unset($temp,$z,$y,$x);
			if(($this->myyheadobj->myygetvars['id'] == 1) && ($this->myyheadobj->myygetvars['cat'] == 1))
			{
				$sql = "SELECT * FROM " . $this->myylang['cat_table'] . "2 WHERE cat1_id=2 ORDER BY cat2_id";
			}
			else
			{
				$sql = "SELECT * FROM " . $this->myylang['cat_table'] . ($this->myyheadobj->myygetvars['cat'] + 1) . 
					" WHERE cat" . $this->myyheadobj->myygetvars['cat'] . "_id=" . $this->myyheadobj->myygetvars['id'] . 
					" ORDER BY cat" . ($this->myyheadobj->myygetvars['cat'] + 1) . "_id";
			}
			$result = $this->myysqlobj->sql_query($sql);
			$this->myyrightrowsnum = $this->myysqlobj->sql_numrows($result);
			while($temp[] = ($this->myysqlobj->sql_fetchrow($result)));
			$x = ($this->myyheadobj->myygetvars['cat'] + 1);
			$z = 'cat' . $x . '_id';
			$y = 'cat' . $x . '_name';
			for($i = $this->myyrightrowsnum - 1; $i >= 0; $i--)
			{
				$this->myyrightrows[$i]['id'] = $temp[$i][$z];
				$this->myyrightrows[$i]['name'] = $temp[$i][$y];
				$this->myyrightrows[$i]['cat'] = $x;
			}
			
			unset($temp,$z,$y,$x);
			if(($this->myyheadobj->myygetvars['id'] == 1) && ($this->myyheadobj->myygetvars['cat'] == 1))
			{
				$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE cat_id=1 AND in_cat_id=2 ORDER BY text_id";
			}
			else
			{
				$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE cat_id=" . $this->myyheadobj->myygetvars['cat'] . " AND in_cat_id=" . $this->myyheadobj->myygetvars['id'] . " ORDER BY text_id";
			}
			$result = $this->myysqlobj->sql_query($sql);
			while($temp[] = ($this->myysqlobj->sql_fetchrow($result)));
			$z = $this->myysqlobj->sql_numrows($result);
			$y = empty($this->myyrightrowsnum)?( 0 ):( $this->myyrightrowsnum );
			$this->myyrightrowsnum += $z;
			for($i = $z - 1; $i >= 0; $i--)
			{
				$this->myyrightrows[$y]['id'] = $temp[$i]['text_id'];
				$this->myyrightrows[$y]['name'] = $temp[$i]['title'];
				$this->myyrightrows[$y]['cat'] = 0;
				$y++;
			}
		}
		else
		{
			unset($temp,$z,$y,$x);
			$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE text_id=" . $this->myyheadobj->myygetvars['id'];
			$result = $this->myysqlobj->sql_query($sql);
			while($text1[] = ($this->myysqlobj->sql_fetchrow($result)));
			
			unset($temp,$z,$y,$x);
			$sql = "SELECT * FROM " . $this->myylang['cat_table'] . ($text1[0]['cat_id'] + 1) . 
				" WHERE cat" . $text1[0]['cat_id'] . "_id=" . $text1[0]['in_cat_id'] . 
				" ORDER BY cat" . ($text1[0]['cat_id'] + 1) . '_id';
			$result = $this->myysqlobj->sql_query($sql);
			$this->myyrightrowsnum = $this->myysqlobj->sql_numrows($result);
			while($temp[] = ($this->myysqlobj->sql_fetchrow($result)));
			$x = ($text1[0]['cat_id'] + 1);
			$z = 'cat' . $x . '_id';
			$y = 'cat' . $x . '_name';
			for($i = $this->myyrightrowsnum - 1; $i >= 0; $i--)
			{
				$this->myyrightrows[$i]['id'] = $temp[$i][$z];
				$this->myyrightrows[$i]['name'] = $temp[$i][$y];
				$this->myyrightrows[$i]['cat'] = $x;
			}
			
			unset($temp,$z,$y,$x);
			$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE cat_id=" . $text1[0]['cat_id'] . " AND in_cat_id=" . $text1[0]['in_cat_id'] . " ORDER BY text_id";
			$result = $this->myysqlobj->sql_query($sql);
			while($temp[] = ($this->myysqlobj->sql_fetchrow($result)));
			$z = $this->myysqlobj->sql_numrows($result);
			$y = empty($this->myyrightrowsnum)?( 0 ):( $this->myyrightrowsnum );
			$this->myyrightrowsnum += $z;
			for($i = $z - 1; $i >= 0; $i--)
			{
				$this->myyrightrows[$y]['id'] = $temp[$i]['text_id'];
				$this->myyrightrows[$y]['name'] = $temp[$i]['title'];
				$this->myyrightrows[$y]['cat'] = 0;
				$y++;
			}
		}
		if($this->myyheadobj->myygetvars['cat'] == '')
		{
			if(($this->myytext[0]['cat_id'] != 1) || ($this->myytext[0]['in_cat_id'] != 1))
			{
				$this->myybody['postdate'] = $this->myylang['postdate'] . $this->myytext[0]['post_date'];
				$this->myybody['writer'] = '<a href="index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&mode=resume&userid=' . $this->myytext[0]['user_id'] . 
							'">' . $this->myylang['writer'] . $this->myytext[0]['user_id'] . '</a>';
			}
		}
		if(($this->myyheadobj->myysessionvars['userid'] == $this->myytext[0]['user_id']) || ($this->myyheadobj->myysessionvars['userid'] == 1))
		{
			
			if($this->myyheadobj->myysessionvars['userid'] == $this->myyheadobj->HTTP_GET_VARS['userid'])
			{
				$this->myybody['modifyuser'] = '<a href="admin/modifyuser.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['modifyuser']. '</a>';
			}
			if($this->myyheadobj->HTTP_GET_VARS['myy'] != c11)
			{
				if(($this->myyrightrowsnum == 0) && !empty($this->myyheadobj->myygetvars['cat']))
				{
					$this->myybody['delete'] = '<a href="admin/deleteconfirm.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['delete'] . '</a>';
				}
				if(empty($this->myyheadobj->myygetvars['cat']))
				{
					$this->myybody['delete'] = '<a href="admin/deleteconfirm.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['delete'] . '</a>';
				}
			}
			$this->myybody['modify'] = '<a href="admin/modify.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['modify'] . '</a>';
		}
		if(($this->myyheadobj->HTTP_GET_VARS['myy'] != 'c11') && (!empty($this->myyheadobj->myysessionvars['sessionid'])))
		{
			$this->myybody['postarticle'] = '<a href="admin/post.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['postarticle'] . '</a>';
			if($this->myyheadobj->myysessionvars['userid'] == 1)
			{
				$this->myybody['newcat'] = '<a href="admin/post.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&postmode=cat">' . $this->myylang['newcat'] . '</a>';
			}
		}
		if($this->myyheadobj->myysessionvars['userid'] == 1)
		{
			$this->myybody['newcat1'] = '<a href="admin/post.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&postmode=cat&postcat=1">' . $this->myylang['newcat'] . '</a>';
		}
		$this->myybody['searchaction'] = 'index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&mode=search' . '&lang=' . $this->myyheadobj->myygetvars['alllang'];
		$this->myybody['keyword'] = $this->myylang['keyword'];
		$this->myybody['search'] = $this->myylang['search'];
		$this->myybody['searchall'] = $this->myylang['searchall'];
		$this->myybody['urls'] = $this->myylang['urls'];
		$this->myybody['texttitle'] = $this->myytext[0]['title'];
		$this->myybody['text'] = $this->myytext[0]['text'];
		
		//display per right page
		$temploop = 0;
		$rtempval = $this->myyrightrowsnum;
		$rlength = 30;
		for($i = ($this->myyrightrowsnum - 1); $i >= 0; $i--)
		{
			$this->myyrightrows[$i]['rpnum'] = ((($rtempval + ($rlength - 1)) - (($rtempval + ($rlength - 1)) % $rlength)) / $rlength);
			$rtempval--;
			if(!empty($this->myyheadobj->HTTP_GET_VARS['rpnum']))
			{
				$this->allrpnum = $this->myyheadobj->HTTP_GET_VARS['rpnum'];
			}
			else
			{
				if(empty($this->myyheadobj->myygetvars['cat']) && ($this->myyheadobj->myygetvars['id'] == $this->myyrightrows[$i]['id']))
				{
					$this->allrpnum = $this->myyrightrows[$i]['rpnum'];
				}
			}
			if($this->temppagenummax < $this->myyrightrows[$i]['rpnum'])
			{
				$this->temppagenummax = $this->myyrightrows[$i]['rpnum'];
			}
		}
		if($this->allrpnum > $this->temppagenummax)
		{
			$this->myyheadobj->myyredirection($this->myyheadobj->HTTP_GET_VARS['myy']);
		}
		if($this->allrpnum == 1)
		{
			if($this->temppagenummax != 1)
			{
				$this->myyup   = '';
				$this->myydown = '[ <a href="index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->HTTP_GET_VARS['lang'] . '&rpnum=2">' . $this->myylang['nextpage'] . '</a> ]';
			}
			else
			{
				$this->myyup   = '';
				$this->myydown = '';
			}
			
		}
		if(($this->allrpnum > 1) && ($this->allrpnum < $this->temppagenummax))
		{
			$this->myyup   = '[ <a href="index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->HTTP_GET_VARS['lang'] . '&rpnum=' . ($this->allrpnum - 1) . '">' . $this->myylang['previewpage'] . '</a> ]';
			$this->myydown = '[ <a href="index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->HTTP_GET_VARS['lang'] . '&rpnum=' . ($this->allrpnum + 1) . '">' . $this->myylang['nextpage'] . '</a> ]';
		}
		if(($this->allrpnum == $this->temppagenummax) && ($this->temppagenummax != 1))
		{
			$this->myyup   = '[ <a href="index.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->HTTP_GET_VARS['lang'] . '&rpnum=' . ($this->temppagenummax - 1) . '">' . $this->myylang['previewpage'] . '</a> ]';
			$this->myydown = '';
		}
		//display per right page end
	}
	//********************************************************
	function myydisplayfoot()
	{
		$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE cat_id=1 AND in_cat_id=1 ORDER BY text_id";
		if( !($result = $this->myysqlobj->sql_query($sql)) )
		{	
			die("Sorry,can't connect to table.CODE common.php:006");
		}
		$this->myyfootrowsnum = $this->myysqlobj->sql_numrows($result);
		while($this->myyfootrows[] = ($this->myysqlobj->sql_fetchrow($result)));
		
		$this->myyfoot['companyname'] = $this->myylang['companyname'];
		$this->myyfoot['service'] = $this->myylang['service'];
	}
	//********************************************************
	function myygetuserinfo()
	{
		if($this->myyheadobj->HTTP_GET_VARS['mode'] == 'resume')
		{
			$sql = "SELECT * FROM " . $this->myylang['user_table'] . " WHERE user_id=" . $this->myyheadobj->HTTP_GET_VARS['userid'];
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('Sorry,this user id dosn\'t exist.<br>对不起，这个用户不存在。');
			}
			if(($this->myysqlobj->sql_numrows($result)) == 0)
			{	
				die('Sorry,this user id dosn\'t exist.<br>对不起，这个用户不存在。');
			}
			while( $temp[] = $this->myysqlobj->sql_fetchrow($result) ); 	
			
			if($this->myyheadobj->myygetvars['lang'] == 'chinese')
			{
				$this->myyuserdata['username'] = $temp[0]['user_cnname'];
				$this->myyuserdata['resume'] = $temp[0]['resumecn'];
			}
			if($this->myyheadobj->myygetvars['lang'] == 'english')
			{
				$this->myyuserdata['username'] = $temp[0]['user_enname'];
				$this->myyuserdata['resume'] = $temp[0]['resumeen'];
			}
			
			$this->myyuserdata['userid'] = $temp[0]['user_id'];
			$this->myyuserdata['userpass'] = $temp[0]['user_pass'];
			$this->myyuserdata['joindate'] = $temp[0]['join_date'];
			$this->myyuserdata['lastlogdate'] = $temp[0]['lastlog_date'];
		}
	}
	//********************************************************
	function myydisplaysearch()
	{       
		if(isset($this->myyheadobj->HTTP_GET_VARS['start']) && ($this->myyheadobj->HTTP_GET_VARS['start'] < 0) )
		{
			$this->myyheadobj->myyredirection();
		}
		if(($this->myyheadobj->HTTP_GET_VARS['start'] % 15) != 0)
		{
			$this->myyheadobj->myyredirection();
		}
		if(!empty($this->myyheadobj->HTTP_POST_VARS['page']))
		{
			if(!ereg("^[0-9]{1,}$",$this->myyheadobj->HTTP_POST_VARS['page']))
			{
				$this->myyheadobj->myyredirection();
			}
		}
		if(isset($this->myyheadobj->HTTP_GET_VARS['page']) && isset($this->myyheadobj->HTTP_GET_VARS['start']))
		{
			$this->myyheadobj->myyredirection();
		}
		if(!empty($this->myyheadobj->HTTP_POST_VARS['page']))
		{
			$start = (intval($this->myyheadobj->HTTP_POST_VARS['page']) - 1) * 15;
		}
		else
		{
			$this->myyheadobj->HTTP_POST_VARS['page'] = ($this->myyheadobj->HTTP_GET_VARS['start'] / 15) + 1;
			$start = ( isset($this->myyheadobj->HTTP_GET_VARS['start']) ) ? intval($this->myyheadobj->HTTP_GET_VARS['start']) : 0;	
		}
		$keyword1 = ( isset($this->myyheadobj->HTTP_POST_VARS['key']) ) ? strval($this->myyheadobj->HTTP_POST_VARS['key']) : strval($this->myyheadobj->HTTP_GET_VARS['key']);
		$keyword = '%' . strip_tags(trim($keyword1)) . '%';
		$catagory = ( isset($this->myyheadobj->HTTP_POST_VARS['cat'])) ? strval($this->myyheadobj->HTTP_POST_VARS['cat']) : strval($this->myyheadobj->HTTP_GET_VARS['cat']);
		
		if(($keyword == '') || ($catagory == '') || ereg('^[[:punct:]]+$',$keyword))
		{
			$this->myyheadobj->myyredirection($this->myyheadobj->HTTP_GET_VARS['myy']);
		}
		
		$this->myysearchstart = $start;
		
		if($catagory == 'all')
		{
			$sql = "SELECT * 
				FROM " . $this->myylang['text_table'] . "
				WHERE (in_cat_id <> 1 OR cat_id <> 1) 
				AND text LIKE '$keyword'
				ORDER BY text_id";
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('1,Sorry,search cat == all fail.');
			}
			while( $tempsearch[] = $this->myysqlobj->sql_fetchrow($result) );
			
			$j = 0;
			$temp = str_replace("%","",$keyword);
			for($i = 0; $i < count($tempsearch); $i++)
			{
				if(ereg($temp,strip_tags($tempsearch[$i]['text'])))
				{
					$myysearch1[$j] = $tempsearch[$i];
					$j++;
				}
			}
			$tempsearch[0][0] = count($myysearch1);
			
			$j = 0;
			for($i = $start; $i < ($start + 15); $i++)
			{
				if(!empty($myysearch1[$i]))
				{
					$this->myysearch[$j] = $myysearch1[$i];
					$j++;
				}
			}
			$this->myysearchnum = count($this->myysearch);
		}
		else
		{
			$sql = "SELECT COUNT(DISTINCT cat_id) 
				FROM " . $this->myylang['text_table'];
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die('Sorry,search count cat_id fail.');
			}
			while( $temp[] = $this->myysqlobj->sql_fetchrow($result) );
			$k = 0;
			for($i = 0; $i < $temp[0][0]; $i++)
			{
				unset($tempsearch);
				$temptable = " FROM ". $this->myylang['text_table'] . " t," . $this->myylang['cat_table'] . "1 c1";
				$temptext  = " WHERE t.text LIKE '$keyword' AND c1.cat1_id = $catagory AND ";
				$tempand   = "";
				for($j = 1; $j <= $i; $j++)
				{
					$temptable .= ',' . $this->myylang['cat_table'] . ($j + 1) . ' c' . ($j + 1);
					$temptext  .= 'c' . $j .'.cat' . $j . '_id = c' . ($j + 1) . '.cat' . $j . '_id AND ';
				}
				$temptext .= 't.in_cat_id = c' . ($i + 1) . '.cat' . ($i + 1) . '_id AND t.cat_id = ' . ($i + 1);
				$sql = "SELECT t.* " . $temptable . $temptext;
				if( !($result = $this->myysqlobj->sql_query($sql)) )
				{	
					die('1,Sorry,search cat != all fail.');
				}
				while( $tempsearch[] = $this->myysqlobj->sql_fetchrow($result) );
				
				$temp2 = str_replace("%","",$keyword);
				for($j = 0; $j < count($tempsearch); $j++)
				{
					if(ereg($temp2,strip_tags($tempsearch[$j]['text'])))
					{
						$myysearch1[$k] = $tempsearch[$j];
						$k++;
					}
				}
			}
			$tempsearch[0][0] = count($myysearch1);
			
			$j = 0;
			for($i = $start; $i < ($start + 15); $i++)
			{
				if(!empty($myysearch1[$i]))
				{
					$this->myysearch[$j] = $myysearch1[$i];
					$j++;
				}
			}
			$this->myysearchnum = count($this->myysearch);
		}
		
		if($tempsearch[0][0] == 0)
		{
			$this->myybody['display'] = 'none';
		}
		$this->myybody['searchkey'] = '<font color=#ff0000>' . stripslashes($keyword1) . '</font> ' . $this->myylang['searchkey'] . '<font color=#ff0000>' . $tempsearch[0][0] . '</font> ' . $this->myylang['searchresult'];
		if($tempsearch[0][0] <= 15)
		{
			$temppagenum  = 1;
		}
		else
		{
			$temppagenum  = (($tempsearch[0][0] - ($tempsearch[0][0] % 15)) / 15) + 1;
		}
		if($temppagenum == 1)
		{
			$this->myybody['display2'] = 'none';
		}
		$this->myybody['pagenum']   = $this->myylang['pagenum'] . $temppagenum . $this->myylang['resultsnum2'];
		
		$temp = ($start + 15) / 15;
		if($temp > $temppagenum)
		{
			$this->myyheadobj->myyredirection();
		}
		if($temp == $temppagenum)
		{
			if($temp == 1)
			{
				$this->myylang['previewpage'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$this->myylang['nextpage']    = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			else
			{
				$this->myylang['previewpage'] = '<a href="index.php?myy=c11&mode=search&key=' . $keyword1 . '&cat=' . $catagory . '&start=' . ($start - 15) . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">&lt;&lt;' . $this->myylang['previewpage'] . '</a>';
				$this->myylang['nextpage']    = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
		if($temp < $temppagenum)
		{
			if($temp == 1)
			{
				$this->myylang['previewpage'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$this->myylang['nextpage']    = '<a href="index.php?myy=c11&mode=search&key=' . $keyword1 . '&cat=' . $catagory . '&start=' . ($start + 15) . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['nextpage'] . '&gt;&gt;</a>';
			}
			else
			{
				$this->myylang['previewpage'] = '<a href="index.php?myy=c11&mode=search&key=' . $keyword1 . '&cat=' . $catagory . '&start=' . ($start - 15) . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">&lt;&lt;' . $this->myylang['previewpage'] . '</a>';
				$this->myylang['nextpage']    = '<a href="index.php?myy=c11&mode=search&key=' . $keyword1 . '&cat=' . $catagory . '&start=' . ($start + 15) . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['nextpage'] . '&gt;&gt;</a>';
			}
		}
		$this->myybody['resultsnum'] = $this->myylang['resultsnum1'] . $temp . $this->myylang['resultsnum2'];
		$this->myybody['resultsnum'] = $this->myylang['previewpage'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->myybody['resultsnum'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->myylang['nextpage'];
		
		unset($temp);
		
		$this->myybody['jumpaction']      = 'index.php?myy=c11&mode=search&key=' . $keyword1 . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&cat=' . $catagory .'&start=' . $this->myysearchstart;
		$this->myybody['jump']            = $this->myylang['jump'];
		$this->myybody['resultsposition'] = $this->myylang['searchkey'];
		$this->myybody['littlecut']       = $this->myylang['searchkey'];
		$this->myybody['author']          = $this->myylang['writer'];
		$this->myybody['postdate']        = $this->myylang['postdate'];
	}
	//********************************************************
	//返回和目录或图片名字和路径
	function myygetphotos()
	{
		if($this->myyheadobj->HTTP_GET_VARS['dir'] == '')
		{
			$tempd = '../photos';
			$dir = dir($tempd);
		}
		else
		{
			if(!ereg("^/{1}[^.]{0,}(..){0,}$",$this->myyheadobj->HTTP_GET_VARS['dir']))
			{
				$this->myyheadobj->myyredirection($this->myyheadobj->HTTP_GET_VARS['myy']);
			}
			$tempd = '../photos' . $this->myyheadobj->HTTP_GET_VARS['dir'];
			$tempd1 = 'photos' . $this->myyheadobj->HTTP_GET_VARS['dir'];
			$dir = dir($tempd);
		}
		
		
		if(file_exists($tempd . '/detail.txt'))
		{
			$tempdetailfile = file($tempd . '/detail.txt');
			
			$i = 0;
			while($tempp[$i] = $dir->read())
			{
				if(is_dir($tempd . '/' .$tempp[$i]))
				{
					if(($tempp[$i] != '..') && ($tempp[$i] != '.'))
					{
						for($j = 0; $j < count($tempdetailfile); $j++)
						{
							if(ereg(str_replace("s","",$tempp[$i]),$tempdetailfile[$j]))
							{
								$this->myyphotod[$i] = str_replace( str_replace("s","",$tempp[$i]) . "#" ,"",$tempdetailfile[$j]);
							}
						}
						$temp = "/{0,1}[^/]{0,}/..$";
						$this->myyheadobj->HTTP_GET_VARS['dir'] = ereg_replace($temp,"",$this->myyheadobj->HTTP_GET_VARS['dir']);
						$this->myyphoto[$i] = '<a href="photos.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&dir=' . $this->myyheadobj->HTTP_GET_VARS['dir'] . '/' . $tempp[$i] .'">' . $this->myyphotod[$i] . ' &gt;&gt;</a>';
						++$i;
					}
				}
				if(is_file($tempd . '/' .$tempp[$i]))
				{
					$tempp1 = str_replace("s","",substr($tempp[$i],0,-4));
					if(($tempp[$i] != 'detail.txt') && (substr($tempp[$i],0,1) == 's'))
					{
						$tempbigimg = str_replace("s","b",$tempp[$i]);
						if(file_exists($tempd . '/' . $tempbigimg))
						{
							$size = getimagesize ($tempd . '/' . $tempp[$i] );
							$sizen = getimagesize ($tempd . '/' . str_replace("s","",$tempp[$i]));
							$imgurl = '&lt;a&nbsp;href="' . $tempd1 . '/' . $tempbigimg . '"&nbsp;target="_blank"&gt;&lt;img&nbsp;src="' . $tempd1 . '/' . str_replace("s","",$tempp[$i]) . '"&nbsp;' . str_replace(" ","&nbsp;",$sizen[3]) . '&nbsp;border="1"&nbsp;class="imgl"&nbsp;alt="' . str_replace(" ","&nbsp;",$this->myylang['imgalt']) . '"&gt;&lt;/a&gt;';
						}
						else
						{
							$size = getimagesize ($tempd . '/' . $tempp[$i] );
							$sizen = getimagesize ($tempd . '/' . str_replace("s","",$tempp[$i]));
							$imgurl = '&lt;img&nbsp;src="' . $tempd1 . '/' . str_replace("s","",$tempp[$i]) . '"&nbsp;' . str_replace(" ","&nbsp;",$sizen[3]) . '&nbsp;border="1"&nbsp;class="imgl"&gt;';
						}
						$this->myyphotof[$i] = "<a href=javascript:imgurl('$imgurl')><img src='" . $tempd . "/" . $tempp[$i] . "' " . $size[3] . " border=1></a>";
						for($j = 0; $j < count($tempdetailfile); $j++)
						{
							if(ereg($tempp1,$tempdetailfile[$j]))
							{
								$this->myyphotod[$i] = "<a href=javascript:imgurl('$imgurl')>" . str_replace($tempp1 . "#" ,"",$tempdetailfile[$j]) . "</a>";
							}
						}
						++$i;
					}
				}
			}
		}
		else
		{
			echo('No detail text;common.php');
		}
		$dir->close();
			
		if($this->myyheadobj->HTTP_GET_VARS['dir'] != '')
		{
			if(!ereg("^/[^./]{1,}(/..)$",$this->myyheadobj->HTTP_GET_VARS['dir']))
			{
				$this->myybody['firstdir']   = '<a href="photos.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $this->myylang['firstdir'] . '</a>';
				$this->myybody['parentsdir'] = '<a href="photos.php?myy=' . $this->myyheadobj->HTTP_GET_VARS['myy'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '&dir=' . $this->myyheadobj->HTTP_GET_VARS['dir']. '/..' .'">' . $this->myylang['parentsdir'] . '</a>';
			}
		}
	}
	//********************************************************
	//返回和文章或目录相的同一级文章或目录名字和id
	function getposition($cat = '',$id)
	{
		if($cat != '')
		{	
			unset($temp,$temp1,$temp2);
			$sql = "SELECT * FROM " . $this->myylang['cat_table'] . $cat . " WHERE cat" . $cat . "_id=" . $id;
 			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				$this->myyheadobj->myyredirection();
			}
			if(($this->myysqlobj->sql_numrows($result)) == 0)
			{	
				$this->myyheadobj->myyredirection();
			}
			while( $temp[] = $this->myysqlobj->sql_fetchrow($result) );
			$temp1 = 'cat' . $cat . '_name';
			$temp2 = 'descat' . $cat;
			$myytext[0]['title'] = $temp[0][$temp1];
			$myytext[0]['text'] = $temp[0][$temp2];
			
			unset($temp,$temp1,$temp2);
			for($i = 0; $i < $cat; $i++)
			{
				$j = $i + 1;
				if($j != $cat)
				{
					$temp = $temp . ('c' . $j . '.cat' . $j . '_id' . ', c' . $j . '.cat' . $j . '_name' . ', ');
					$temp1 = $temp1 . ($this->myylang['cat_table'] . $j . ' c' . $j . ', ');
					if(($j + 1) < $cat)
					{
						$temp2 = $temp2 . ('c' . $j . '.cat' .$j . '_id = c' . ($j + 1) . '.cat' .$j . '_id' .' AND ');
					}
					if(($j + 1) == $cat)
					{
						$temp2 = $temp2 . ('c' . $j . '.cat' .$j . '_id = c' . ($j + 1) . '.cat' .$j . '_id' . ' AND ' . 'c' . ($j + 1) . '.' . 'cat' . ($j + 1) . '_id = ' . $id);
					}
				}
				else
				{
					$temp = $temp . ('c' . $j . '.cat' . $j . '_id' . ', c' . $j . '.cat' . $j . '_name');
					$temp1 = $temp1 . ($this->myylang['cat_table'] . $j . ' c' . $j);
				}
				if($cat == 1)
				{
					$temp2 = 'c' . $j . '.' . 'cat' . $j . '_id = ' . $id;
				}
			}
			$sql = "SELECT " . $temp . " FROM " . $temp1 . " WHERE " . $temp2;
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't connect to table.CODE common.php:002");
			}
		}
		else
		{
			$sql = "SELECT * FROM " . $this->myylang['text_table'] . " WHERE text_id=" . $id;
 			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't connect to table.CODE common.php:003");
			}
			if(($this->myysqlobj->sql_numrows($result)) == 0)
			{	
				$this->myyheadobj->myyredirection();
			}
			while( $myytext[] = $this->myysqlobj->sql_fetchrow($result) );
						
			unset($temp,$temp1,$temp2);
			for($i = 0; $i < $myytext[0]['cat_id']; $i++)
			{
				$j = $i + 1;
				if($j != $myytext[0]['cat_id'])
				{
					$temp  = $temp  . ('c' . $j . '.cat' . $j . '_id' . ', c' . $j . '.cat' . $j . '_name' . ', ');
					$temp1 = $temp1 . ($this->myylang['cat_table'] . $j . ' c' . $j . ', ');
					$temp2 = $temp2 . ('c' . $j . '.cat' .$j . '_id = c' . ($j + 1) . '.cat' .$j . '_id' .' AND ');
				}
				else
				{
					$temp = $temp . ('c' . $j . '.cat' . $j . '_id' . ', c' . $j . '.cat' . $j . '_name');
					$temp1 = $temp1 . ($this->myylang['cat_table'] . $j . ' c' . $j);
					$temp2 = $temp2 . ('c' . $j . '.cat' . $j . '_id = t.in_cat_id' . ' AND ');
				}
			}
			$temp = $temp . ', t.' . 'text_id,' . ' t.' . 'title';
			$temp1 = $temp1 . ',' . $this->myylang['text_table'] . ' t';
			$temp2 = $temp2 . ('t.text_id = ' . $id);
			$sql = "SELECT " . $temp . " FROM " . $temp1 . " WHERE " . $temp2;
			if( !($result = $this->myysqlobj->sql_query($sql)) )
			{	
				die("Sorry,can't connect to table.CODE common.php:004");
			}
		}
		$myypositionnum        = ($this->myysqlobj->sql_numfields($result));
		while($myyposition[]   = ($this->myysqlobj->sql_fetchrow($result)));
		$all['myytext']        = $myytext;
		$all['myypositionnum'] = $myypositionnum;
		$all['myyposition']    = $myyposition;
		
		return($all);
	}
	//********************************************************
	//显示右边的子目录或文章
	function displaysubname($mode = '',$myyrightrows,$myygetvars)
	{
		
		$temp = $this->allrpnum;
		
		if($myyrightrows['rpnum'] == $temp)
		{
			if($mode == '')
			{
				if($myyrightrows['cat'] != 0)
				{
					$subname = '<a href="index.php?myy=c' . $myyrightrows['cat'] . $myyrightrows['id'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $myyrightrows['name'] . ' >></a>';
				}
				else
				{
					if(($myyrightrows['id'] == $myygetvars['id']) && ($myygetvars['cat'] == ''))
					{
						$subname = "<font color=#ff0000>" . $myyrightrows['name'] . "</font>";
					}
					else
					{
						$subname = '<a href="index.php?myy=' . $myyrightrows['id'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $myyrightrows['name'] . '</a>';
					}
				}
			}
			else
			{
				if($myyrightrows['cat'] != 0)
				{
					$subname = '<a href="index.php?myy=c' . $myyrightrows['cat'] .  $myyrightrows['id'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' . $myyrightrows['name'] . ' >></a>';
				}
				else
				{
					$subname = '<a href="index.php?myy=' . $myyrightrows['id'] . '&lang=' . $this->myyheadobj->myygetvars['alllang'] . '">' .  $myyrightrows['name'] . '</a>';
				}
			}
		}
		
		return 	$subname;
	}
}
//#################################################################################
?>