<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* Simple Chat is a chat for SugarCRM developed by Letrium, Ltd.
* Copyright (C) 2006 - 2014 Letrium Ltd.
*
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
*
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY;  without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE. See  the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with
* this program; if  not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51  Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
*
* You can contact Letrium Ltd. at email address crm@letrium.com.
*
* SimpleChat version 3.5.2, Copyright (C) Letrium Ltd., Taras Machyshyn.
*
* In accordance with Section 7(b) of the GNU General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Letrium" label.
*
*For more information on how to apply and follow the GNU GPL, see http://www.gnu.org/licenses.
********************************************************************************/

global $current_user, $app_strings, $mod_strings, $app_list_strings, $db;
if (!is_admin($current_user)) sugar_die($app_strings['ERR_NOT_ADMIN']);

if (isset($_REQUEST['return_module'])&& $_REQUEST['return_module']=='Administration' && function_exists('getClassicModuleTitle')) {
  echo getClassicModuleTitle("Administration", array("<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>", $mod_strings['LBL_SETTING_TITLE'],), true);
}
else {
  echo get_module_title('let_Chat', $mod_strings['LBL_SETTING_TITLE'], $mod_strings['LBL_SETTING_TITLE']);
}

$sugar_smarty	= new Sugar_Smarty();
$errors	 = '';
///////////////////////////////////////////////////////////////////////////////
////	HANDLE CHANGES
if(isset($_REQUEST['process']) && $_REQUEST['process'] == 'true') {

	// letrium v
	$update="UPDATE messages_config SET disable_chat='".$_POST['disable_chat']."', skin='".$_POST['skin']."', display_name='".$_POST['display_name']."', draggable = '".$_POST['draggable']."', expand_collapse_chat = '".$_POST['expand_collapse_chat']."', chat_location = '".$_POST['chat_location']."' WHERE id='main'";
	// letrium v END
	if (!$db->query($update)) $errors=$mod_strings['LBL_ERROR_CHAT'];
	else header("Location: index.php?module=let_Chat&action=settings");
}

//get Settings
$setings=array();
$exist=false; //check for exist record in database
$select="SELECT disable_chat, skin, display_name, draggable, expand_collapse_chat, chat_location FROM messages_config WHERE id='main'";
$res=$db->query($select);
while($row=$db->fetchByAssoc($res)) {
	$setings['disable_chat']= $row['disable_chat'];
	$setings['skin']= $row['skin'];
	// letrium v
	$setings['display_name']= $row['display_name'];
	$setings['draggable']= $row['draggable'];
	$setings['expand_collapse_chat']= $row['expand_collapse_chat'];
	$setings['chat_location']= $row['chat_location'];
	// letrium v END
	$exist=true;
}
if (!$exist) {
	// letrium v
	$db->query("INSERT INTO messages_config (id, disable_chat, skin, display_name, draggable, expand_collapse_chat, chat_location) VALUES ('main', 0, 'white', 'name', '', '1', 'Double click', 'Left');");
	// letrium v END
	$setings['disable_chat']='0';
	$setings['skin']='white';
	// letrium v
	$setings['display_name']='name';
	$setings['draggable']= $row['draggable'];
	$setings['expand_collapse_chat']= $row['expand_collapse_chat'];
	$setings['chat_location']= $row['chat_location'];
	// letrium v END
}

$skin_list= get_select_options_with_id($mod_strings['LBL_SKIN_LIST'], $setings['skin']);
// letrium v
$display_name_list = get_select_options_with_id($mod_strings['LBL_DISPLAY_NAME_LIST'], $setings['display_name']);
$expand_collapse_chat_type_list = get_select_options_with_id($mod_strings['LBL_EXPAND_COLLAPSE_CHAT_TYPE_LIST'], $setings['expand_collapse_chat']);
$chat_location_list = get_select_options_with_id($mod_strings['LBL_CHAT_LOCATION_LIST'], $setings['chat_location']);
// letrium v END
//end get Settings

///////////////////////////////////////////////////////////////////////////////
////	PAGE OUTPUT
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$sugar_smarty->assign('settings', $setings);
$sugar_smarty->assign('error', $errors);
$sugar_smarty->assign('SKIN_LIST', $skin_list);
// letrium v
$sugar_smarty->assign('DISPLAY_NAME_LIST', $display_name_list);
$sugar_smarty->assign('EXPAND_COLLAPSE_CHAT_TYPE', $expand_collapse_chat_type_list);
$sugar_smarty->assign('CHAT_LOCATION_LIST', $chat_location_list);
// letrium v END
$sugar_smarty->assign('return_module', empty($_REQUEST['return_module'])? 'Administration' : $_REQUEST['return_module']);
$sugar_smarty->assign('return_action', empty($_REQUEST['return_action'])? 'index' : $_REQUEST['return_action']);

$sugar_smarty->display('modules/let_Chat/settings.tpl');

?>
