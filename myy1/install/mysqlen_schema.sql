# 蒙医药信息网
# $Id: mysql_schema.sql,v 0.1 2003/02/10 brgd $
# mysql3.23以上

#2003/02/10 tested
CREATE TABLE myye_cat1 (
   cat1_id  tinyint(3) UNSIGNED NOT NULL,
   cat1_name varchar(255) NOT NULL,
   descat1  text NOT NULL,
   est_date date NOT NULL,
   mod_date date NOT NULL,
   mod_userid smallint(5) UNSIGNED,
   PRIMARY KEY (cat1_id)
);

#2003/02/10 tested
CREATE TABLE myye_cat2 (
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
CREATE TABLE myye_text (
   text_id    mediumint(8) UNSIGNED NOT NULL,
   cat_id     tinyint(3) UNSIGNED NOT NULL,
   in_cat_id  tinyint(3) UNSIGNED NOT NULL,
   title      varchar(100) NOT NULL,
   text       text,
   user_id    smallint(5) UNSIGNED NOT NULL,
   post_date  date NOT NULL,
   PRIMARY KEY (text_id)
);
 
#2003/03/28 tested 
CREATE TABLE myye_urls (
	url_id    smallint(5) UNSIGNED NOT NULL,
	url_name  char(32)    NOT NULL,
	url_url   char(32)    NOT NULL,
	PRIMARY KEY(url_id)
);

#initionalize
INSERT INTO myye_cat1 (
	cat1_id,cat1_name,descat1,est_date,mod_date,mod_userid)
	VALUES(
	1,'home','home',CURDATE(),CURDATE(),1);
	
INSERT INTO myye_text (
	text_id,cat_id,in_cat_id,title,text,user_id,post_date )
	VALUES(
	1,1,1,'about us','about us',1,CURDATE());