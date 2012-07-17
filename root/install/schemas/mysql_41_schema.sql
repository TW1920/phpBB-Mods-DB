#
# $Id: mysql_41_schema.sql 115 2008-09-08 22:00:21Z lefty74 $
#

# Table: 'phpbb_mods_database'
CREATE TABLE phpbb_mods_database (
	mod_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	mod_title varchar(255) DEFAULT '' NOT NULL,
	mod_version varchar(10) DEFAULT '' NOT NULL,
	mod_version_type varchar(20) DEFAULT '' NOT NULL,
	mod_desc mediumtext NOT NULL,
	mod_desc_bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	mod_desc_bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	mod_desc_bbcode_options int(11) UNSIGNED DEFAULT '7' NOT NULL,
	mod_url varchar(100) DEFAULT '' NOT NULL,
	mod_author varchar(50) DEFAULT '' NOT NULL,
	mod_download varchar(255) DEFAULT '' NOT NULL,
	mod_phpbb_version varchar(10) DEFAULT '' NOT NULL,
	mod_comments mediumtext NOT NULL,
	mod_comments_bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	mod_comments_bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	mod_comments_bbcode_options int(11) UNSIGNED DEFAULT '7' NOT NULL,
	mod_access tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	mod_author_email varchar(100) DEFAULT '' NOT NULL,
	mod_install_date int(11) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (mod_id)
) CHARACTER SET `utf8` COLLATE `utf8_bin`;


