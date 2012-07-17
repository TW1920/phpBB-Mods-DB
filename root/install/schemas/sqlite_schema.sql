#
# $Id: sqlite_schema.sql 115 2008-09-08 22:00:21Z lefty74 $
#

BEGIN TRANSACTION;

# Table: 'phpbb_mods_database'
CREATE TABLE phpbb_mods_database (
	mod_id INTEGER PRIMARY KEY NOT NULL ,
	mod_title varchar(255) NOT NULL DEFAULT '',
	mod_version varchar(10) NOT NULL DEFAULT '',
	mod_version_type varchar(20) NOT NULL DEFAULT '',
	mod_desc mediumtext(16777215) NOT NULL DEFAULT '',
	mod_desc_bbcode_uid varchar(8) NOT NULL DEFAULT '',
	mod_desc_bbcode_bitfield varchar(255) NOT NULL DEFAULT '',
	mod_desc_bbcode_options INTEGER UNSIGNED NOT NULL DEFAULT '7',
	mod_url varchar(100) NOT NULL DEFAULT '',
	mod_author varchar(50) NOT NULL DEFAULT '',
	mod_download varchar(255) NOT NULL DEFAULT '',
	mod_phpbb_version varchar(10) NOT NULL DEFAULT '',
	mod_comments mediumtext(16777215) NOT NULL DEFAULT '',
	mod_comments_bbcode_uid varchar(8) NOT NULL DEFAULT '',
	mod_comments_bbcode_bitfield varchar(255) NOT NULL DEFAULT '',
	mod_comments_bbcode_options INTEGER UNSIGNED NOT NULL DEFAULT '7',
	mod_access INTEGER UNSIGNED NOT NULL DEFAULT '0',
	mod_author_email varchar(100) NOT NULL DEFAULT '',
	mod_install_date INTEGER UNSIGNED NOT NULL DEFAULT '0'
);



COMMIT;