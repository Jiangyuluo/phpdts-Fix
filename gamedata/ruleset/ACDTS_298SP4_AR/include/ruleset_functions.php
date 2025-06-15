<?php
if(!defined('IN_GAME')) exit('Access Denied');

/**
 * ACDTS298 ALL RANDOM RuleSet 钩子函数
 * 这些函数实现纯钩子接口，不包含具体的游戏逻辑
 */

/**
 * 物品获得钩子函数
 */
function ruleset_itemget_hook(&$data) {
    global $log, $nosta;

    // 检查是否为全随机模式
    if(!is_all_random_mode()) {
        return;
    }

    extract($data, EXTR_REFS);

    // 随机化itme和itms (0到原数值的2~7倍)
    if($itme0 > 0) {
        $multiplier = rand(2, 7);
        $itme0 = rand(0, $itme0 * $multiplier);
        if($itme0 == 0) $itme0 = 1; // 防止为0
    }
    if($itms0 > 0 && $itms0 !== $nosta) {
        $multiplier = rand(2, 7);
        $itms0 = rand(0, $itms0 * $multiplier);
        if($itms0 == 0) $itms0 = 1; // 防止为0
    }

    // 随机化itmsk
    include_once GAME_ROOT.'./gamedata/ruleset/ACDTS_298SP4_AR/cache/resources_1.php';
    if(isset($itemspkinfo) && is_array($itemspkinfo)) {
        $available_itmsk = array_keys($itemspkinfo);
        // 随机选择1-3个属性
        $num_attrs = rand(1, 3);
        $selected_attrs = array();
        for($i = 0; $i < $num_attrs; $i++) {
            $random_attr = $available_itmsk[array_rand($available_itmsk)];
            if(!in_array($random_attr, $selected_attrs)) {
                $selected_attrs[] = $random_attr;
            }
        }
        $itmsk0 = implode('', $selected_attrs);
    }

    $log .= "<span class=\"cyan\">【全随机模式】物品属性已随机化！</span><br>";

    // 将修改后的变量写回$data数组
    $data['itme0'] = $itme0;
    $data['itms0'] = $itms0;
    $data['itmsk0'] = $itmsk0;
}

/**
 * 合成物品钩子函数
 */
function ruleset_itemmix_hook(&$data) {
    global $log, $nosta;

    // 检查是否为全随机模式
    if(!is_all_random_mode()) {
        return;
    }

    extract($data, EXTR_REFS);

    // 随机化itme和itms (0到原数值的2~7倍)
    if($itme0 > 0) {
        $multiplier = rand(2, 7);
        $itme0 = rand(0, $itme0 * $multiplier);
        if($itme0 == 0) $itme0 = 1; // 防止为0
    }
    if($itms0 > 0 && $itms0 !== $nosta) {
        $multiplier = rand(2, 7);
        $itms0 = rand(0, $itms0 * $multiplier);
        if($itms0 == 0) $itms0 = 1; // 防止为0
    }

    // 随机化itmsk
    include_once GAME_ROOT.'./gamedata/ruleset/ACDTS_298SP4_AR/cache/resources_1.php';
    if(isset($itemspkinfo) && is_array($itemspkinfo)) {
        $available_itmsk = array_keys($itemspkinfo);
        // 随机选择1-3个属性
        $num_attrs = rand(1, 3);
        $selected_attrs = array();
        for($i = 0; $i < $num_attrs; $i++) {
            $random_attr = $available_itmsk[array_rand($available_itmsk)];
            if(!in_array($random_attr, $selected_attrs)) {
                $selected_attrs[] = $random_attr;
            }
        }
        $itmsk0 = implode('', $selected_attrs);
    }

    $log .= "<span class=\"cyan\">【全随机模式】合成物品属性已随机化！</span><br>";

    // 将修改后的变量写回$data数组
    $data['itme0'] = $itme0;
    $data['itms0'] = $itms0;
    $data['itmsk0'] = $itmsk0;
}

/**
 * 检查地图物品刷新是否需要随机化
 */
function ruleset_should_randomize_item($imap, $iarea, $an) {
    // 在全随机模式下，所有物品都随机刷新
    return is_all_random_mode();
}

/**
 * 检查NPC刷新是否需要随机化
 */
function ruleset_should_randomize_npc($npc_pls) {
    // 在全随机模式下，所有NPC都随机刷新
    return is_all_random_mode();
}

/**
 * 获取随机NPC位置
 */
function ruleset_get_random_npc_location($plsnum) {
    $rmap = rand(1, $plsnum-1);
    while ($rmap == 34) { // 排除地点34
        $rmap = rand(1, $plsnum-1);
    }
    return $rmap;
}

?>
