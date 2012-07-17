#
# $Id: firebird_schema.sql 115 2008-09-08 22:00:21Z lefty74 $
#


# Table: 'phpbb_mods_database'
CREATE TABLE phpbb_mods_database (
	mod_id INTEGER NOT NULL,
	mod_title VARCHAR(255) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_version VARCHAR(10) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_version_type VARCHAR(20) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_desc BLOB SUB_TYPE TEXT CHARACTER SET UTF8 DEFAULT '' NOT NULL,
	mod_desc_bbcode_uid VARCHAR(8) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_desc_bbcode_bitfield VARCHAR(255) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_desc_bbcode_options INTEGER DEFAULT 7 NOT NULL,
	mod_url VARCHAR(100) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_author VARCHAR(50) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_download VARCHAR(255) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_phpbb_version VARCHAR(10) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_comments BLOB SUB_TYPE TEXT CHARACTER SET UTF8 DEFAULT '' NOT NULL,
	mod_comments_bbcode_uid VARCHAR(8) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_comments_bbcode_bitfield VARCHAR(255) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_comments_bbcode_options INTEGER DEFAULT 7 NOT NULL,
	mod_access INTEGER DEFAULT 0 NOT NULL,
	mod_author_email VARCHAR(100) CHARACTER SET NONE DEFAULT '' NOT NULL,
	mod_install_date INTEGER DEFAULT 0 NOT NULL
);;

ALTER TABLE phpbb_mods_database ADD PRIMARY KEY (mod_id);;


CREATE GENERATOR phpbb_mods_database_gen;;
SET GENERATOR phpbb_mods_database_gen TO 0;;

CREATE TRIGGER t_phpbb_mods_database FOR phpbb_mods_database
BEFORE INSERT
AS
BEGIN
	NEW.mod_id = GEN_ID(phpbb_mods_database_gen, 1);
END;;


