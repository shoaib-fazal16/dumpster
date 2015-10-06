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

require_once('modules/let_Chat/configurator.php');

class chatHelper extends chatConfigurator
{
	// letrium v
	// add user_name
	protected $reservedKeywords= array('user_name|user', 'time');
	protected $messages_config = array();
	// letrium v END

	function __construct()
	{
		parent::__construct();
	}

	public function escapeSQL($query)
	{
		foreach($this->reservedKeywords as $val) {
			$query= preg_replace('/([^a-z])('.$val.')([^a-z])/i', '$1'.$this->eL.'$2'.$this->eR.'$3', $query);
		}
        return $query;
	}

	public function is_mssql()
	{
		return $this->is_mssql;
	}
	
	// letrium v
	function format_display_name($name, $first_name, $last_name){
		global $db, $locale;;
		
		if (empty($this->messages_config)){
			$q = "SELECT disable_chat, skin, display_name FROM messages_config WHERE id='main'";
			$res = $db->query($q);
			if ($row = $db->fetchByAssoc($res)){
				$this->messages_config = $row;
			}
		}
		if (empty($this->messages_config['display_name'])){
			$this->messages_config['display_name'] = 'name';
		}
		
		$return_name = $name;
		switch($this->messages_config['display_name']){
			case 'name':
				$return_name = $name;
				break;
			case 'full_name':
				$return_name = $locale->getLocaleFormattedName($first_name, $last_name);
				break;
			case 'last_name':
				$return_name = $last_name;
				break;
			case 'first_name':
				$return_name = $first_name;
				break;
		}
		
		if (empty($return_name)) $return_name = $name;
		
		return $return_name;
	}
	// letrium v END

}

?>
