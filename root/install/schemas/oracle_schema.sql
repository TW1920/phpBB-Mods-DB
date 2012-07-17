/*

 $Id: oracle_schema.sql 115 2008-09-08 22:00:21Z lefty74 $

*/

/*
  This first section is optional, however its probably the best method
  of running phpBB on Oracle. If you already have a tablespace and user created
  for phpBB you can leave this section commented out!

  The first set of statements create a phpBB tablespace and a phpBB user,
  make sure you change the password of the phpBB user before you run this script!!
*/

/*
CREATE TABLESPACE "PHPBB"
	LOGGING
	DATAFILE 'E:\ORACLE\ORADATA\LOCAL\PHPBB.ora'
	SIZE 10M
	AUTOEXTEND ON NEXT 10M
	MAXSIZE 100M;

CREATE USER "PHPBB"
	PROFILE "DEFAULT"
	IDENTIFIED BY "phpbb_password"
	DEFAULT TABLESPACE "PHPBB"
	QUOTA UNLIMITED ON "PHPBB"
	ACCOUNT UNLOCK;

GRANT ANALYZE ANY TO "PHPBB";
GRANT CREATE SEQUENCE TO "PHPBB";
GRANT CREATE SESSION TO "PHPBB";
GRANT CREATE TABLE TO "PHPBB";
GRANT CREATE TRIGGER TO "PHPBB";
GRANT CREATE VIEW TO "PHPBB";
GRANT "CONNECT" TO "PHPBB";

COMMIT;
DISCONNECT;

CONNECT phpbb/phpbb_password;
*/
/*
	Table: 'phpbb_mods_database'
*/
CREATE TABLE phpbb_mods_database (
	mod_id number(8) NOT NULL,
	mod_title varchar2(255) DEFAULT '' ,
	mod_version varchar2(10) DEFAULT '' ,
	mod_version_type varchar2(20) DEFAULT '' ,
	mod_desc clob DEFAULT '' ,
	mod_desc_bbcode_uid varchar2(8) DEFAULT '' ,
	mod_desc_bbcode_bitfield varchar2(255) DEFAULT '' ,
	mod_desc_bbcode_options number(11) DEFAULT '7' NOT NULL,
	mod_url varchar2(100) DEFAULT '' ,
	mod_author varchar2(50) DEFAULT '' ,
	mod_download varchar2(255) DEFAULT '' ,
	mod_phpbb_version varchar2(10) DEFAULT '' ,
	mod_comments clob DEFAULT '' ,
	mod_comments_bbcode_uid varchar2(8) DEFAULT '' ,
	mod_comments_bbcode_bitfield varchar2(255) DEFAULT '' ,
	mod_comments_bbcode_options number(11) DEFAULT '7' NOT NULL,
	mod_access number(1) DEFAULT '0' NOT NULL,
	mod_author_email varchar2(100) DEFAULT '' ,
	mod_install_date number(11) DEFAULT '0' NOT NULL,
	CONSTRAINT pk_phpbb_mods_database PRIMARY KEY (mod_id)
)
/


CREATE SEQUENCE phpbb_mods_database_seq
/

CREATE OR REPLACE TRIGGER t_phpbb_mods_database
BEFORE INSERT ON phpbb_mods_database
FOR EACH ROW WHEN (
	new.mod_id IS NULL OR new.mod_id = 0
)
BEGIN
	SELECT phpbb_mods_database_seq.nextval
	INTO :new.mod_id
	FROM dual;
END;
/


