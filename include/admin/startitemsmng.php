<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

// 引入资源管理公共函数
require_once GAME_ROOT.'./include/admin/resourcemng.php';

if(!isset($command)){$command = 'list';}
if(!isset($start)){$start = 0;}
if(!isset($search_term)){$search_term = '';}
if(!isset($pagemode)){$pagemode = '';}
if(!isset($ruleset)){$ruleset = 'default';}
if(!isset($file_type)){$file_type = 'items';}

$cmd_info = '';
$start = getstart($start,$pagemode);
$current_ruleset = $ruleset;
$available_rulesets = getRulesetList();

// 获取资源文件路径
$resource_path = getCurrentRulesetPath($current_ruleset);
$stitem_file = $resource_path . 'stitem_1.php';
$stwep_file = $resource_path . 'stwep_1.php';

// 解析开局物品文件
function parseStartItemFile($file_path) {
	$items = array();
	if(!file_exists($file_path)) {
		return $items;
	}
	
	$content = file_get_contents($file_path);
	$lines = explode("\n", $content);
	
	foreach($lines as $line_num => $line) {
		$line = trim($line);
		if(empty($line) || strpos($line, '<?') === 0) {
			continue;
		}
		
		$parts = explode(',', $line);
		if(count($parts) >= 5) {
			$items[] = array(
				'line_num' => $line_num + 1,
				'name' => trim($parts[0]),
				'type' => trim($parts[1]),
				'effect' => trim($parts[2]),
				'durability' => trim($parts[3]),
				'subtype' => trim($parts[4]),
				'raw_line' => $line
			);
		}
	}
	return $items;
}

// 保存开局物品文件
function saveStartItemFile($file_path, $items) {
	$content = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n";
	
	foreach($items as $item) {
		$line = $item['name'] . ',' . $item['type'] . ',' . $item['effect'] . ',' . 
				$item['durability'] . ',' . $item['subtype'] . ',';
		$content .= $line . "\n";
	}
	
	return file_put_contents($file_path, $content);
}

// 根据文件类型选择文件
$current_file = ($file_type == 'weapons') ? $stwep_file : $stitem_file;
$current_file_name = ($file_type == 'weapons') ? 'stwep_1.php' : 'stitem_1.php';
$current_type_name = ($file_type == 'weapons') ? '武器' : '物品';

// 处理命令
$startitems = parseStartItemFile($current_file);
$total_items = count($startitems);

if($command == 'search' && !empty($search_term)) {
	$filtered_items = array();
	foreach($startitems as $item) {
		if(stripos($item['name'], $search_term) !== false || 
		   stripos($item['type'], $search_term) !== false) {
			$filtered_items[] = $item;
		}
	}
	$startitems = $filtered_items;
	$total_items = count($startitems);
}

// 分页处理
$showlimit = 20;
$page_items = array_slice($startitems, $start, $showlimit);

if($command == 'delete' && isset($delete_line)) {
	$delete_line = (int)$delete_line;
	$new_items = array();
	$deleted = false;
	
	foreach($startitems as $item) {
		if($item['line_num'] != $delete_line) {
			$new_items[] = $item;
		} else {
			$deleted = true;
			$deleted_item_name = $item['name'];
		}
	}
	
	if($deleted && saveStartItemFile($current_file, $new_items)) {
		$cmd_info = "{$current_type_name} {$deleted_item_name} 已删除";
		adminlog('delete_startitem', $deleted_item_name, $current_ruleset);
		$startitems = parseStartItemFile($current_file);
		$page_items = array_slice($startitems, $start, $showlimit);
	} else {
		$cmd_info = "删除失败";
	}
} elseif($command == 'edit' && isset($edit_line)) {
	$edit_line = (int)$edit_line;
	$edit_item = null;
	
	foreach($startitems as $item) {
		if($item['line_num'] == $edit_line) {
			$edit_item = $item;
			break;
		}
	}
	
	if(!$edit_item) {
		$cmd_info = "找不到指定的{$current_type_name}";
	} else {
		$command = 'edit_form';
	}
} elseif($command == 'save_edit') {
	$edit_line = (int)$edit_line;
	$new_items = array();
	$updated = false;
	
	foreach($startitems as $item) {
		if($item['line_num'] == $edit_line) {
			$item['name'] = trim($name);
			$item['type'] = trim($type);
			$item['effect'] = trim($effect);
			$item['durability'] = trim($durability);
			$item['subtype'] = trim($subtype);
			$updated = true;
		}
		$new_items[] = $item;
	}
	
	if($updated && saveStartItemFile($current_file, $new_items)) {
		$cmd_info = "{$current_type_name} {$name} 已更新";
		adminlog('edit_startitem', $name, $current_ruleset);
		$startitems = parseStartItemFile($current_file);
		$page_items = array_slice($startitems, $start, $showlimit);
	} else {
		$cmd_info = "更新失败";
	}
} elseif($command == 'add') {
	$command = 'add_form';
} elseif($command == 'save_add') {
	$new_item = array(
		'name' => trim($name),
		'type' => trim($type),
		'effect' => trim($effect),
		'durability' => trim($durability),
		'subtype' => trim($subtype)
	);
	
	$startitems[] = $new_item;
	
	if(saveStartItemFile($current_file, $startitems)) {
		$cmd_info = "{$current_type_name} {$name} 已添加";
		adminlog('add_startitem', $name, $current_ruleset);
		$startitems = parseStartItemFile($current_file);
		$page_items = array_slice($startitems, $start, $showlimit);
	} else {
		$cmd_info = "添加失败";
	}
}

include template('admin_startitemsmng');
?>
