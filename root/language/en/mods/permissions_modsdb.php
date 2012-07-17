<?php
/**
* permissions_modsdb [English]
*
* @package language
* @version $Id: permissions_modsdb.php 137 2008-11-07 22:49:55Z lefty74 $
* @copyright (c) 2008 lefty74
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

// Adding new category
$lang['permission_cat']['modsdb']   = 'Mods Database';

// Adding the permissions
$lang = array_merge($lang, array(
   // User perms
   'acl_u_modsdb_view'      => array('lang' => 'User Can view Mods Database', 'cat' => 'modsdb'),
   
   // Admin perms
   'acl_a_modsdb_view'      => array('lang' => 'Admin Can view Mods Database', 'cat' => 'modsdb'),
));

?>