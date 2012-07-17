<?php
/**
*
* @package phpBB3
* @version $Id: index.php 242 2009-11-01 20:40:53Z lefty74 $
* @copyright (c) 2008 lefty74
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
define('IN_INSTALL', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
include($phpbb_root_path . 'includes/db/db_tools.' . $phpEx);

$action = request_var('action', '');	
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/info_acp_modsdb', 'mods/modsdb', 'acp/common', 'install'));
$user->theme['template_storedb'] = false;

// CURRENT VERSION
$current_version = '1.0.7';


// Before we do anything, lets see if an Admin is calling this file
if (!$auth->acl_get('a_'))
{
	trigger_error('NO_ADMIN');
}

$msg = '';
$table_data = array();

if (version_compare($config['version'], '3.0.4', '>' ))
{
	$db_tools = new phpbb_db_tools($db);
	$table_data = get_table_data();
}

switch ($action)
{
	case 'uninstall_complete':
	case 'install_complete':

		$sql = 'DELETE 
		FROM ' . STYLES_TEMPLATE_DATA_TABLE . "
		WHERE template_filename LIKE '" . $db->sql_escape('install_%') . "'";
		$result = $db->sql_query($sql);

		if ($action == 'install_complete')
		{
			$msg .= $user->lang['MODDB_INSTALL_COMPLETE'];
		}
		elseif ($action == 'uninstall_complete')
		{
			$msg .= $user->lang['MODDB_DELETE_COMPLETE'];
		}
		
		$msg .= sprintf($user->lang['MODDB_INSTALL_RETURN'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');

						
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['MODS_DATABASE'],
			'BODY'	=> $msg,
		));

	break;
	case 'install':
	
		// Add permissions
		$auth_admin = new auth_admin();
		$auth_admin->acl_add_option(array(
		    'global'   => array('u_modsdb_view', 'a_modsdb_view'),
		));    
		
		$msg .=  '<span style="color:green;"> - ' . $user->lang['MODDB_PERM_CREATED'] . '</span><br/>';
				
		if (version_compare($config['version'], '3.0.4', '>' ))
		{
			$db_tools->sql_create_table(MODS_DATABASE_TABLE, $table_data);
		}
		else 
		{
			load_schema($phpbb_root_path . 'install/schemas/');
		}

		$uid_desc = $bitfield_desc = $options_desc = ''; // will be modified by generate_text_for_storage
		$uid_comments = $bitfield_comments = $options_comments = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;

		generate_text_for_storage($user->lang['MODDB_DESCRIPTION'], $uid_desc, $bitfield_desc, $options_desc, $allow_bbcode, $allow_urls, $allow_smilies);

		// Add the first entry to the Mod Database :), the Mod Database Mod :D
		$sql_ary = array(
			'mod_title'						=> (string) utf8_normalize_nfc($user->lang['MODS_DATABASE']),
			'mod_version'					=> (string) $current_version, 
			'mod_version_type'				=> '', 
			'mod_desc'						=> (string) utf8_normalize_nfc($user->lang['MODDB_DESCRIPTION']), 
			'mod_desc_bbcode_uid'			=> (string) $uid_desc,
			'mod_desc_bbcode_bitfield'		=> (string) $bitfield_desc,
			'mod_desc_bbcode_options' 		=> (int) $options_desc,
			'mod_author'					=> 'lefty74', 
			'mod_author_email'				=> 'lefty@lefty74.com', 
			'mod_url'						=> '', 
			'mod_download'					=> '',
			'mod_phpbb_version'				=> (string) $config['version'], 
			'mod_comments'					=> '',
			'mod_comments_bbcode_uid' 		=> '',
			'mod_comments_bbcode_bitfield'	=> '',
			'mod_comments_bbcode_options' 	=> 0,
			'mod_access'					=> 0,
			'mod_install_date'				=> (int) time() + $user->timezone + $user->dst - date('Z'),
		);
		
		$db->sql_query('INSERT INTO ' . MODS_DATABASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));
		
		$msg .=  '<span style="color:green;">- ' . $user->lang['MODDB_TABLE_CREATED'] . '</span> <br/>';

		//lets tell everyone we added the Mods Database already
		add_log('admin', 'LOG_MOD_ADDED', (string) utf8_normalize_nfc($user->lang['MODS_DATABASE']));

		install_modules();
		$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_MODULE_ADDED'] . '</span><br /><br />';
		
		set_config('moddb_version', (string) $current_version);
		
		global $cache;
		$cache->purge();

		add_log('admin', 'LOG_PURGE_CACHE');
						
		$redirect = append_sid("{$phpbb_root_path}install/index.$phpEx", "action=install_complete");
		meta_refresh(3, $redirect);

		$msg .= $user->lang['MODDB_INSTALL_REDIRECT'];
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['MODS_DATABASE'],
			'BODY'	=> $msg,
		));

	break;
	
	case 'upgrade':

		if (version_compare((isset($config['moddb_version']) ? $config['moddb_version'] : 0), $current_version, '=='))
		{
			$msg .= sprintf($user->lang['MODDB_UP_TO_DATE'], $current_version) . '<br />';
			
			$msg .= sprintf($user->lang['MODDB_INSTALL_RETURN'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');

		}
		else if ( !isset($config['moddb_version']))
		{
			// Lets first get the mods and store them so they dont have to be readded
			$mod_store_ary = array();
			
			$sql = 'SELECT *
				FROM ' . MODS_DATABASE_TABLE;
			$result = $db->sql_query($sql);
			
			while ($row = $db->sql_fetchrow($result))
			{
				$mod_store_ary[] = $row;
			}
			$db->sql_freeresult($result);
			$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_PREV_MODS_SAVE'] . '</span>';
			
			// Lets drop the table from the database as we don't know which version we are upgrading from
			$sql = 'DROP TABLE ' . MODS_DATABASE_TABLE;
			$result = $db->sql_query($sql);		
	
			$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_PREV_TABLE_DELETE'] . '</span>';
	
			// Add permissions
			$auth_admin = new auth_admin();
			
			$auth_admin->acl_add_option(array(
			    'global'   => array('u_modsdb_view', 'a_modsdb_view'),
			));    
			
			$msg .=  '<span style="color:green;"> - ' . $user->lang['MODDB_PERM_CREATED'] . '</span>';
	
			// load schema
			if (version_compare($config['version'], '3.0.4', '>' ))
			{
				$db_tools->sql_create_table(MODS_DATABASE_TABLE, $table_data);
			}
			else 
			{
				load_schema($phpbb_root_path . 'install/schemas/');
			}
								
			//lets put the mods back in the database
			for ($i = 0; $i < sizeof($mod_store_ary); $i++) 
			{
				$sql_ary = array(
					'mod_title'						=> (string) $mod_store_ary[$i]['mod_title'],
					'mod_version'					=> (string) $mod_store_ary[$i]['mod_version'], 
					'mod_version_type'				=> (string) $mod_store_ary[$i]['mod_version_type'], 
					'mod_desc'						=> (string) $mod_store_ary[$i]['mod_desc'], 
					'mod_desc_bbcode_uid'			=> (string) $mod_store_ary[$i]['mod_desc_bbcode_uid'], 
					'mod_desc_bbcode_bitfield'		=> (string) $mod_store_ary[$i]['mod_desc_bbcode_bitfield'],
					'mod_desc_bbcode_options' 		=> (int) $mod_store_ary[$i]['mod_desc_bbcode_options'],
					'mod_author'					=> (string) $mod_store_ary[$i]['mod_author'], 
					'mod_author_email'				=> (string) $mod_store_ary[$i]['mod_author_email'], 
					'mod_url'						=> (string) $mod_store_ary[$i]['mod_url'], 
					'mod_download'					=> (string) $mod_store_ary[$i]['mod_download'], 
					'mod_phpbb_version'				=> (string) $mod_store_ary[$i]['mod_phpbb_version'], 
					'mod_comments'					=> (string) $mod_store_ary[$i]['mod_comments'],
					'mod_comments_bbcode_uid' 		=> (string) $mod_store_ary[$i]['mod_comments_bbcode_uid'],
					'mod_comments_bbcode_bitfield'	=> (string) $mod_store_ary[$i]['mod_comments_bbcode_bitfield'],
					'mod_comments_bbcode_options' 	=> (int) $mod_store_ary[$i]['mod_comments_bbcode_options'],
					'mod_access'					=> (int) $mod_store_ary[$i]['mod_access'],
					'mod_install_date'				=> (int) $mod_store_ary[$i]['mod_install_date'],
				);
				$db->sql_query('INSERT INTO ' . MODS_DATABASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));
	
			}		
			$msg .=  '<br /><span style="color:green;">- ' . $user->lang['MODDB_PREV_TABLE_POP'] . '</span>';
		
			install_modules();
			$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_MODULE_READDED'] . '</span><br /><br />';

			// lets get rid of the old config field if still there
			if ( isset($config['mod_show']) )
			{
				$sql = 'DELETE 
				FROM ' . CONFIG_TABLE . "
				WHERE config_name = '" . $db->sql_escape('mod_show') . "'";
				$result = $db->sql_query($sql);
			}
		}
		else
		{
			switch ($config['moddb_version'])
			{
				case '1.0.2':
				case '1.0.3':
				case '1.0.4':
				case '1.0.5':
				case '1.0.6':
				case '1.0.6a':
					install_modules();
					$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_MODULE_READDED'] . '</span><br /><br />';
				break;
			}
		}
		

		//setting the version so if we update in the future we don't have to go through the whole shabang again		
		set_config('moddb_version', (string) $current_version);
		
		global $cache;
		$cache->purge();
		add_log('admin', 'LOG_PURGE_CACHE');
						
		//don't know of a better fix but redirect to make sure to get the install html files out of the templates that are stored in the database after the cache purging
		$redirect = append_sid("{$phpbb_root_path}install/index.$phpEx", "action=install_complete");
		meta_refresh(3, $redirect);

		$msg .= $user->lang['MODDB_INSTALL_REDIRECT'];
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['MODS_DATABASE'],
			'BODY'	=> $msg,
		));
	break;

	case 'uninstall':
		
		//lets now delete the table
		if (version_compare($config['version'], '3.0.4', '>' ))
		{
			$db_tools->sql_table_drop(MODS_DATABASE_TABLE);
		}
		else 
		{
			$sql = 'DROP TABLE ' . MODS_DATABASE_TABLE;
			$result = $db->sql_query($sql);		
		}

		// now lets delete the config fields should they exist
		if ( isset($config['mod_show']) )
		{
			$sql = 'DELETE 
			FROM ' . CONFIG_TABLE . "
			WHERE config_name = '" . $db->sql_escape('mod_show') . "'";
			$result = $db->sql_query($sql);
		}

		if ( isset($config['moddb_version']) )
		{
			$sql = 'DELETE 
			FROM ' . CONFIG_TABLE . "
			WHERE config_name = '" . $db->sql_escape('moddb_version') . "'";
			$result = $db->sql_query($sql);
		}
		$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_TABLE_CONFIG_DELETE'] . '</span>';
					
		// install the modules
		install_modules('delete');
		$msg .= '<span style="color:green;">- ' . $user->lang['MODDB_MODULE_DELETED'] . '</span>';

		global $cache;
		$cache->purge();
		add_log('admin', 'LOG_PURGE_CACHE');
						
		//don't know of a better fix but redirect to make sure to get the install html files out of the templates that are stored in the database after the cache purging
		$redirect = append_sid("{$phpbb_root_path}install/index.$phpEx", "action=uninstall_complete");
		meta_refresh(3, $redirect);

		$msg .= $user->lang['MODDB_UNINSTALL_REDIRECT'];		
			
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['MODS_DATABASE'],
			'BODY'	=> $msg,
		));
	break;

	default:

		$msg = '<span style="color:red; font-size:1.5em;">' . $user->lang['MODDB_BACKUP_WARN'] . '</span><br /><br />';				

		if (!isset($config['mod_show']) && !isset($config['moddb_version']))
		{
			$msg .= '<span style="color:red;">' . $user->lang['MODDB_INSTALL_DESC'] . '</span><br /><br />';				
			$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=install") . '">' . $user->lang['MODDB_NEW_INSTALL'] . '</a><br />';
		}
		else
		{
			$msg .= '<span style="color:red;">' . $user->lang['MODDB_UPGRADE_DESC'] . '</span><br /><br />';				
			if (!version_compare((isset($config['moddb_version']) ? $config['moddb_version'] : 0), $current_version, '=='))
			{
				$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=upgrade") . '">' . sprintf($user->lang['MODDB_UPGRADE'], $current_version) . '</a><br />';			}
			else
			{
				$msg .= sprintf($user->lang['MODDB_UP_TO_DATE'], $current_version) . '<br />';
			}
					
			$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=uninstall") . '"><br /><span style="color:red;">' . $user->lang['MODDB_UNINSTALL'] . '</span></a><br />';	
		}

		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['MODS_DATABASE'],
			'BODY'	=> $msg,
		));

}

// Output

// Output page
page_header($user->lang['INSTALL_PANEL']);

$template->set_custom_template('../adm/style', 'admin');
$template->assign_var('T_TEMPLATE_PATH', '../adm/style');

$template->set_filenames(array(
	'body' => 'install_main.html')
);

page_footer();

/**
* Load a schema (and execute)
*
* @param string $install_path Path to folder containing schema files
* @param mixed $install_dbms Alternative database system than $dbms
*/
function load_schema($install_path = '', $install_dbms = false)
{
   global $db;
   global $table_prefix;

   if ($install_dbms === false)
   {
	  global $dbms;
	  $install_dbms = $dbms;
   }

   static $available_dbms = false;

   if (!$available_dbms)
   {
	  if (!function_exists('get_available_dbms'))
	  {
		 global $phpbb_root_path, $phpEx;
		 include($phpbb_root_path . 'includes/functions_install.' . $phpEx);
	  }

	  $available_dbms = get_available_dbms($install_dbms);

	  if ($install_dbms == 'mysql')
	  {
		 if (version_compare($db->sql_server_info(true), '4.1.3', '>='))
		 {
			$available_dbms[$install_dbms]['SCHEMA'] .= '_41';
		 }
		 else
		 {
			$available_dbms[$install_dbms]['SCHEMA'] .= '_40';
		 }
	  }
   }

   $remove_remarks = $available_dbms[$install_dbms]['COMMENTS'];
   $delimiter = $available_dbms[$install_dbms]['DELIM'];

   $dbms_schema = $install_path . $available_dbms[$install_dbms]['SCHEMA'] . '_schema.sql';

   if (file_exists($dbms_schema))
   {
	  $sql_query = @file_get_contents($dbms_schema);
	  $sql_query = preg_replace('#(?<!mod_)phpbb_#i', $table_prefix, $sql_query);

	  $remove_remarks($sql_query);

	  $sql_query = split_sql_file($sql_query, $delimiter);

	  foreach ($sql_query as $sql)
	  {
		 $db->sql_query($sql);
	  }
	  unset($sql_query);
   }

   if (file_exists($install_path . 'schema_data.sql'))
   {
	  $sql_query = file_get_contents($install_path . 'schema_data.sql');

	  switch ($install_dbms)
	  {
		 case 'mssql':
		 case 'mssql_odbc':
			$sql_query = preg_replace('#\# MSSQL IDENTITY (phpbb_[a-z_]+) (ON|OFF) \##s', 'SET IDENTITY_INSERT \1 \2;', $sql_query);
		 break;

		 case 'postgres':
			$sql_query = preg_replace('#\# POSTGRES (BEGIN|COMMIT) \##s', '\1; ', $sql_query);
		 break;
	  }

	  $sql_query = preg_replace('#(?<!mod_)phpbb_#i', $table_prefix, $sql_query);
	  $sql_query = preg_replace_callback('#\{L_([A-Z0-9\-_]*)\}#s', 'adjust_language_keys_callback', $sql_query);

	  remove_remarks($sql_query);

	  $sql_query = split_sql_file($sql_query, ';');

	  foreach ($sql_query as $sql)
	  {
		 $db->sql_query($sql);
	  }
	  unset($sql_query);
   }
}

function install_modules($type=false)
{
	global $db, $user;
	
	// Lets make sure this module does not get added a second time by accident
	$sql = 'SELECT module_id
		FROM ' . MODULES_TABLE . "
		WHERE module_langname = '" . $db->sql_escape('ACP_MODS_DATABASE') . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if ($row)
	{
		$sql = 'DELETE
			FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape('ACP_MODS_DATABASE') . "'";
		$result = $db->sql_query($sql);
	}
	
	if ($type != 'delete')
	{
		// Lets get the .MOD module ID so we can insert our module there
		$sql = 'SELECT module_id
			FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape('ACP_CAT_DOT_MODS') . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$_module = new acp_modules();
		
		// So lets add the main category
		$mods_db = array(
			'module_basename'	=> '',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> (int) $row['module_id'],
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_MODS_DATABASE',
			'module_mode'		=> '',
			'module_auth'		=> 'acl_a_modsdb_view',
		);
		$_module->update_module_data($mods_db);
		// Now the subcategories
		$mods_db_sub = array(
			'module_basename'	=> 'modsdb',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> (int) $mods_db['module_id'],
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_MODS_DATABASE',
			'module_mode'		=> 'acp_modsdb',
			'module_auth'		=> 'acl_a_modsdb_view',
		);
		$_module->update_module_data($mods_db_sub);
		
	}
}

function get_table_data()
{
	//lets set up the column data for the creation of the new table as of 3.0.5
	$table_data = array(
		'COLUMNS'      => array(
			'mod_id'         				=> array('UINT', NULL, 'auto_increment'),
			'mod_title'   					=> array('VCHAR', ''),
			'mod_version' 					=> array('VCHAR:10', ''),
			'mod_version_type'   			=> array('VCHAR:20', ''),
			'mod_desc'   					=> array('MTEXT_UNI', ''),
			'mod_desc_bbcode_uid'   		=> array('VCHAR:8', ''),
			'mod_desc_bbcode_bitfield'  	=> array('VCHAR', ''),
			'mod_desc_bbcode_options'		=> array('UINT:11', 7),
			'mod_url'   					=> array('VCHAR:100', ''),
			'mod_author'   					=> array('VCHAR:50', ''),
			'mod_download'  				=> array('VCHAR', ''),
			'mod_phpbb_version'  			=> array('VCHAR:10', ''),
			'mod_comments'   				=> array('MTEXT_UNI', ''),
			'mod_comments_bbcode_uid'   	=> array('VCHAR:8', ''),
			'mod_comments_bbcode_bitfield' 	=> array('VCHAR', ''),
			'mod_comments_bbcode_options'	=> array('UINT:11', 7),
			'mod_access'					=> array('BOOL', 0),
			'mod_author_email'   			=> array('VCHAR:100', ''),
			'mod_install_date'   			=> array('TIMESTAMP', 0),
		),
		'PRIMARY_KEY'	=> 'mod_id',
		);
	
	return $table_data;
}
?>