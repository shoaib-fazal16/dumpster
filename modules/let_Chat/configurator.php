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

class chatConfigurator
{
	public $display_num= 50; //number of displayed messages
	public $user_timeout= 10; //seconds when user is online
	public $is_mssql= false; //check if databse is MsSQL
	protected $eMysql= array('l'=> '`', 'r'=> '`');
	protected $eMssql= array('l'=> '[', 'r'=> ']');
	public $eL; //left symbol of screening of reserved words in MySQL, MsSQL
	public $eR; //right symbol of screening of reserved words in MySQL, MsSQL

	function __construct()
	{
		$db= $this->getDb();

		if (strtolower($db->dbType)=='mssql') {
			$this->is_mssql= true;
		}

		$this->eL= $this->eMysql['l'];
		$this->eR= $this->eMysql['r'];
		if ($this->is_mssql) {
			$this->eL= $this->eMssql['l'];
			$this->eR= $this->eMssql['r'];
		}

	}

	protected function getDb()
	{
		global $db;
		if(empty($db)) {
			$db = DBManagerFactory::getInstance();
		}

		return $db;
	}

}

?>

