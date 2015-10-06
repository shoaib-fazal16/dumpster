<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $sugar_config;


    $admin_option_defs = array();
	$admin_option_defs['Administration']['simple_chat'] = array(
		'Administration',
		'LBL_SIMPLECHAT_TITLE',
		'LBL_SIMPLECHAT_DESCRIPTION',
		'index.php?module=let_Chat&action=settings&return_module=Administration&return_action=index'
	);
    $admin_option_defs['Administration']['simple_chat_feedback'] = array(
		'EmailFolder',
		'LBL_SIMPLECHAT_FEEDBACK_TITLE',
		'LBL_SIMPLECHAT_FEEDBACK_DESCRIPTION',
		'index.php?module=let_Chat&action=proposals&return_module=Administration&return_action=index'
	);
    $admin_option_defs['Administration']['simple_chat_check'] = array(
		'sugarupdate',
		'LBL_SIMPLECHAT_CHECK_TITLE',
		'LBL_SIMPLECHAT_CHECK_DESCRIPTION',
		'index.php?module=let_Chat&action=new_version&return_module=Administration&return_action=index'
	);
    $admin_option_defs['Administration']['simple_chat_aboutus'] = array(
		'Users',
		'LBL_SIMPLECHAT_ABOUTUS_TITLE',
		'LBL_SIMPLECHAT_ABOUTUS_DESCRIPTION',
		'index.php?module=let_Chat&action=index&return_module=Administration&return_action=index'
	);

	$admin_group_header[]= array(
		'LBL_SIMPLECHAT_ACTIONS_TITLE',
		'',
		false,
		$admin_option_defs,
		'LBL_SIMPLECHAT_ACTIONS_DESC'
	);







?>