<?php
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
if(!isset($_SESSION)){
    session_start();
}
global $db;
if (empty($db)) {
  require_once('include/database/DBManagerFactory.php');
  $db = &DBManagerFactory::getInstance();
}

// letrium v
//check for chat disabling
$select="SELECT disable_chat, skin, display_name, draggable, expand_collapse_chat, chat_location FROM messages_config WHERE id='main'";
// letrium v END
$res=$db->limitQuery($select, 0, 1);
//$res=$db->query($select);
$row=$db->fetchByAssoc($res);

//for new feature
$row['default_collapse']= 'true';

if ($row['disable_chat']==false && !isset($_REQUEST['ajax_load']) && !isset($_REQUEST['sugar_body_only']) && !isset($_REQUEST['to_pdf']) && $_REQUEST['action']!='Popup')
{
  $sett=$_SESSION['chat_setting'];
  if ( ($_REQUEST['module']=='Users') && ($_REQUEST['action']=='Login') ) {}
  else {
    global $current_user, $mod_strings, $current_language, $chatCurrentVersion;

    $mod_strings_chat= return_module_language('en_us', 'let_Chat');
    require_once('modules/let_Chat/version.php');

    $xtpl=new XTemplate ('modules/let_Chat/simpleChat.html');
    $xtpl->assign("MOD", $mod_strings_chat);
    $xtpl->assign("CHAT_VERSION", $chatCurrentVersion);
    $xtpl->assign("USER_NAME", $current_user->user_name);

	if (!isset($_SESSION['collapsed']) || $_SESSION['collapsed']=='undefined') {
    	$_SESSION['collapsed']= $row['default_collapse'];
    }$a=$sett;

    $chatH='auto';
    $bottom='';
    if ($_SESSION['collapsed']=='true') {
     $chatH='60px';
     $bottom='bottom:50px;';
    }

    $xtpl->assign("CHATH", $chatH);
    $xtpl->assign("BOTTOM", $bottom);
    $xtpl->assign("A", $a);
    $xtpl->assign("COLLAPSED", $_SESSION['collapsed']);
    $xtpl->assign("ldelim", '{');
    $xtpl->assign("rdelim", '}');
    $xtpl->assign("SKIN", $row['skin']);
    // letrium v    
    $xtpl->assign("DRAGGABLE_DISABLE", (bool)$row['draggable']);
    $xtpl->assign("CHAT_LOCATION", $row['chat_location']);
    $xtpl->assign("EXPAND_COLLAPSE_CHAT", $row['expand_collapse_chat']);
    // letrium v END

	//include jQuery
	$include_jquery= '<script type="text/javascript" src="modules/let_Chat/inc/jquery-min.js"></script>';
	if ($GLOBALS['sugar_version']>=6.5) {
		$include_jquery= '';
	}
    $xtpl->assign("INCLUDE_JQUERY", $include_jquery);
	//end

    $xtpl->parse('main');
    $xtpl->out('main');
  }
} //end check for chat disabling

?>
