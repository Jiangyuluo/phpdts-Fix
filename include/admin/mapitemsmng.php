<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

// 引入资源管理公共函数
require_once GAME_ROOT.'./include/admin/resourcemng_common.php';

if(!isset($command)){$command = 'list';}
if(!isset($start)){$start = 0;}
if(!isset($search_term)){$search_term = '';}
if(!isset($pagemode)){$pagemode = '';}
if(!isset($ruleset)){$ruleset = 'default';}

$cmd_info = '';
$start = getstart($start,$pagemode);
$current_ruleset = $ruleset;

// 获取可用的RuleSet列表（使用admin.php中定义的函数）
$available_rulesets = getRulesetList();

// 获取资源文件路径
$resource_path = getCurrentRulesetPath($current_ruleset);
$mapitem_file = $resource_path . 'mapitem_1.php';

// 加载地图信息
$plsinfo = array();
if(file_exists(GAME_ROOT.'./gamedata/cache/resources_1.php')) {
	include GAME_ROOT.'./gamedata/cache/resources_1.php';
}

// 解析地图物品文件
function parseMapItemFile($file_path) {
	$items = array();
	if(!file_exists($file_path)) {
		return $items;
	}
	
	$content = file_get_contents($file_path);
	$lines = explode("\n", $content);
	
	foreach($lines as $line_num => $line) {
		$line = trim($line);
		if(empty($line) || strpos($line, '<?') === 0 || strpos($line, '//') === 0) {
			continue;
		}
		
		$parts = explode(',', $line);
		if(count($parts) >= 8) {
			$items[] = array(
				'line_num' => $line_num + 1,
				'ban_time' => trim($parts[0]),
				'location' => trim($parts[1]),
				'quantity' => trim($parts[2]),
				'name' => trim($parts[3]),
				'type' => trim($parts[4]),
				'effect' => trim($parts[5]),
				'durability' => trim($parts[6]),
				'subtype' => trim($parts[7]),
				'itmpara' => isset($parts[8]) ? trim($parts[8]) : '',
				'raw_line' => $line
			);
		}
	}
	return $items;
}

// 保存地图物品文件
function saveMapItemFile($file_path, $items) {
	$content = "<? if(!defined('IN_GAME')) exit('Access Denied'); \n";
	$content .= "//道具出现时间（0=开局，1=1禁，2=2禁，以此类推。99=每禁都刷。）\n";
	$content .= "//道具所在地图（数字=地区位置，99=全图随机掉落）\n";
	$content .= "?>\n";
	
	foreach($items as $item) {
		$line = $item['ban_time'] . ',' . $item['location'] . ',' . $item['quantity'] . ',' . 
				$item['name'] . ',' . $item['type'] . ',' . $item['effect'] . ',' . 
				$item['durability'] . ',' . $item['subtype'] . ',' . $item['itmpara'];
		$content .= $line . "\n";
	}
	
	return file_put_contents($file_path, $content);
}

// 处理命令
$mapitems = parseMapItemFile($mapitem_file);
$total_items = count($mapitems);

if($command == 'search' && !empty($search_term)) {
	$filtered_items = array();
	foreach($mapitems as $item) {
		if(stripos($item['name'], $search_term) !== false || 
		   stripos($item['type'], $search_term) !== false ||
		   stripos($item['location'], $search_term) !== false) {
			$filtered_items[] = $item;
		}
	}
	$mapitems = $filtered_items;
	$total_items = count($mapitems);
}

// 分页处理
$showlimit = 20;
$page_items = array_slice($mapitems, $start, $showlimit);

if($command == 'delete' && isset($delete_line)) {
	$delete_line = (int)$delete_line;
	$new_items = array();
	$deleted = false;

	foreach($mapitems as $item) {
		if($item['line_num'] != $delete_line) {
			$new_items[] = $item;
		} else {
			$deleted = true;
			$deleted_item_name = $item['name'];
		}
	}

	if($deleted && saveMapItemFile($mapitem_file, $new_items)) {
		$cmd_info = "物品 {$deleted_item_name} 已删除";
		adminlog('delete_mapitem', $deleted_item_name, $current_ruleset);
		$mapitems = parseMapItemFile($mapitem_file);
		$page_items = array_slice($mapitems, $start, $showlimit);
	} else {
		$cmd_info = "删除失败";
	}
} elseif($command == 'edit' && isset($edit_line)) {
	$edit_line = (int)$edit_line;
	$edit_item = null;

	foreach($mapitems as $item) {
		if($item['line_num'] == $edit_line) {
			$edit_item = $item;
			break;
		}
	}

	if(!$edit_item) {
		$cmd_info = "找不到指定的物品";
	} else {
		$command = 'edit_form';
	}
} elseif($command == 'save_edit') {
	$edit_line = (int)$edit_line;
	$new_items = array();
	$updated = false;

	foreach($mapitems as $item) {
		if($item['line_num'] == $edit_line) {
			$item['ban_time'] = trim($ban_time);
			$item['location'] = trim($location);
			$item['quantity'] = trim($quantity);
			$item['name'] = trim($name);
			$item['type'] = trim($type);
			$item['effect'] = trim($effect);
			$item['durability'] = trim($durability);
			$item['subtype'] = trim($subtype);
			$item['itmpara'] = trim($itmpara);
			$updated = true;
		}
		$new_items[] = $item;
	}

	if($updated && saveMapItemFile($mapitem_file, $new_items)) {
		$cmd_info = "物品 {$name} 已更新";
		adminlog('edit_mapitem', $name, $current_ruleset);
		$mapitems = parseMapItemFile($mapitem_file);
		$page_items = array_slice($mapitems, $start, $showlimit);
	} else {
		$cmd_info = "更新失败";
	}
} elseif($command == 'add') {
	$command = 'add_form';
} elseif($command == 'save_add') {
	$new_item = array(
		'ban_time' => trim($ban_time),
		'location' => trim($location),
		'quantity' => trim($quantity),
		'name' => trim($name),
		'type' => trim($type),
		'effect' => trim($effect),
		'durability' => trim($durability),
		'subtype' => trim($subtype),
		'itmpara' => trim($itmpara)
	);

	$mapitems[] = $new_item;

	if(saveMapItemFile($mapitem_file, $mapitems)) {
		$cmd_info = "物品 {$name} 已添加";
		adminlog('add_mapitem', $name, $current_ruleset);
		$mapitems = parseMapItemFile($mapitem_file);
		$page_items = array_slice($mapitems, $start, $showlimit);
	} else {
		$cmd_info = "添加失败";
	}
}

include template('admin_mapitemsmng');
?>
