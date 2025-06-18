<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

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
?>
