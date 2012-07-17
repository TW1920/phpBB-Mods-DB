#
# $Id: mysql_40_schema.sql 115 2008-09-08 22:00:21Z lefty74 $
#

# Table: 'phpbb_mods_database'
CREATE TABLE phpbb_mods_database (
	mod_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	mod_title varbinary(255) DEFAULT '' NOT NULL,
	mod_version varbinary(10) DEFAULT '' NOT NULL,
	mod_version_type varbinary(20) DEFAULT '' NOT NULL,
	mod_desc mediumblob NOT NULL,
	mod_desc_bbcode_uid varbinary(8) DEFAULT '' NOT NULL,
	mod_desc_bbcode_bitfield varbinary(255) DEFAULT '' NOT NULL,
	mod_desc_bbcode_options int(11) UNSIGNED DEFAULT '7' NOT NULL,
	mod_url varbinary(100) DEFAULT '' NOT NULL,
	mod_author varbinary(50) DEFAULT '' NOT NULL,
	mod_download varbinary(255) DEFAULT '' NOT NULL,
	mod_phpbb_version varbinary(10) DEFAULT '' NOT NULL,
	mod_comments mediumblob NOT NULL,
	mod_comments_bbcode_uid varbinary(8) DEFAULT '' NOT NULL,
	mod_comments_bbcode_bitfield varbinary(255) DEFAULT '' NOT NULL,
	mod_comments_bbcode_options int(11) UNSIGNED DEFAULT '7' NOT NULL,
	mod_access tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	mod_author_email varbinary(100) DEFAULT '' NOT NULL,
	mod_install_date int(11) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (mod_id)
);


