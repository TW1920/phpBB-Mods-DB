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
* @package acp
*/
class acp_modsdb
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $db, $user, $auth, $template, $cache;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$action = request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;
		$mark	= request_var('mark', array(0));
		$mod_id	= request_var('id', 0);

		if (isset($_POST['add']))
		{
			$action = 'add';
		}

		$default_key = 'a';
		$sort_key = request_var('sk', $default_key);
		$sort_dir = request_var('sd', 'a');
	
		$error = array();

		$user->add_lang('mods/acp_modsdb');
		$this->tpl_name = 'acp_modsdb';
		$this->page_title = 'ACP_MODS_DATABASE';
		add_form_key('acp_modsdb');

		if ($submit && !check_form_key('acp_modsdb'))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}
		$mod_id = (int) $mod_id;
		// User wants to do something, how inconsiderate of them!
		switch ($action)
		{
			case 'delete':
				if ($mod_id || sizeof($mark))
				{
					if (confirm_box(true))
					{
						// We need to delete the relevant mod entries ...
						$sql = 'SELECT mod_title, mod_id 
							FROM ' . MODS_DATABASE_TABLE . ' 
							WHERE ' . $db->sql_in_set('mod_id', ($mod_id) ? array($mod_id) : $mark);
						$result = $db->sql_query($sql);

						$mod_title_ary = array();
						while ($row = $db->sql_fetchrow($result))
						{
							$mod_title_ary[] = (string) $row['mod_title'];
						}
						$db->sql_freeresult($result);

						$sql = 'DELETE FROM ' . MODS_DATABASE_TABLE . ' 
							WHERE ' . $db->sql_in_set('mod_id', ($mod_id) ? array($mod_id) : $mark);
						$db->sql_query($sql);

						add_log('admin', 'LOG_MOD_DELETE', implode(', ', $mod_title_ary));
						trigger_error($user->lang['MOD_DELETED'] . adm_back_link($this->u_action));
					}
					else
					{
						confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
							'mark'		=> $mark,
							'id'		=> $mod_id,
							'mode'		=> $mode,
							'action'	=> $action))
						);
					}
				}
			break;

			case 'edit':
			case 'add':

				$mod_row = array(
					'mod_title'			=> utf8_normalize_nfc(request_var('mod_title', '', true)),
					'mod_version'		=> request_var('mod_version', ''),
					'mod_version_type'	=> request_var('mod_version_type', ''),
					'mod_desc'			=> utf8_normalize_nfc(request_var('mod_desc', '', true)),
					'mod_author'		=> utf8_normalize_nfc(request_var('mod_author', '', true)),
					'mod_author_email'	=> utf8_normalize_nfc(strtolower(request_var('mod_author_email', '', true))),
					'mod_url'			=> utf8_normalize_nfc(request_var('mod_url', '', true)),
					'mod_download'		=> utf8_normalize_nfc(request_var('mod_download' , '', true)),
					'mod_phpbb_version'	=> request_var('mod_phpbb_version' , ''),
					'mod_comments'		=> utf8_normalize_nfc(request_var('mod_comments' , '', true)),
					'mod_access'		=> request_var('mod_access', 0),
					'mod_install_day'	=> request_var('mod_install_day' , 0),
					'mod_install_month'	=> request_var('mod_install_month' , 0),
					'mod_install_year'	=> request_var('mod_install_year' , 0),
					
				);

				if ($submit)
				{
					if (!$mod_row['mod_title'] || !$mod_row['mod_version'])
					{
						$error[] = $user->lang['ERR_MOD_NO_MATCHES'];
					}

					if ($mod_id)
					{
						$sql = 'SELECT mod_title
							FROM ' . MODS_DATABASE_TABLE . " 
							WHERE mod_id = $mod_id";
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (!$row)
						{
							$error[] = $user->lang['NO_MOD'];
						}
					}
					else
					{
						$sql = 'SELECT mod_title
							FROM ' . MODS_DATABASE_TABLE . "
							WHERE mod_title = '" . $db->sql_escape($mod_row['mod_title']) . "'";
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						
						if ($row)
						{
							$error[] = $user->lang['MOD_NAME_TAKEN'];
						}
					}
					
					if (!sizeof($error))
					{
						$uid_desc = $bitfield_desc = $options_desc = ''; // will be modified by generate_text_for_storage
						$uid_comments = $bitfield_comments = $options_comments = ''; // will be modified by generate_text_for_storage
						$allow_bbcode = $allow_urls = $allow_smilies = true;
			
						generate_text_for_storage($mod_row['mod_desc'], $uid_desc, $bitfield_desc, $options_desc, $allow_bbcode, $allow_urls, $allow_smilies);
						generate_text_for_storage($mod_row['mod_comments'], $uid_comments, $bitfield_comments, $options_comments, $allow_bbcode, $allow_urls, $allow_smilies);

						// New Mod? Create a Mod entry
						if ($action == 'add')
						{
							$sql = 'INSERT INTO ' . MODS_DATABASE_TABLE . ' ' . $db->sql_build_array('INSERT', array(
								'mod_title'						=> (string) $mod_row['mod_title'],
								'mod_version'					=> (string) $mod_row['mod_version'], 
								'mod_version_type'				=> (string) $mod_row['mod_version_type'], 
								'mod_desc'						=> (string) $mod_row['mod_desc'], 
								'mod_desc_bbcode_uid'			=> (string) $uid_desc,
								'mod_desc_bbcode_bitfield'		=> (string) $bitfield_desc,
								'mod_desc_bbcode_options' 		=> (int) $options_desc,
								'mod_author'					=> (string) $mod_row['mod_author'], 
								'mod_author_email'				=> (string) $mod_row['mod_author_email'], 
								'mod_url'						=> (string) $mod_row['mod_url'], 
								'mod_download'					=> (string) $mod_row['mod_download'], 
								'mod_phpbb_version'				=> (string) $mod_row['mod_phpbb_version'], 
								'mod_comments'					=> (string) $mod_row['mod_comments'],
								'mod_comments_bbcode_uid' 		=> (string) $uid_comments,
								'mod_comments_bbcode_bitfield'	=> (string) $bitfield_comments,
								'mod_comments_bbcode_options' 	=> (int) $options_comments,
								'mod_access'					=> (int) $mod_row['mod_access'],
								'mod_install_date'				=> (int) ( $mod_row['mod_install_day'] == 0 || $mod_row['mod_install_month'] == 0 || $mod_row['mod_install_year'] == 0 ) ? 0 : mktime(0, 0, 0, $mod_row['mod_install_month'], $mod_row['mod_install_day'],$mod_row['mod_install_year']),
						));
							$db->sql_query($sql);
	
							$log = 'ADDED';
						}
						else if ($mod_id)
						{
							$sql = 'SELECT mod_id, mod_title 
								FROM ' . MODS_DATABASE_TABLE . " 
								WHERE mod_id = $mod_id";
							$result = $db->sql_query($sql);
							$row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);

							if (!$row)
							{
								trigger_error($user->lang['NO_MOD'] . adm_back_link($this->u_action . "&amp;id=$mod_id&amp;action=$action"), E_USER_WARNING);
							}

							$sql = 'UPDATE ' . MODS_DATABASE_TABLE . ' SET ' . $db->sql_build_array('UPDATE', array(
								'mod_title'						=> (string) $mod_row['mod_title'],
								'mod_version'					=> (string) $mod_row['mod_version'], 
								'mod_version_type'				=> (string) $mod_row['mod_version_type'], 
								'mod_desc'						=> (string) $mod_row['mod_desc'], 
								'mod_desc_bbcode_uid'			=> (string) $uid_desc,
								'mod_desc_bbcode_bitfield'		=> (string) $bitfield_desc,
								'mod_desc_bbcode_options' 		=> (int) $options_desc,
								'mod_author'					=> (string) $mod_row['mod_author'], 
								'mod_author_email'				=> (string) $mod_row['mod_author_email'], 
								'mod_url'						=> (string) $mod_row['mod_url'], 
								'mod_download'					=> (string) $mod_row['mod_download'],
								'mod_phpbb_version'				=> (string) $mod_row['mod_phpbb_version'], 
								'mod_comments'					=> (string) $mod_row['mod_comments'],
								'mod_comments_bbcode_uid' 		=> (string) $uid_comments,
								'mod_comments_bbcode_bitfield'	=> (string) $bitfield_comments,
								'mod_comments_bbcode_options' 	=> (int) $options_comments,
								'mod_access'					=> (int) $mod_row['mod_access'],
								'mod_install_date'				=> (int) ( $mod_row['mod_install_day'] == 0 || $mod_row['mod_install_month'] == 0 || $mod_row['mod_install_year'] == 0 ) ? 0 : mktime(0, 0, 0, $mod_row['mod_install_month'], $mod_row['mod_install_day'],$mod_row['mod_install_year'])
							)) . " WHERE mod_id = $mod_id";
							$db->sql_query($sql);

							$log = 'UPDATED';
						}
						
						add_log('admin', 'LOG_MOD_' . $log, $mod_row['mod_title']);
						trigger_error($user->lang['MOD_' . $log] . adm_back_link($this->u_action));
					
					}
				}
				else if ($mod_id)
				{
					$sql = 'SELECT * 
						FROM ' . MODS_DATABASE_TABLE . "
						WHERE mod_id = $mod_id";
					$result = $db->sql_query($sql);
					$mod_row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if (!$mod_row)
					{
						trigger_error($user->lang['NO_MOD'] . adm_back_link($this->u_action . "&amp;id=$mod_id&amp;action=$action"), E_USER_WARNING);
					}

				decode_message($mod_row['mod_desc'], $mod_row['mod_desc_bbcode_uid']);
				decode_message($mod_row['mod_comments'], $mod_row['mod_comments_bbcode_uid']);

				}
				$mod_install_day = ($action == 'edit') ? (($mod_row['mod_install_date'] == 0) ? 0 : date('j', $mod_row['mod_install_date'])) : '' ;
				$mod_install_month = ($action == 'edit') ? (($mod_row['mod_install_date'] == 0) ? 0 : date('n', $mod_row['mod_install_date'])) : '';
				$mod_install_year = ($action == 'edit') ? (($mod_row['mod_install_date'] == 0) ? 0 : date('Y', $mod_row['mod_install_date'])) : '';
				
				$s_mod_install_day_options = '<option value="0"' . ((!$mod_install_day) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = 1; $i < 32; $i++)
				{
					$selected = ($i == $mod_install_day) ? ' selected="selected"' : '';
					$s_mod_install_day_options .= "<option value=\"$i\"$selected>$i</option>";
				}

				$s_mod_install_month_options = '<option value="0"' . ((!$mod_install_month) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = 1; $i < 13; $i++)
				{
					$selected = ($i == $mod_install_month) ? ' selected="selected"' : '';
					$s_mod_install_month_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				$s_mod_install_year_options = '';

				$now = getdate();
				$s_mod_install_year_options = '<option value="0"' . ((!$mod_install_year) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = $now['year'] - 10; $i < $now['year'] + 10; $i++)
				{
					$selected = ($i == $mod_install_year) ? ' selected="selected"' : '';
					$s_mod_install_year_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				unset($now);

				$l_title = ($action == 'edit') ? 'EDIT' : 'ADD';

				$template->assign_vars(array(
					'L_TITLE'		=> $user->lang['MOD_' . $l_title],
					'U_ACTION'		=> $this->u_action . "&amp;id=$mod_id&amp;action=$action",
					'U_BACK'		=> $this->u_action,
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br />', $error) : '',
					
					'MOD_TITLE'			=> $mod_row['mod_title'],
					'MOD_VERSION'		=> $mod_row['mod_version'],
					'MOD_VERSION_TYPE'	=> $mod_row['mod_version_type'],
					'MOD_DESC'			=> $mod_row['mod_desc'],
					'MOD_AUTHOR'		=> $mod_row['mod_author'],
					'MOD_AUTHOR_EMAIL'	=> $mod_row['mod_author_email'],
					'MOD_URL'			=> $mod_row['mod_url'],
					'MOD_DOWNLOAD'		=> $mod_row['mod_download'],
					'MOD_PHPBB_VERSION'	=> $mod_row['mod_phpbb_version'],
					'MOD_COMMENTS'		=> $mod_row['mod_comments'],
					'MOD_ACCESS'		=> $mod_row['mod_access'],

					'S_MOD_INSTALL_DAY_OPTIONS'		=> $s_mod_install_day_options,
					'S_MOD_INSTALL_MONTH_OPTIONS'	=> $s_mod_install_month_options,
					'S_MOD_INSTALL_YEAR_OPTIONS'	=> $s_mod_install_year_options,

					'WWW_IMG' 			=> $user->img('icon_contact_www', 'VISIT_WEBSITE'),
					'DOWNLOAD_IMG' 		=> $user->img('icon_contact_download', 'DOWNLOAD_MOD'),
						
					'S_EDIT_MOD'		=> true,
					'S_ERROR'			=> (sizeof($error)) ? true : false,
					)
				);

				return;

			break;
		}

		$s_options = '';
		$_options = array('delete' => 'DELETE');
		foreach ($_options as $value => $lang)
		{
			$s_options .= '<option value="' . $value . '">' . $user->lang[$lang] . '</option>';
		}

		// Sorting
		$sort_key_sql = array('a' => 'mod_title', 'b' => 'mod_version', 'c' => 'mod_author');

		$order_by = '';

		// Sorting and order
		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_key;
		}

		$order_by .= 'LOWER(' . $sort_key_sql[$sort_key] . ') ' . (($sort_dir == 'a') ? 'ASC' : 'DESC');

		$sql = 'SELECT * 
			FROM ' . MODS_DATABASE_TABLE . " 
			ORDER BY $order_by";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('mods', array(
				'MOD_ID'			=> $row['mod_id'],
				'MOD_TITLE'			=> $row['mod_title'],
				'MOD_VERSION'		=> $row['mod_version'],
				'MOD_ACCESS'		=> $row['mod_access'],
				'MOD_VERSION_TYPE'	=> $row['mod_version_type'],
				'MOD_DESC'			=> generate_text_for_display($row['mod_desc'], $row['mod_desc_bbcode_uid'], $row['mod_desc_bbcode_bitfield'], $row['mod_desc_bbcode_options']), 
				'MOD_AUTHOR'		=> $row['mod_author'],
				'MOD_AUTHOR_EMAIL'	=> $row['mod_author_email'],
				'MOD_URL'			=> $row['mod_url'],
				'MOD_DOWNLOAD'		=> $row['mod_download'],
	
				'U_EDIT'				=> $this->u_action . "&amp;id={$row['mod_id']}&amp;action=edit",
				'U_DELETE'				=> $this->u_action . "&amp;id={$row['mod_id']}&amp;action=delete")
			);
		}
		$db->sql_freeresult($result);
		

		$template->assign_vars(array(
			'U_ACTION'		=> $this->u_action,

			'U_SORT_MOD_TITLE'		=> $this->u_action . '&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_MOD_VERSION'	=> $this->u_action . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_MOD_AUTHOR'		=> $this->u_action . '&amp;sk=c&amp;sd=' . (($sort_key == 'c' && $sort_dir == 'a') ? 'd' : 'a'),
			
			'MODDB_VERSION'	=> $config['moddb_version'],
			'S_MOD_OPTIONS'	=> $s_options)
		);


	}
	
}
?>