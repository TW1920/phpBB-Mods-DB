<?php
/** 
*
* acp_modsdb [English]
*
* @package language
* @version $Id: modsdb.php 242 2009-11-01 20:40:53Z lefty74 $ 
* @copyright (c) 2007 lefty74 
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

// Bot settings
$lang = array_merge($lang, array(
	'MODS_DATABASE_DETAIL'		=> 'Mods Database - Mod Detail',
	'MODS_DATABASE'				=> 'Mods Database',

	'MOD_DETAIL'				=> 'Mod Details',
	'NO_MOD'					=> 	'Found no Mod with the specified ID.',
	'NO_MODS_ADDED'				=> 	'There are no Mods currently in the Database.',
	'MOD_INSTALL_DATE'			=>	'Mod Installation Date',
	'MOD_INSTALL_DATE_SHORT'	=>	'Install Date',
	'MOD_TITLE'					=>	'Mod Title',
	'MOD_COMMENTS'				=>	'Comments',
	'MOD_PHPBB_VERSION'			=>	'phpbb Version',
	'MOD_VERSION'				=>	'Version',
	'MOD_VERSION_TYPE'			=>	'Version Type',
	'MOD_DESC'					=>	'Description',
	'MOD_AUTHOR'				=>	'Author',
	'MOD_URL'					=>	'Location',
	'VISIT_WEBSITE'				=>	'URL Link where Mod is published',
	'DOWNLOAD_MOD'				=>	'URL Link where Mod can be downloaded',
	'LIST_MOD'					=>  '1 Mod installed',
	'LIST_MODS'					=>  '%s Mods installed',
	'WWW'						=>  'Website',
	'DOWNLOAD'					=>  'Download',
	'ADMIN_SIGN'				=>  'A',
	'VISIBLE_TO_ADMIN'			=>  'This MOD entry is only visible to an admin',
	
	// Installation file stuff, not needed anymore after installation is complete
	'MODDB_PERM_CREATED'		=> 	'Mods Database Permissions created.',
	'MODDB_TABLE_CREATED'		=> 	'Mods Database Table Created.',
	'MODDB_MODULE_ADDED'		=> 	'Mods Database Module has been added.',
	'MODDB_INSTALL_COMPLETE'	=> 	'<strong>Mods Database installation complete. Please delete this folder (/install)!!</strong>',
	'MODDB_INSTALL_RETURN'		=> 	'<br /><br /><br />Click %shere%s to return to the board index.',
	'MODDB_INSTALL_REDIRECT'	=> 	'Please wait while you are being redirected to complete the installation.',
	'MODDB_UNINSTALL_REDIRECT'	=> 	'Please wait while you are being redirected to complete the deletion.',

	'MODDB_PREV_MODS_SAVE'		=>	'Mods already in database stored   <br />',
	'MODDB_PREV_TABLE_DELETE'	=>	'Mod Database table deleted   <br />',
	'MODDB_PREV_TABLE_POP'		=>	'Mods Database Table created and populated with stored mods data entries <br/>',
	'MODDB_MODULE_READDED'		=> 	'Mods Database Module has been re-added.',

	'MODDB_TABLE_CONFIG_DELETE'	=> 	'Mod Database table and config fields deleted   <br />',
	'MODDB_MODULE_DELETED'		=> 	'Mods Database Module has been deleted   <br />',
	'MODDB_DELETE_COMPLETE'		=> 	'<strong>Mods Database deletion complete. Please delete this folder (/install)!!	</strong>',
	'MODDB_BACKUP_WARN'			=> 	'Make sure you have backed up your database before proceeding!!!',
	'MODDB_INSTALL_DESC'		=> 	'This installation file will create the Database table/fields and add the appropriate module. <br />To proceed please click on the appropriate action below:',
	'MODDB_UPGRADE_DESC'		=> 	'This installation file will upgrade/delete the Database table/fields and add/remove the appropriate module. <br />To proceed please click on the appropriate action below:',
	
	'MODDB_NEW_INSTALL'			=> 	'New Installation',
	'MODDB_UP_TO_DATE'			=> 	'Version %s is currently installed on your system which is the latest up-to-date version',
	'MODDB_UPGRADE'				=> 	'Upgrade to %s',
	'MODDB_UNINSTALL'			=> 	'Uninstall',

																			
	'MODDB_DESCRIPTION' 		=>	'Adds a simple Mods Database for keeping track of the mods installed on the board with possibility to add various additional information.
Mod detail page to view the additional information, admins with appropriate permissions  will be able to see installation date on mod detail page.',
	
	
));

?>