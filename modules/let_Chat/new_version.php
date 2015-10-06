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
?>
<style type="text/css">
#simplechat-version {
    background-color: #EEEEEE;
    color: #000000;
    font-size: 14px;
    font-weight: normal;
    min-height: 150px;
    padding: 10px;
    border: 1px solid #666666;
}
h1{
 border: none;
 cursor: default;
}
</style>
<?php

global $mod_strings, $chatCurrentVersion, $current_user, $app_strings;
if (!is_admin($current_user)) sugar_die($app_strings['ERR_NOT_ADMIN']);

require_once('modules/let_Chat/version.php');

if (isset($_REQUEST['return_module'])&& $_REQUEST['return_module']=='Administration' && function_exists('getClassicModuleTitle')) {
  echo getClassicModuleTitle("Administration", array("<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>", $mod_strings['LBL_CHECK_NEW_VERSION'],), true);
}
else {
  echo get_module_title('let_Chat', $mod_strings['LBL_CHECK_NEW_VERSION'], $mod_strings['LBL_CHECK_NEW_VERSION']);
}

echo '<div id="simplechat-version">
<br />
<iframe width="700" height="300" frameborder="0" scrolling="no" allowtransparency="true" hspace="0" id="letrium_feedback_frame" marginheight="0" marginwidth="0" name="letrium_simplechat_version" src="http://letrium.com/version.php?p=simplechat&v='.$chatCurrentVersion.'" vspace="0"></iframe></div>';
?>
