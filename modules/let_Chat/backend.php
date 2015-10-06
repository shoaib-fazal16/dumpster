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

global $db, $current_user;
if(empty($db)) {
	$db = DBManagerFactory::getInstance();
}

require_once('modules/let_Chat/helper.php');
$helper= new chatHelper();

$display_num = $helper->display_num;
$user_timeout = $helper->user_timeout; // seconds
$prefix = 'messages';  //deprecated
$timestamp=time();
$time= time();

/*$q = "SELECT otop, oleft FROM ".$prefix."_online WHERE usr_id='".$current_user->id."' limit 0,1" ;
$result = $db->query($q);
while (($message = $db->fetchByAssoc($result)) != null) {
	$otop =  $message['otop'];
  	$oleft =  $message['oleft'];
    $collapsed=$message['collapsed'];
} */

$q="DELETE FROM messages_online WHERE usr_id='".$current_user->id."' OR ($timestamp-rtime)>$user_timeout";
$db->query($q);

if (!empty($_REQUEST['collapsed'])) $_SESSION['collapsed']=$_REQUEST['collapsed'];

/*if (($_REQUEST['top']=='0px') && ($_REQUEST['left']=='0px'))
{
  $_REQUEST['top']=$otop;
  $_REQUEST['left']=$oleft;
}
else
{
  $otop=$_REQUEST['top'];
  $oleft=$_REQUEST['left'];
}       */
$q="INSERT INTO messages_online (usr_id, usr_name, rtime, collapsed)  VALUES('".$current_user->id."','".$current_user->user_name."',$timestamp, '".$_REQUEST['collapsed']."')";
$db->query($q);

if(!$_REQUEST['time']){
	$_REQUEST['time'] = 0;
}
$myTmp=mktime(5,0,0,date('m'),date('d'),date('Y'));

//history
$currentDate= gmdate('Y-m-d');
if (!isset($_SESSION['let_Chat']['historyDate']) || $_SESSION['let_Chat']['historyDate']!=$currentDate) {

	$history_query = "SELECT * FROM messages WHERE time<".$myTmp." ORDER BY id ASC" ;
	$history_query= $helper->escapeSQL($history_query); //Taras 2012-12-21
	$history_result = $db->query($history_query);
	while (($history = $db->fetchByAssoc($history_result)) != null) {
	   $h_query="INSERT INTO messages_history (id, user, msg, time, foruser, del)
	        VALUES ('".$history['id']."',
	         '".$history['user']."',
	         '".$db->quote($history['msg'])."',
	          '".$history['time']."',
	          '".$history['foruser']."',
	          '".$history['del']."'
	    ); ";
	   $db->query($h_query);

	   $del= "DELETE FROM messages WHERE id = ".$history['id'];
	   // letrium v
	   //$res=$db->limitQuery($del, 0, 1);
	   $res=$db->query($del);
	   // letrium v END
	}

	if (!isset($_SESSION['let_Chat'])) {
       	$_SESSION['let_Chat']= array();
	}
	$_SESSION['let_Chat']['historyDate']= $currentDate;
}
//end history
$data = array();
  /* $data[]['top'] =  $otop;
  	$data[]['left'] =  $oleft;*/

  switch($_GET['task']) {
  	 case 'delete_history':

		$q="UPDATE messages SET del=1 WHERE foruser='".$current_user->user_name."'";
		$db->query($q);
  	 break;

    case 'add':

	$name = htmlspecialchars(trim($_REQUEST['nickname']));
	$message = $db->quote(trim($_REQUEST['message']));
	$for_user = htmlspecialchars(trim($_REQUEST['for_user']));

   	$q = "INSERT INTO messages (user,msg, time, foruser)
			VALUES ('$name','$message',".$time.",'$for_user')";
	$q= $helper->escapeSQL($q); //Taras 2012-12-21
	$db->query($q);

	//TARAS 2012-12-20: MSSQL fix
	/*
	$q = "SELECT rez1.* FROM (SELECT id,user as nickname,msg as message,time,foruser
						 FROM messages
						 WHERE id>".$_REQUEST['time']." AND ((foruser = 'all') OR (foruser = '".$current_user->user_name."')
						 	OR user='".$current_user->user_name."') AND time>=".$myTmp."  ORDER BY id DESC)
					AS rez1,
					messages WHERE rez1.id=messages.id ORDER BY id ASC";
	*/
	// letrium v
	// add display name format
	$q = "SELECT m.id, m.id as sortid, m.user as nickname, m.msg as message, m.time, m.foruser, u.first_name as from_first_name, u.last_name as from_last_name, foru.first_name as to_first_name, foru.last_name as to_last_name
						 FROM messages m
						 LEFT JOIN users u ON u.user_name = m.user AND u.deleted = 0
						 LEFT JOIN users foru ON foru.user_name = m.foruser  AND foru.deleted = 0
						 WHERE m.id>".$_REQUEST['time']." AND ((m.foruser = 'all') OR (m.foruser = '".$current_user->user_name."')
						 	OR m.user='".$current_user->user_name."') AND m.time>=".$myTmp."
						 ORDER BY sortid ASC";
    $q= $helper->escapeSQL($q); //Taras 2012-12-21
	$result = $db->limitQuery($q, 0, $display_num);
	while (($message = $db->fetchByAssoc($result)) != null) {
			$message['message'] = htmlspecialchars(stripslashes($message['message']));
			$message['display_from_name'] = $helper->format_display_name($message['nickname'], $message['from_first_name'], $message['from_last_name']);
			$message['display_to_name'] = $helper->format_display_name($message['foruser'], $message['to_first_name'], $message['to_last_name']);
			$data[]=$message;
	}
	// letrium v END

    break;

    case 'view':
//letrium tanya

	//TARAS 2012-12-20: MSSQL fix
	/*$q = "SELECT rez1.* FROM (SELECT id,user as nickname,msg as message,time,foruser
						 FROM messages
						 WHERE id>".$_REQUEST['time']." AND foruser = 'all' AND time>=".$myTmp."  ORDER BY id DESC)
						  AS rez1,messages  WHERE rez1.id=messages.id ORDER BY id ASC";*/
	// letrium v
	// add display name format
	$q = "SELECT m.id, m.id as sortid, m.user as nickname, m.msg as message, m.time, m.foruser, u.first_name as from_first_name, u.last_name as from_last_name, foru.first_name as to_first_name, foru.last_name as to_last_name
						 FROM messages m
						 LEFT JOIN users u ON u.user_name = m.user AND u.deleted = 0
						 LEFT JOIN users foru ON foru.user_name = m.foruser AND foru.deleted = 0
						 WHERE m.id>".$_REQUEST['time']." AND m.foruser = 'all' AND m.time>=".$myTmp."
						 ORDER BY sortid ASC";
	$q= $helper->escapeSQL($q); //Taras 2012-12-21
	$result = $db->limitQuery($q, 0, $display_num);

	while (($message = $db->fetchByAssoc($result)) != null) {
			$message['message'] = htmlspecialchars(stripslashes($message['message']));
			$message['display_from_name'] = $helper->format_display_name($message['nickname'], $message['from_first_name'], $message['from_last_name']);
			$message['display_to_name'] = $helper->format_display_name($message['foruser'], $message['to_first_name'], $message['to_last_name']);
			$data[]=$message;
	}
	// letrium v END

	$q = "SELECT user_name as name FROM users WHERE deleted = 0";
	$users_res = $db->query($q);
	while ($user = $db->fetchByAssoc($users_res))
	{
		/*$q = "SELECT rez1.* FROM (SELECT id,user as nickname,msg as message,time,foruser
						 FROM messages
						 WHERE id>".$_REQUEST['time']." AND
						 	((foruser = '".$current_user->user_name."' AND user='".$user['name']."') OR
						 	(user = '".$current_user->user_name."' AND foruser='".$user['name']."'))
						 	 AND time>=".$myTmp."  ORDER BY id DESC limit ".$display_num." )
						  AS rez1,messages  WHERE rez1.id=messages.id ORDER BY id ASC"; */
		// letrium v
		// add display name format
		//TARAS 2012-12-20: MSSQL fix
		$q = "SELECT m.id, m.id as sortid, m.user as nickname,m.msg as message,m.time,m.foruser, u.first_name as from_first_name, u.last_name as from_last_name, foru.first_name as to_first_name, foru.last_name as to_last_name
						 FROM messages m
						 LEFT JOIN users u ON u.user_name = m.user AND u.deleted = 0
						 LEFT JOIN users foru ON foru.user_name = m.foruser AND foru.deleted = 0
						 WHERE m.id>".$_REQUEST['time']." AND
						 	((m.foruser = '".$current_user->user_name."' AND m.user='".$user['name']."') OR
						 	(m.user = '".$current_user->user_name."' AND m.foruser='".$user['name']."'))
						 	 AND m.time>=".$myTmp."
							 ORDER BY sortid ASC";
							 
		$q= $helper->escapeSQL($q); //Taras 2012-12-21
		$result = $db->limitQuery($q, 0, $display_num);

		while (($message = $db->fetchByAssoc($result)) != null) {
				$message['message'] = htmlspecialchars(stripslashes($message['message']));
				$message['display_from_name'] = $helper->format_display_name($message['nickname'], $message['from_first_name'], $message['from_last_name']);
				$message['display_to_name'] = $helper->format_display_name($message['foruser'], $message['to_first_name'], $message['to_last_name']);
				$data[]=$message;
		}
		// letrium v END
	}


	/*
	//original
	$q = "SELECT rez1.* FROM (SELECT id,user as nickname,msg as message,time,foruser
						 FROM messages
						 WHERE id>".$_REQUEST['time']." AND ((foruser = 'all') OR (foruser = '".$current_user->user_name."')
						 	OR user='".$current_user->user_name."') AND time>=".$myTmp."  ORDER BY id DESC limit ".$display_num." )
						  AS rez1,messages  WHERE rez1.id=messages.id ORDER BY id ASC";


	$result = $db->limitQuery($q, 0, $display_num);
	while (($message = $db->fetchByAssoc($result)) != null) {
			$message['msg'] = htmlspecialchars(stripslashes($message['msg']));
			$data[]=$message;
	}
	*/
   //letrium end
    break;
  }

if ($helper->is_mssql()) {
	$q = "SELECT mo.usr_name, u.first_name, u.last_name 
			FROM messages_online mo
			LEFT JOIN users u ON u.user_name = mo.usr_name AND u.deleted = 0
			WHERE mo.usr_id <>'".$current_user->id."' ORDER BY mo.usr_name ASC" ;
}
else {
	$q = "SELECT mo.usr_name, u.first_name, u.last_name 
			FROM messages_online mo
			LEFT JOIN users u ON u.user_name = mo.usr_name AND u.deleted = 0
			WHERE mo.usr_id <>'".$current_user->id."' GROUP BY mo.usr_id ORDER BY mo.usr_name ASC" ;
}
$result = $db->query($q);
while (($message = $db->fetchByAssoc($result)) != null) {
	$message['display_usr_name'] = $helper->format_display_name($message['usr_name'], $message['first_name'], $message['last_name']);
	$data[] =  $message;
}

if (empty($data)) {
	$data[]=array('status'=>'success');
}                                      

  require_once('modules/let_Chat/JSON.php');
  $json = new Services_JSON();
  $out = $json->encode($data);
  ob_clean();
  print $out;
  exit;
?>
