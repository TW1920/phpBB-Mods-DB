/*

 $Id: postgres_schema.sql 115 2008-09-08 22:00:21Z lefty74 $

*/

BEGIN;

/*
	Domain definition
*/
CREATE DOMAIN varchar_ci AS varchar(255) NOT NULL DEFAULT ''::character varying;

/*
	Operation Functions
*/
CREATE FUNCTION _varchar_ci_equal(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) = LOWER($2)' LANGUAGE SQL STRICT;
CREATE FUNCTION _varchar_ci_not_equal(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) != LOWER($2)' LANGUAGE SQL STRICT;
CREATE FUNCTION _varchar_ci_less_than(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) < LOWER($2)' LANGUAGE SQL STRICT;
CREATE FUNCTION _varchar_ci_less_equal(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) <= LOWER($2)' LANGUAGE SQL STRICT;
CREATE FUNCTION _varchar_ci_greater_than(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) > LOWER($2)' LANGUAGE SQL STRICT;
CREATE FUNCTION _varchar_ci_greater_equals(varchar_ci, varchar_ci) RETURNS boolean AS 'SELECT LOWER($1) >= LOWER($2)' LANGUAGE SQL STRICT;

/*
	Operators
*/
CREATE OPERATOR <(
  PROCEDURE = _varchar_ci_less_than,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = >,
  NEGATOR = >=,
  RESTRICT = scalarltsel,
  JOIN = scalarltjoinsel);

CREATE OPERATOR <=(
  PROCEDURE = _varchar_ci_less_equal,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = >=,
  NEGATOR = >,
  RESTRICT = scalarltsel,
  JOIN = scalarltjoinsel);

CREATE OPERATOR >(
  PROCEDURE = _varchar_ci_greater_than,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = <,
  NEGATOR = <=,
  RESTRICT = scalargtsel,
  JOIN = scalargtjoinsel);

CREATE OPERATOR >=(
  PROCEDURE = _varchar_ci_greater_equals,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = <=,
  NEGATOR = <,
  RESTRICT = scalargtsel,
  JOIN = scalargtjoinsel);

CREATE OPERATOR <>(
  PROCEDURE = _varchar_ci_not_equal,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = <>,
  NEGATOR = =,
  RESTRICT = neqsel,
  JOIN = neqjoinsel);

CREATE OPERATOR =(
  PROCEDURE = _varchar_ci_equal,
  LEFTARG = varchar_ci,
  RIGHTARG = varchar_ci,
  COMMUTATOR = =,
  NEGATOR = <>,
  RESTRICT = eqsel,
  JOIN = eqjoinsel,
  HASHES,
  MERGES,
  SORT1= <);

/*
	Table: 'phpbb_mods_database'
*/
CREATE SEQUENCE phpbb_mods_database_seq;

CREATE TABLE phpbb_mods_database (
	mod_id INT4 DEFAULT nextval('phpbb_mods_database_seq'),
	mod_title varchar(255) DEFAULT '' NOT NULL,
	mod_version varchar(10) DEFAULT '' NOT NULL,
	mod_version_type varchar(20) DEFAULT '' NOT NULL,
	mod_desc TEXT DEFAULT '' NOT NULL,
	mod_desc_bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	mod_desc_bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	mod_desc_bbcode_options INT4 DEFAULT '7' NOT NULL CHECK (mod_desc_bbcode_options >= 0),
	mod_url varchar(100) DEFAULT '' NOT NULL,
	mod_author varchar(50) DEFAULT '' NOT NULL,
	mod_download varchar(255) DEFAULT '' NOT NULL,
	mod_phpbb_version varchar(10) DEFAULT '' NOT NULL,
	mod_comments TEXT DEFAULT '' NOT NULL,
	mod_comments_bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	mod_comments_bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	mod_comments_bbcode_options INT4 DEFAULT '7' NOT NULL CHECK (mod_comments_bbcode_options >= 0),
	mod_access INT2 DEFAULT '0' NOT NULL CHECK (mod_access >= 0),
	mod_author_email varchar(100) DEFAULT '' NOT NULL,
	mod_install_date INT4 DEFAULT '0' NOT NULL CHECK (mod_install_date >= 0),
	PRIMARY KEY (mod_id)
);



COMMIT;