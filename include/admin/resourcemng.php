<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

// 引入公共函数
require_once GAME_ROOT.'./include/admin/resourcemng_common.php';

// 获取当前选择的RuleSet路径
function getCurrentRulesetPath($ruleset = 'default') {
	if($ruleset == 'default') {
		return GAME_ROOT.'./gamedata/cache/';
	} else {
		return GAME_ROOT.'./gamedata/ruleset/'.$ruleset.'/cache/';
	}
}

// 检查文件是否存在
function checkResourceFile($ruleset, $filename) {
	$path = getCurrentRulesetPath($ruleset);
	return file_exists($path.$filename);
}

$cmd_info = '';
$current_ruleset = isset($ruleset) ? $ruleset : 'default';

// 获取可用的RuleSet列表（使用admin.php中定义的函数）
$available_rulesets = getRulesetList();

// 检查各资源文件状态
$resource_status = array(
	'mapitem_1.php' => array(
		'name' => '地图物品配置',
		'exists' => checkResourceFile($current_ruleset, 'mapitem_1.php'),
		'manage_cmd' => 'mapitemsmng',
		'permission' => 7
	),
	'shopitem_1.php' => array(
		'name' => '商店物品配置',
		'exists' => checkResourceFile($current_ruleset, 'shopitem_1.php'),
		'manage_cmd' => 'shopitemsmng',
		'permission' => 7
	),
	'stitem_1.php' => array(
		'name' => '开局物品配置',
		'exists' => checkResourceFile($current_ruleset, 'stitem_1.php'),
		'manage_cmd' => 'startitemsmng',
		'permission' => 6
	),
	'stwep_1.php' => array(
		'name' => '开局武器配置',
		'exists' => checkResourceFile($current_ruleset, 'stwep_1.php'),
		'manage_cmd' => 'startitemsmng',
		'permission' => 6
	),
	'npc_1.php' => array(
		'name' => '开局NPC配置',
		'exists' => checkResourceFile($current_ruleset, 'npc_1.php'),
		'manage_cmd' => 'startnpcsmng',
		'permission' => 8
	)
);

include template('admin_resourcemng');
?>
