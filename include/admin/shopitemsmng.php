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

$cmd_info = '';
$start = getstart($start,$pagemode);
$current_ruleset = $ruleset;
$available_rulesets = getRulesetList();

// 获取资源文件路径
$resource_path = getCurrentRulesetPath($current_ruleset);
$shopitem_file = $resource_path . 'shopitem_1.php';

// 解析商店物品文件
function parseShopItemFile($file_path) {
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
			// 检查是否是分隔符行 (如 0,2,,,补给,,,,,)
			if(count($parts) >= 9 && empty($parts[2]) && empty($parts[3]) && !empty($parts[4])) {
				$items[] = array(
					'line_num' => $line_num + 1,
					'is_separator' => true,
					'category_id' => trim($parts[0]),
					'sub_category' => trim($parts[1]),
					'category_name' => trim($parts[4]),
					'raw_line' => $line
				);
			} elseif(count($parts) >= 9) {
				$items[] = array(
					'line_num' => $line_num + 1,
					'is_separator' => false,
					'category' => trim($parts[0]),
					'quantity' => trim($parts[1]),
					'price' => trim($parts[2]),
					'start_ban' => trim($parts[3]),
					'name' => trim($parts[4]),
					'type' => trim($parts[5]),
					'effect' => trim($parts[6]),
					'durability' => trim($parts[7]),
					'subtype' => trim($parts[8]),
					'itmpara' => isset($parts[9]) ? trim($parts[9]) : '',
					'raw_line' => $line
				);
			}
		}
	}
	return $items;
}

// 保存商店物品文件
function saveShopItemFile($file_path, $items) {
	$content = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n";
	
	foreach($items as $item) {
		if($item['is_separator']) {
			$line = $item['category_id'] . ',' . $item['sub_category'] . ',,,' . 
					$item['category_name'] . ',,,,,';
		} else {
			$line = $item['category'] . ',' . $item['quantity'] . ',' . $item['price'] . ',' . 
					$item['start_ban'] . ',' . $item['name'] . ',' . $item['type'] . ',' . 
					$item['effect'] . ',' . $item['durability'] . ',' . $item['subtype'] . ',' . 
					$item['itmpara'];
		}
		$content .= $line . "\n";
	}
	
	return file_put_contents($file_path, $content);
}

// 处理命令
$shopitems = parseShopItemFile($shopitem_file);
$total_items = count($shopitems);

if($command == 'search' && !empty($search_term)) {
	$filtered_items = array();
	foreach($shopitems as $item) {
		if($item['is_separator']) {
			if(stripos($item['category_name'], $search_term) !== false) {
				$filtered_items[] = $item;
			}
		} else {
			if(stripos($item['name'], $search_term) !== false || 
			   stripos($item['type'], $search_term) !== false) {
				$filtered_items[] = $item;
			}
		}
	}
	$shopitems = $filtered_items;
	$total_items = count($shopitems);
}

// 分页处理
$showlimit = 20;
$page_items = array_slice($shopitems, $start, $showlimit);

if($command == 'delete' && isset($delete_line)) {
	$delete_line = (int)$delete_line;
	$new_items = array();
	$deleted = false;
	
	foreach($shopitems as $item) {
		if($item['line_num'] != $delete_line) {
			$new_items[] = $item;
		} else {
			$deleted = true;
			$deleted_item_name = $item['is_separator'] ? $item['category_name'] : $item['name'];
		}
	}
	
	if($deleted && saveShopItemFile($shopitem_file, $new_items)) {
		$cmd_info = "项目 {$deleted_item_name} 已删除";
		adminlog('delete_shopitem', $deleted_item_name, $current_ruleset);
		$shopitems = parseShopItemFile($shopitem_file);
		$page_items = array_slice($shopitems, $start, $showlimit);
	} else {
		$cmd_info = "删除失败";
	}
}

include template('admin_shopitemsmng');
?>
