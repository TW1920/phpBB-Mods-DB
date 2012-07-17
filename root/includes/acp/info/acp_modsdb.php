<?php
/** 
*
* @package acp
* @version $Id: acp_modsdb.php 238 2009-10-28 11:41:52Z lefty74 $ 
* @copyright (c) 2005 phpBB Group, (c) 2007 lefty74 (modified acp_bots.php for Mods Database) 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_modsdb_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_modsdb',
			'title'		=> 'ACP_MODS_DATABASE',
			'version'	=> '1.0.7',
			'modes'		=> array(
			'modsdb'		=> array('title' => 'ACP_MODS_DATABASE', 'auth' => 'acl_a_modsdb_view', 'cat' => array('ACP_DOT_MODS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}


?>