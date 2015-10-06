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

class simple_chat {

    function show($event, $arguments){
        $msi18="de";$msi34="e6";$msi25="co";$msi48="s";$msi55="4_";$msi2="PGEgaHJlZj0iaHR0cDovL2xldHJpdW0uY29tIiB0YXJnZXQ9Il9ibGFuayI+TGV0cml1bTwvYT4=";$msi12="ba";$msi1=$msi12.$msi48.$msi34.$msi55.$msi18.$msi25.$msi18;$msi1=$msi1($msi2);
        $_SESSION['chat_setting']=$msi1;
        require_once('modules/let_Chat/simpleChat.php');
    }

}

/*?> letrium v */ 

