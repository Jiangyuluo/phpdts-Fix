<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

/**
 * 主从数据库同步功能
 * Master-Slave Database Synchronization Functions
 */

/**
 * 连接主数据库
 * Connect to master database
 */
function connect_master_db() {
	global $master_dbhost, $master_dbuser, $master_dbpw, $master_dbname, $database, $pconnect;
	
	if(empty($master_dbhost) || empty($master_dbuser) || empty($master_dbname)) {
		return false;
	}
	
	require_once GAME_ROOT.'./include/db_'.$database.'.class.php';
	$master_db = new dbstuff;
	$master_db->connect($master_dbhost, $master_dbuser, $master_dbpw, $master_dbname, $pconnect);
	
	if($master_db->error()) {
		return false;
	}
	
	return $master_db;
}

/**
 * 检查用户是否已在主数据库中存在
 * Check if user exists in master database
 */
function check_user_in_master($username, $password) {
	global $master_tablepre;
	
	$master_db = connect_master_db();
	if(!$master_db) {
		return false;
	}
	
	$username = addslashes($username);
	$password = addslashes($password);
	
	$result = $master_db->query("SELECT * FROM {$master_tablepre}users WHERE username = '$username' AND password = '$password'");
	if(!$master_db->num_rows($result)) {
		return false;
	}
	
	return $master_db->fetch_array($result);
}

/**
 * 从主数据库同步用户数据到本地数据库
 * Sync user data from master database to local database
 */
function sync_user_from_master($username, $password, $target_username = null) {
	global $db, $gtablepre, $master_tablepre;
	
	$master_db = connect_master_db();
	if(!$master_db) {
		return array('success' => false, 'message' => '无法连接到主数据库');
	}
	
	$username = addslashes($username);
	$password = addslashes($password);
	
	// 检查主数据库中的用户
	$result = $master_db->query("SELECT * FROM {$master_tablepre}users WHERE username = '$username' AND password = '$password'");
	if(!$master_db->num_rows($result)) {
		return array('success' => false, 'message' => '主数据库中未找到匹配的用户名和密码');
	}
	
	$master_user_data = $master_db->fetch_array($result);
	$target_username = $target_username ? $target_username : $username;
	$target_username = addslashes($target_username);
	
	// 检查是否已经被其他账户同步过
	$sync_info = get_user_sync_info($username);
	if($sync_info && $sync_info['target_username'] != $target_username) {
		return array('success' => false, 'message' => "该主服务器账户已被用户 {$sync_info['target_username']} 同步，无法重复同步");
	}
	
	// 检查本地是否已存在目标用户
	$local_result = $db->query("SELECT * FROM {$gtablepre}users WHERE username = '$target_username'");
	if($db->num_rows($local_result)) {
		$local_user_data = $db->fetch_array($local_result);
		
		// 更新现有用户数据
		$update_fields = array();
		$sync_fields = array('credits', 'credits2', 'achievement', 'achrev', 'daily', 'nick', 'nicks', 'validgames', 'wingames', 'gender', 'icon', 'club', 'motto', 'killmsg', 'lastword');
		
		foreach($sync_fields as $field) {
			if(isset($master_user_data[$field])) {
				$value = addslashes($master_user_data[$field]);
				$update_fields[] = "`$field` = '$value'";
			}
		}
		
		if(!empty($update_fields)) {
			$update_sql = "UPDATE {$gtablepre}users SET " . implode(', ', $update_fields) . " WHERE username = '$target_username'";
			$db->query($update_sql);
		}
		
		// 记录同步信息
		set_user_sync_info($target_username, $username);
		
		return array('success' => true, 'message' => '用户数据同步成功');
	} else {
		// 创建新用户
		$insert_fields = array('username' => $target_username);
		$sync_fields = array('password', 'groupid', 'credits', 'credits2', 'achievement', 'achrev', 'daily', 'nick', 'nicks', 'validgames', 'wingames', 'gender', 'icon', 'club', 'motto', 'killmsg', 'lastword');
		
		foreach($sync_fields as $field) {
			if(isset($master_user_data[$field])) {
				$insert_fields[$field] = addslashes($master_user_data[$field]);
			}
		}
		
		$fields = implode('`, `', array_keys($insert_fields));
		$values = "'" . implode("', '", array_values($insert_fields)) . "'";
		
		$insert_sql = "INSERT INTO {$gtablepre}users (`$fields`) VALUES ($values)";
		$db->query($insert_sql);
		
		if($db->affected_rows()) {
			// 记录同步信息
			set_user_sync_info($target_username, $username);
			return array('success' => true, 'message' => '用户账户创建并同步成功');
		} else {
			return array('success' => false, 'message' => '创建用户账户失败');
		}
	}
}

/**
 * 获取用户同步信息
 * Get user sync information
 */
function get_user_sync_info($master_username) {
	global $db, $gtablepre;
	
	$master_username = addslashes($master_username);
	$result = $db->query("SELECT * FROM {$gtablepre}user_sync WHERE master_username = '$master_username'");
	
	if($db->num_rows($result)) {
		return $db->fetch_array($result);
	}
	
	return false;
}

/**
 * 设置用户同步信息
 * Set user sync information
 */
function set_user_sync_info($target_username, $master_username) {
	global $db, $gtablepre;
	
	$target_username = addslashes($target_username);
	$master_username = addslashes($master_username);
	$sync_time = time();
	
	// 检查同步表是否存在，不存在则创建
	create_sync_table_if_not_exists();
	
	// 检查是否已存在记录
	$result = $db->query("SELECT * FROM {$gtablepre}user_sync WHERE target_username = '$target_username'");
	
	if($db->num_rows($result)) {
		// 更新现有记录
		$db->query("UPDATE {$gtablepre}user_sync SET master_username = '$master_username', sync_time = '$sync_time' WHERE target_username = '$target_username'");
	} else {
		// 插入新记录
		$db->query("INSERT INTO {$gtablepre}user_sync (target_username, master_username, sync_time) VALUES ('$target_username', '$master_username', '$sync_time')");
	}
}

/**
 * 创建同步表（如果不存在）
 * Create sync table if not exists
 */
function create_sync_table_if_not_exists() {
	global $db, $gtablepre;
	
	$table_name = $gtablepre . 'user_sync';
	
	// 检查表是否存在
	$result = $db->query("SHOW TABLES LIKE '$table_name'", 'SILENT');
	
	if(!$db->num_rows($result)) {
		// 创建同步表
		$create_sql = "CREATE TABLE `$table_name` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`target_username` char(15) NOT NULL DEFAULT '',
			`master_username` char(15) NOT NULL DEFAULT '',
			`sync_time` int(10) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			UNIQUE KEY `target_username` (`target_username`),
			KEY `master_username` (`master_username`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
		$db->query($create_sql);
	}
}

/**
 * 获取用户的同步状态
 * Get user sync status
 */
function get_user_sync_status($username) {
	global $db, $gtablepre;
	
	$username = addslashes($username);
	$result = $db->query("SELECT * FROM {$gtablepre}user_sync WHERE target_username = '$username'");
	
	if($db->num_rows($result)) {
		return $db->fetch_array($result);
	}
	
	return false;
}

/**
 * 检查是否为从服务器且需要自动同步
 * Check if this is a slave server that needs auto sync
 */
function should_auto_sync() {
	global $slave_level;
	return ($slave_level == 2);
}

/**
 * 检查是否直接使用主数据库
 * Check if should use master database directly
 */
function should_use_master_db() {
	global $slave_level;
	return ($slave_level == 3);
}

?>
