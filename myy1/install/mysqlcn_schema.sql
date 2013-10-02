# 蒙医药信息网
# $Id: mysql_schema.sql,v 0.1 2003/02/10 brgd $
# mysql3.23以上

#2003/02/10 tested
CREATE TABLE myy_cat1 (
   cat1_id  tinyint(3) UNSIGNED NOT NULL,
   cat1_name varchar(255) NOT NULL,
   descat1  text NOT NULL,
   est_date date NOT NULL,
   mod_date date NOT NULL,
   mod_userid smallint(5) UNSIGNED,
   PRIMARY KEY (cat1_id)
);

#2003/02/10 tested
CREATE TABLE myy_cat2 (
   cat2_id  tinyint(3) UNSIGNED NOT NULL,
   cat2_name varchar(255) NOT NULL,
   cat1_id  tinyint(3) UNSIGNED NOT NULL,
   descat2  text,
   est_date date NOT NULL,
   mod_date date NOT NULL,
   mod_userid smallint(5) UNSIGNED,
   PRIMARY KEY (cat2_id)
);

#2003/02/10 tested
CREATE TABLE myy_text (
   text_id    mediumint(8) UNSIGNED NOT NULL,
   cat_id     tinyint(3) UNSIGNED NOT NULL,
   in_cat_id  tinyint(3) UNSIGNED NOT NULL,
   title      varchar(100) NOT NULL,
   text       text,
   user_id    smallint(5) UNSIGNED NOT NULL,
   post_date  date NOT NULL,
   PRIMARY KEY (text_id)
);
 
#2003/02/10 tested 
CREATE TABLE myy_user (
   user_id      smallint(5) UNSIGNED NOT NULL,    
   user_cnname    varchar(25) NOT NULL,
   user_enname    varchar(25) NOT NULL,
   user_pass    varchar(32) NOT NULL,
   join_date    date NOT NULL,
   lastlog_date date NOT NULL,
   resumecn       text,
   resumeen       text,
   PRIMARY KEY (user_id)
);

#2003/02/10 tested 
CREATE TABLE myy_sessions (
   session_id char(32) DEFAULT '' NOT NULL,
   session_user_id mediumint(8) DEFAULT '0' NOT NULL,
   session_start int(11) DEFAULT '0' NOT NULL,
   session_ip char(8) DEFAULT '0' NOT NULL,
   session_logged_in tinyint(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (session_id),
   KEY session_user_id (session_user_id),
   KEY session_id_ip_user_id (session_id, session_ip, session_user_id)
);
#

#2003/03/28 tested 
CREATE TABLE myy_urls (
	url_id    smallint(5) UNSIGNED NOT NULL,
	url_name  char(32)    NOT NULL,
	url_url   char(32)    NOT NULL,
	PRIMARY KEY(url_id)
);

#initionalize
INSERT INTO myy_cat1 (
	cat1_id,cat1_name,descat1,est_date,mod_date,mod_userid)
	VALUES(
	1,'首页','首页',CURDATE(),CURDATE(),1);

INSERT INTO myy_user (
	user_id,user_cnname,user_enname,user_pass,join_date,lastlog_date,resumecn,resumeen)
	VALUES(
	1,'博爱民族康复医药研发中心','Inner Mongolia','BRGDnm114.net',CURDATE(),CURDATE(),'内蒙古博爱民族康复医药技术研究开发中心','Inner Mongolia');

INSERT INTO myy_text (
	text_id,cat_id,in_cat_id,title,text,user_id,post_date )
	VALUES(
	1,1,1,'关于我们','关于我们',1,CURDATE());



