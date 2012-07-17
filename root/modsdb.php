<?php
/**
*
* @package phpBB3
* @version $Id: modsdb.php 248 2009-11-22 11:34:16Z lefty74 $
* @copyright (c) 2007,2008 lefty74
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/modsdb'));

$is_authorised = ($auth->acl_get('u_modsdb_view') || $auth->acl_get('a_modsdb_view')) ? true : false;

if (!$is_authorised)
{
trigger_error('NOT_AUTHORISED');
}

// Grab data
$mode		= request_var('mode', '');
$action		= request_var('action', '');
$mod_id		= request_var('m', 0);

// Check our mode...
if (!in_array($mode, array('', 'moddetail')))
{
	trigger_error('NO_MODE');
}
$start	= request_var('start', 0);
$submit = (isset($_POST['submit'])) ? true : false;

$default_key = 'a';
$sort_key = request_var('sk', $default_key);
$sort_dir = request_var('sd', 'a');

switch ($mode)
{
	case 'moddetail':
		$page_title = $user->lang['MODS_DATABASE_DETAIL'];
		$template_html = 'modsdb_body_detail.html';

		$mod_id = (int) $mod_id;
		
		$sql = 'SELECT *
			FROM ' . MODS_DATABASE_TABLE . '
			WHERE mod_id = ' . $mod_id;
				
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			// Lets check if this mod is tagged for Admin eyes only
			if ( $row['mod_access'] && !$auth->acl_get('a_') )
			{
				trigger_error('NOT_AUTHORISED');
			}

			$template->assign_vars(array(
				'MOD_TITLE'			=> $row['mod_title'],
				'MOD_VERSION'		=> $row['mod_version'],
				'MOD_VERSION_TYPE'	=> $row['mod_version_type'],
				'MOD_DESC'			=> generate_text_for_display($row['mod_desc'], $row['mod_desc_bbcode_uid'], $row['mod_desc_bbcode_bitfield'], $row['mod_desc_bbcode_options']),
				'MOD_AUTHOR'		=> $row['mod_author'],
				'MOD_AUTHOR_EMAIL'	=> $auth->acl_get('a_modsdb_view') ? $row['mod_author_email'] : false,
				'WWW_IMG' 			=> $user->img('icon_contact_www', $user->lang['WWW']),
				'DOWNLOAD_IMG' 		=> $user->img('icon_download', $user->lang['DOWNLOAD']),
				'MOD_URL'			=> $row['mod_url'],
				'MOD_PHPBB_VERSION'	=> $row['mod_phpbb_version'],
				'MOD_COMMENTS'		=> generate_text_for_display($row['mod_comments'], $row['mod_comments_bbcode_uid'], $row['mod_comments_bbcode_bitfield'], $row['mod_comments_bbcode_options']),
				'MOD_DOWNLOAD_URL'	=> $row['mod_download'],
				'MOD_INSTALL_DATE'	=> $row['mod_install_date'] ? $user->format_date($row['mod_install_date'],'d F Y') : '',
				
				'S_MOD_IS_AUTHORISED_ADM' 	=> $auth->acl_get('a_modsdb_view') ? true : false

			));

		}
		else
		{
			trigger_error('NO_MOD');
		}
		$db->sql_freeresult($result);


	break;

	default:

		$page_title = $user->lang['MODS_DATABASE'];
		$template_html = 'modsdb_body.html';

		// Sorting
		$sort_key_sql = array('a' => 'mod_title', 'b' => 'mod_version', 'c' => 'mod_author', 'd' => 'mod_install_date');

		$order_by = '';

		// Sorting and order
		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_key;
		}

		$order_by .= 'LOWER(' . $sort_key_sql[$sort_key] . ') ' . (($sort_dir == 'a') ? 'ASC' : 'DESC');

		$where = (!$auth->acl_get('a_')) ? 'WHERE mod_access = ' . MODSDB_VISIBLE_TO_ALL : ''; 
		
		// Count the  MODs ...
		$sql = 'SELECT COUNT(mod_id) AS total_mods
			FROM ' . MODS_DATABASE_TABLE . " 
			$where";
		$result = $db->sql_query($sql);
		$total_mods = (int) $db->sql_fetchfield('total_mods');
		$db->sql_freeresult($result);
	
	
		$i = 0;
		// Get us some MODS :D
		$sql = 'SELECT *
			FROM ' . MODS_DATABASE_TABLE . " 
			$where
			ORDER BY $order_by";
		$result = $db->sql_query_limit($sql, $config['topics_per_page'], $start);

		while ($row = $db->sql_fetchrow($result))
		{
			$mod_id = (int) $row['mod_id'];

			$template->assign_block_vars('modsrow', array(
				'ROW_NUMBER'		=> $i++,
				'MOD_TITLE'			=> $row['mod_title'],
				'MOD_VERSION'		=> $row['mod_version'],
				'MOD_VERSION_TYPE'	=> $row['mod_version_type'],
				'MOD_DESC'			=> generate_text_for_display($row['mod_desc'], $row['mod_desc_bbcode_uid'], $row['mod_desc_bbcode_bitfield'], $row['mod_desc_bbcode_options']),
				'MOD_AUTHOR_EMAIL'	=> $auth->acl_get('a_modsdb_view') ? $row['mod_author_email'] : false,
				'MOD_AUTHOR'		=> $row['mod_author'],
				'MOD_INSTALL_DATE'	=> $row['mod_install_date'] ? $user->format_date($row['mod_install_date'],'d M Y') : false,
				'WWW_IMG' 			=> $user->img('icon_contact_www', $user->lang['WWW']),
				'DOWNLOAD_IMG' 		=> $user->img('icon_download', $user->lang['WWW']),
				'MOD_URL'			=> $row['mod_url'],
				'MOD_DETAIL'		=> append_sid("{$phpbb_root_path}modsdb.$phpEx", "mode=moddetail&amp;m={$mod_id}"),
				'MOD_DOWNLOAD_URL'	=> $row['mod_download'],
				'MOD_PHPBB_VERSION'	=> $row['mod_phpbb_version'],
				'MOD_COMMENTS'		=> $row['mod_comments'],
				'MOD_ACCESS'		=> $row['mod_access'])
				
			);
		
			
		}
			$db->sql_freeresult($result);

		// Build a relevant pagination_url
		$pagination_url = append_sid("{$phpbb_root_path}modsdb.$phpEx", 'sk=' . $sort_key . '&amp;sd='. $sort_dir);
			
		// Generate page
		$template->assign_vars(array(
			'PAGINATION'	=> generate_pagination($pagination_url, $total_mods, $config['topics_per_page'], $start),
			'PAGE_NUMBER'	=> on_page($total_mods, $config['topics_per_page'], $start),
			'TOTAL_MODS'	=> ($total_mods == 1) ? $user->lang['LIST_MOD'] : sprintf($user->lang['LIST_MODS'], $total_mods),

			'U_SORT_MOD_TITLE'		=> append_sid("{$phpbb_root_path}modsdb.$phpEx", 'sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'a') ? 'd' : 'a')),
			'U_SORT_MOD_VERSION'	=> append_sid("{$phpbb_root_path}modsdb.$phpEx", 'sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a')),
			'U_SORT_MOD_AUTHOR'		=> append_sid("{$phpbb_root_path}modsdb.$phpEx", 'sk=c&amp;sd=' . (($sort_key == 'c' && $sort_dir == 'a') ? 'd' : 'a')),

			'U_SORT_MOD_INSTALL_DATE'	=> append_sid("{$phpbb_root_path}modsdb.$phpEx", 'sk=d&amp;sd=' . (($sort_key == 'd' && $sort_dir == 'a') ? 'd' : 'a')),

			'S_MOD_IS_AUTHORISED_ADM' 	=> $auth->acl_get('a_modsdb_view') ? true : false,
			'S_MODE_ACTION'				=> $pagination_url)
		);
}

// Output the page
page_header($page_title);

$template->set_filenames(array(
	'body' => $template_html)
);
make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));

page_footer();
?>