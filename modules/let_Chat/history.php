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

@ob_end_clean();
ob_start(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<!-- letrium v. Change js file source -->
 <script type="text/javascript" src="modules/let_Chat/inc/jquery-min.js"></script>
<!-- letrium v END -->
 <link rel="stylesheet" href="modules/let_Chat/css/style.css" type="text/css">
 <title>History</title>
<script type="text/javascript">
function liClick()
{
  $j('ul.tabs.tabs1 li').click(function(){
 	var thisClass = this.className.slice(0,2);
    $j('div.mypanel').hide();
	$j('#panel' + thisClass).show();
	$j('ul.tabs.tabs1 li').removeClass('tab-current');
	$j(this).addClass('tab-current');
	});
}

var $j = jQuery.noConflict();

$j(document).ready(function() {
$j('div.mypanel').hide();
$j('#panelt1').show();
$j('ul.tabs li').css('cursor', 'pointer');
});
</script>
</head>
<body>
<div id="mypanelbody">
<?php
global $db,$current_user;
if(empty($db)) {
	$db = DBManagerFactory::getInstance();
}

$myTmp=mktime(5,0,0,date('m'),date('d')-15,date('Y'));

require_once('modules/let_Chat/helper.php');
$helper= new chatHelper();

$all='';

//ALL MESS
$q="SELECT mh.id, mh.id as sortid, mh.user as nickname, mh.msg as message, mh.time, mh.foruser, u1.first_name, u1.last_name
						 FROM messages_history mh
						 LEFT JOIN users u1 ON u1.user_name = mh.user AND u1.deleted = 0
						 WHERE mh.foruser = 'all' AND mh.time>=".$myTmp." ORDER BY sortid";
$q= $helper->escapeSQL($q); //Taras 2012-12-21
$result = $db->query($q);
while (($message = $db->fetchByAssoc($result)) != null) {
	$message['message'] = htmlspecialchars(stripslashes($message['message']));
	$all.= '<div class="shoutbox-list">'
	           . '<span class="shoutbox-list-time">'.date($GLOBALS['timedate']->get_date_time_format(),$message['time']).'</span>'
	           . '<span class="shoutbox-list-nick">'.$helper->format_display_name($message['nickname'], $message['first_name'], $message['last_name']).':</span>'
	           . '<span class="shoutbox-list-message">'.$message['message'].'</span>'
	           .'</div>';
}

?>
	<ul class="tabs tabs1">
		<li class="t1 tab-current"><a>All (15 days)</a></li>
	</ul>

	<div id="panelt1" class="mypanel">
       <?php echo $all; ?>
	</div>
    <?php
// letrium v
$q="SELECT DISTINCT mh.foruser, u1.first_name, u1.last_name FROM messages_history mh
						 LEFT JOIN users u1 ON u1.user_name = mh.foruser AND u1.deleted = 0
                         WHERE  mh.user='".$current_user->user_name."'  AND mh.foruser<>'".$current_user->user_name."'
                         AND mh.foruser<>'null' AND mh.foruser<>'all'
                         AND mh.time>=".$myTmp."
                         ORDER BY mh.foruser";
// letrium v
$q= $helper->escapeSQL($q); //Taras 2012-12-21
$result = $db->query($q);
$jj=0;
while (($message = $db->fetchByAssoc($result)) != null) {
            ?>
          <script type="text/javascript">
            $j(document).ready(function() {
            	// letrium v
              $j('.t<?php echo $jj+1; ?>').after('<li class="t<?php echo $jj+2; ?>"><a><?php echo $helper->format_display_name($message["foruser"], $message["first_name"], $message["last_name"]); ?></a></li>');
               // letrium v END
             liClick();
            });
          </script>
          <?php
          $tmp=$jj+2;
          echo '<div id="panelt'.$tmp.'" class="mypanel" style="display: none;">';
          // letrium v
          $q2="SELECT mh.id, mh.user as nickname, mh.msg as message, mh.time, mh.foruser, u1.first_name as from_first_name, u1.last_name as from_last_name, u2.first_name as to_first_name, u2.last_name as to_last_name
				 FROM messages_history mh
				 LEFT JOIN users u1 ON u1.user_name = mh.user AND u1.deleted = 0
				 LEFT JOIN users u2 ON u2.user_name = mh.foruser AND u2.deleted = 0
				 WHERE  mh.foruser <> 'all' AND (mh.foruser = '".$current_user->user_name."' OR mh.user='".$current_user->user_name."') AND (mh.foruser = '".$message["foruser"]."' OR mh.user='".$message["foruser"]."') AND mh.time>=".$myTmp." ORDER BY mh.id";
		  // letrium v END
          $q2= $helper->escapeSQL($q2); //Taras 2012-12-21
          $result2 = $db->query($q2);
          while (($row2 = $db->fetchByAssoc($result2)) != null) {
                  $row2['message'] = htmlspecialchars(stripslashes($row2['message']));
                  //isay tome
                  // letrium v
                  if ($row2['nickname']==$current_user->user_name){
                  	$fromTo = '<span class="isay">to '.$helper->format_display_name($row2['foruser'], $row2['to_first_name'], $row2['to_last_name']).'</span>';
                  }
                  else {
                  	$fromTo = '<span class="tome">'.$helper->format_display_name($row2['nickname'], $row2['to_first_name'], $row2['to_last_name']).'</span>';
                  }
                  // letrium v END
                  echo '<div class="shoutbox-list">'
            . '<span class="shoutbox-list-time">'.date($GLOBALS['timedate']->get_date_time_format(),$row2['time']).'</span>'
            . '<span class="shoutbox-list-nick">'.$fromTo.':</span>'
            . '<span class="shoutbox-list-message">'.$row2['message'].'</span>'
            .'</div>';
          }
          echo '</div>';
         $jj++;
   }
?>
</div>
</body>

</html>
<?php
die();
?>
