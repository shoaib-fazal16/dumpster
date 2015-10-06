{*
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2014 SugarCRM Inc.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
*}

<form id="ConfigureSettings" name="ConfigureSettings" method="POST"	action="index.php?module=let_Chat&action=settings&process=true">

{if !empty($error)}
<span class='error'>{$error}</span>
<br/><br/>
{/if}

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
<tr>
	<td>
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}"
			accessKey="{$APP.LBL_SAVE_BUTTON_KEY}"
			class="button primary"
			type="submit"
			name="save"
			onclick="return verify_data('ConfigureSettings');"
			value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module={$return_module}&action={$return_action}'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " > </td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view" style="margin-top: 5px;">
	<tr>
		<th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_LOCALE_DEFAULT_CURRENCY}</h4></th>
	</tr>
    <tr>
		<td  scope="row" width="120">{$MOD.LBL_DISABLE_CHAT}: </td>
		    {if $settings.disable_chat==1}
			{assign var='disable_chat_checked' value='CHECKED'}
		    {else}
			{assign var='disable_chat_checked' value=''}
		    {/if}
		<td width="25%"><input type='hidden' name='disable_chat' value='0'><input name='disable_chat' type="checkbox" value="1" {$disable_chat_checked}>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>

	</tr>

	<tr>
		<td scope="row" width="120">{$MOD.LBL_SKIN}: </td>
		<td width="25%">
		    <select id="skin" name="skin">
				{$SKIN_LIST}
			</select>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>

	</tr>
	
	<!-- letrium v -->
	<tr>
		<td scope="row" width="120">{$MOD.LBL_DISPLAY_NAME}: </td>
		<td width="25%">
		    <select id="display_name" name="display_name">
				{$DISPLAY_NAME_LIST}
			</select>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>

	</tr>
	
	<tr>
	<td  scope="row" width="120">{$MOD.LBL_DRAGGABLE}: </td>
		    {if $settings.draggable==1}
			{assign var='draggable_checked' value='CHECKED'}
		    {else}
			{assign var='draggable_checked' value=''}
		    {/if}
		<td width="25%"><input type='hidden' name='draggable' value='0'><input name='draggable' type="checkbox" value="1" {$draggable_checked}>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>
     
    </tr>
	<tr>
		<td scope="row" width="120">{$MOD.LBL_EXPAND_COLLAPSE_CHAT_TYPE}: </td>
		<td width="25%">
		    <select id="expand_collapse_chat" name="expand_collapse_chat">
				{$EXPAND_COLLAPSE_CHAT_TYPE}
			</select>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>

	</tr>
	<tr>
		<td scope="row" width="120">{$MOD.LBL_CHAT_LOCATION}: </td>
		<td width="25%">
		    <select id="chat_location" name="chat_location">
				{$CHAT_LOCATION_LIST}
			</select>
        </td>
        <td>&nbsp</td>
        <td>&nbsp</td>

	</tr>
	
	<!-- letrium v END -->
</table>

<div style="padding-top: 2px;">
<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button primary"  type="submit" name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " />
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module={$return_module}&action={$return_action}'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " />
</div>
</form>

