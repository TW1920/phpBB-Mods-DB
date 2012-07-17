<?php
/** 
*
* acp_modsdb [English]
*
* @package language
* @version $Id: acp_modsdb.php 172 2008-12-30 22:47:58Z lefty74 $ 
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
	'MODS_DATABASE'				=> 	'Manage Mods Database',
	'MODS_DATABASE_EXPLAIN'		=> 	'You can maintain your Mods Database here. Add, Edit or Delete Mods to and from the database. Via the permissions tab you can choose who can view the Mods Database.',
	'MODS_SHOW'					=> 	'Mods Database Display',
	'MODS_SHOW_EXPLAIN'			=> 	'Show Mod Database to:',
	'MOD_ADD'					=> 	'Add Mod',
	'MOD_ADDED'					=> 	'New Mod successfully added.',
	'MOD_COMMENTS'				=>	'Comments',
	'MOD_COMMENTS_EXPLAIN'		=>	'Here you can add additional comments like own customisations to the mod, etc.',
	'MOD_DELETED'				=> 	'Mod(s) successfully deleted.',
	'MOD_EDIT'					=> 	'Edit Mod',
	'MOD_ADD_INFO'				=> 	'Additional Information',
	'MOD_EDIT_EXPLAIN'			=> 	'Here you can add or edit an existing Mod entry. The Title and version number are required. You will also be able to enter details of where the Mod can be downloaded from and where the Mod itself can be found.',

	'MOD_INSTALL_DATE'			=> 	'Installation Date',
	'MOD_INSTALL_DATE_EXPLAIN'	=> 	'Date you installed this Mod (Note: partial completion of the date fields will not be stored)',

	'MOD_NAME_TAKEN'			=> 	'The Title is already in use in the Mods Database and can’t be used again.',
	'MOD_UPDATED'				=> 	'Existing Mod updated successfully.',
	'MOD_PHPBB_VERSION'			=>	'phpbb Version',
	'MOD_PHPBB_VERSION_EXPLAIN'	=>	'Enter the phpbb version this mod is written for e.g. 3.0.4',

	'ERR_MOD_NO_MATCHES'		=> 	'You must supply at least the Mod Title and Mod Version for this Mod entry.',

	'NO_MOD'					=> 	'Found no Mod with the specified ID.',
	'NO_MODS_ADDED'				=> 	'There are no Mods currently in the Database.',
	
	'MOD_TITLE'					=>	'Mod Title',
	'MOD_VERSION'				=>	'Version',
	'MOD_VERSION_TYPE'			=>	'Version Type',
	'MOD_VERSION_TYPE_EXPLAIN'	=>	'Beta, Alpha, Dev or RC*',
	'MOD_DESC'					=>	'Description',
	'MOD_AUTHOR'				=>	'Author',
	'MOD_AUTHOR_EMAIL'			=>	'Author’s email',
	'MOD_AUTHOR_EMAIL_EXPLAIN'	=>	'Include the author’s email if available (makes the author’s name mailto: clickable)',
	'MOD_URL'					=>	'URL',
	'MOD_DOWNLOAD'				=>	'Download',
	'MOD_ACCESS'				=>	'Show mod only to Admins',
	'VISIBLE_TO_ADMIN'			=>  'This MOD entry is only visible to an admin',		
));

?>