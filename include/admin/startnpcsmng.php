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
$npc_file = $resource_path . 'npc_1.php';

// 解析NPC文件
function parseNPCFile($file_path) {
	$npcs = array();
	if(!file_exists($file_path)) {
		return $npcs;
	}
	
	// 包含NPC文件
	include $file_path;
	
	if(isset($npcinfo) && is_array($npcinfo)) {
		foreach($npcinfo as $npc_id => $npc_data) {
			$npc_summary = array(
				'id' => $npc_id,
				'mode' => isset($npc_data['mode']) ? $npc_data['mode'] : 1,
				'num' => isset($npc_data['num']) ? $npc_data['num'] : 1,
				'club' => isset($npc_data['club']) ? $npc_data['club'] : 0,
				'pls' => isset($npc_data['pls']) ? $npc_data['pls'] : 0,
				'mhp' => isset($npc_data['mhp']) ? $npc_data['mhp'] : 100,
				'msp' => isset($npc_data['msp']) ? $npc_data['msp'] : 100,
				'att' => isset($npc_data['att']) ? $npc_data['att'] : 10,
				'def' => isset($npc_data['def']) ? $npc_data['def'] : 10,
				'lvl' => isset($npc_data['lvl']) ? $npc_data['lvl'] : 1,
				'money' => isset($npc_data['money']) ? $npc_data['money'] : 0,
				'sub_count' => 0,
				'sub_names' => array()
			);
			
			// 统计子NPC
			if(isset($npc_data['sub']) && is_array($npc_data['sub'])) {
				$npc_summary['sub_count'] = count($npc_data['sub']);
				foreach($npc_data['sub'] as $sub_npc) {
					if(isset($sub_npc['name'])) {
						$npc_summary['sub_names'][] = $sub_npc['name'];
					}
				}
			}
			
			$npcs[] = $npc_summary;
		}
	}
	
	return $npcs;
}

// 获取NPC详细信息
function getNPCDetail($file_path, $npc_id) {
	if(!file_exists($file_path)) {
		return null;
	}
	
	include $file_path;
	
	if(isset($npcinfo[$npc_id])) {
		return $npcinfo[$npc_id];
	}
	
	return null;
}

// 保存NPC文件
function saveNPCFile($file_path, $npcinfo) {
	$content = "<?php\n\n";
	$content .= "\tif(!defined('IN_GAME')) exit('Access Denied');\n\n";
	$content .= "\t\$npcinit = array\n\t(\n";
	$content .= "\t\t'name' => '',\t'pass' => 'bra', 'gd' => 'm',\t'icon' => 0,\t'club' => 0,\t\n";
	$content .= "\t\t'mhp' => 0,\t'msp' => 0,\t'att' => 0,\t'def' => 0,\t'pls' => 0,\t'lvl' => 0,\n";
	$content .= "\t\t'money' => 0,\t'inf' => '',\t'rage' => 0,\t'pose' => 0,\t'tactic' => 0,\t\n";
	$content .= "\t\t'killnum' => 0,\t'state' => 1,\t'teamID' => '',\t'teamPass' => '','bid' => 0,\n";
	$content .= "\t\t'horizon' => 0, 'clbpara' => Array(),\n";
	$content .= "\t\t'wp' => 0, 'wk' => 0, 'wc' => 0, 'wg' => 0, 'wd' => 0, 'wf' => 0, 'skills' => 0, 'rp' => 0,\n";
	$content .= "\t\t'wep' => '',\t'wepk' => '',\t'wepe' => 0,\t'weps' => 0,\t'wepsk' => '',\n";
	$content .= "\t\t'arb' => '',\t'arbk' => '',\t'arbe' => 0,\t'arbs' => 0,\t'arbsk' => '',\n";
	$content .= "\t\t'arh' => '',\t'arhk' => '',\t'arhe' => 0,\t'arhs' => 0,\t'arhsk' => '',\n";
	$content .= "\t\t'arf' => '',\t'arfk' => '',\t'arfe' => 0,\t'arfs' => 0,\t'arfsk' => '',\n";
	$content .= "\t\t'ara' => '',\t'arak' => '',\t'arae' => 0,\t'aras' => 0,\t'arask' => '',\n";
	$content .= "\t\t'art' => '',\t'artk' => '',\t'arte' => 0,\t'arts' => 0,\t'artsk' => '',\n";
	
	for($i = 0; $i <= 6; $i++) {
		$content .= "\t\t'itm{$i}' => '',\t'itmk{$i}' => '',\t'itme{$i}' => 0,\t'itms{$i}' => 0,\t'itmsk{$i}' => '',\n";
	}
	
	$content .= "\t);\n";
	$content .= "\t\$npcinfo = array\n\t( \n";
	
	foreach($npcinfo as $npc_id => $npc_data) {
		$content .= "\t//{$npc_id}\n";
		$content .= "\t{$npc_id} => " . var_export($npc_data, true) . ",\n\n";
	}
	
	$content .= "\t);\n?>";
	
	return file_put_contents($file_path, $content);
}

// 处理命令
$npcs = parseNPCFile($npc_file);
$total_npcs = count($npcs);

if($command == 'search' && !empty($search_term)) {
	$filtered_npcs = array();
	foreach($npcs as $npc) {
		$found = false;
		foreach($npc['sub_names'] as $name) {
			if(stripos($name, $search_term) !== false) {
				$found = true;
				break;
			}
		}
		if($found || stripos($npc['id'], $search_term) !== false) {
			$filtered_npcs[] = $npc;
		}
	}
	$npcs = $filtered_npcs;
	$total_npcs = count($npcs);
}

// 分页处理
$showlimit = 10;
$page_npcs = array_slice($npcs, $start, $showlimit);

if($command == 'edit' && isset($edit_id)) {
	$edit_id = (int)$edit_id;
	$edit_npc = getNPCDetail($npc_file, $edit_id);
	
	if(!$edit_npc) {
		$cmd_info = "找不到指定的NPC";
	} else {
		$command = 'edit_form';
	}
}

include template('admin_startnpcsmng');
?>
