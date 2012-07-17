<?php
/**
*
* Mods Database [English]
*
* @package language
* @version $Id: info_acp_modsdb.php 169 2008-12-15 17:07:34Z lefty74 $
* @copyright (c) 2005 phpBB Group
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

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine


$lang = array_merge($lang, array(
	'ACP_MODS_DATABASE'			=> 'Mods Database',
	'LOG_MOD_DELETE'			=> '<strong>Deleted Mod(s) in Mods Database</strong><br />» %s',
	'LOG_MOD_ADDED'				=> '<strong>Added Mod in Mods Database</strong><br />» %s',
	'LOG_MOD_UPDATED'			=> '<strong>Updated Mod in Mods Database</strong><br />» %s',
));
?>