/*

 $Id: mssql_schema.sql 115 2008-09-08 22:00:21Z lefty74 $

*/

BEGIN TRANSACTION
GO

/*
	Table: 'phpbb_mods_database'
*/
CREATE TABLE [phpbb_mods_database] (
	[mod_id] [int] IDENTITY (1, 1) NOT NULL ,
	[mod_title] [varchar] (255) DEFAULT ('') NOT NULL ,
	[mod_version] [varchar] (10) DEFAULT ('') NOT NULL ,
	[mod_version_type] [varchar] (20) DEFAULT ('') NOT NULL ,
	[mod_desc] [text] DEFAULT ('') NOT NULL ,
	[mod_desc_bbcode_uid] [varchar] (8) DEFAULT ('') NOT NULL ,
	[mod_desc_bbcode_bitfield] [varchar] (255) DEFAULT ('') NOT NULL ,
	[mod_desc_bbcode_options] [int] DEFAULT (7) NOT NULL ,
	[mod_url] [varchar] (100) DEFAULT ('') NOT NULL ,
	[mod_author] [varchar] (50) DEFAULT ('') NOT NULL ,
	[mod_download] [varchar] (255) DEFAULT ('') NOT NULL ,
	[mod_phpbb_version] [varchar] (10) DEFAULT ('') NOT NULL ,
	[mod_comments] [text] DEFAULT ('') NOT NULL ,
	[mod_comments_bbcode_uid] [varchar] (8) DEFAULT ('') NOT NULL ,
	[mod_comments_bbcode_bitfield] [varchar] (255) DEFAULT ('') NOT NULL ,
	[mod_comments_bbcode_options] [int] DEFAULT (7) NOT NULL ,
	[mod_access] [int] DEFAULT (0) NOT NULL ,
	[mod_author_email] [varchar] (100) DEFAULT ('') NOT NULL ,
	[mod_install_date] [int] DEFAULT (0) NOT NULL 
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [phpbb_mods_database] WITH NOCHECK ADD 
	CONSTRAINT [PK_phpbb_mods_database] PRIMARY KEY  CLUSTERED 
	(
		[mod_id]
	)  ON [PRIMARY] 
GO



COMMIT
GO

