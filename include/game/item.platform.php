<?php

if (! defined('IN_GAME')) {
    exit('Access Denied');
}

/**
 * 处理 NPC 平台物品
 * NPC 平台允许玩家临时或永久地获取其他玩家或 NPC 的状态
 *
 * @param int $itmn 物品在物品栏中的位置
 * @param array &$data 玩家数据
 */
function item_platform($itmn, &$data) {
    global $log, $db, $tablepre, $nosta;
    extract($data, EXTR_REFS);

    $itm = & ${'itm' . $itmn};
    $itmk = & ${'itmk' . $itmn};
    $itme = & ${'itme' . $itmn};
    $itms = & ${'itms' . $itmn};
    $itmsk = & ${'itmsk' . $itmn};
    $itmpara = & get_itmpara(${'itmpara' . $itmn});

    // 记录玩家原始名称，以便在日志中使用
    $playerOriginalName = $name;

    // 初始化平台数据数组
    $platformData = array();

    // 确定数据来源：PID模式或预设数据模式
    if (!empty($itmpara['platformPlayerMode']) && $itmpara['platformPlayerMode'] == 'PID') {
        // PID模式：从数据库获取目标玩家数据
        if (!empty($itmpara['targetPID'])) {
            $targetPID = $itmpara['targetPID'];
            $result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$targetPID'");
            if ($db->num_rows($result)) {
                $platformData = $db->fetch_array($result);
                $log .= "NPC平台正在连接到目标玩家数据...<br>";
            } else {
                $log .= "错误：无法找到指定的目标玩家数据。<br>";
                return;
            }
        } else {
            $log .= "错误：未指定目标玩家ID。<br>";
            return;
        }
    } else {
        // 预设数据模式：从物品参数中获取预设的玩家数据
        $platformData = extractPlatformPlayerData($itmpara);
        if (empty($platformData)) {
            $log .= "错误：NPC平台中没有有效的预设数据。<br>";
            return;
        }
        $log .= "NPC平台正在加载预设数据...<br>";
    }

    // 检查是否为临时变身
    $isTemporary = isset($itmpara['PlatformIsTimed']);

    // 如果是临时变身，保存玩家原始数据
    if ($isTemporary) {
        savePlayerOriginalData($data);
        $log .= "你的原始数据已保存，变身将在一定时间后恢复。<br>";
    } else {
        $log .= "警告：这是永久性变身，你的原始数据将不会被保存！<br>";
    }

    // 应用平台数据到玩家
    applyPlatformDataToPlayer($data, $platformData);

    // 记录变身日志
    $targetName = !empty($platformData['name']) ? $platformData['name'] : "未知NPC";
    $log .= "变身成功！你现在是 <span class=\"yellow\">{$targetName}</span>。<br>";

    // 消耗物品
    if ($itms != $nosta) {
        $itms--;
        if ($itms <= 0) {
            $log .= "<span class=\"red\">{$itm}</span>用光了。<br>";
            $itm = $itmk = $itmsk = '';
            $itme = $itms = 0;
            ${'itmpara' . $itmn} = '';
        }
    }
}

/**
 * 从物品参数中提取预设的玩家数据
 *
 * @param array $itmpara 物品参数
 * @return array 提取的玩家数据
 */
function extractPlatformPlayerData($itmpara) {
    $platformData = array();
    $playerFields = array(
        'name', 'type', 'hp', 'mhp', 'sp', 'msp', 'att', 'def', 'pls', 'lvl', 'exp',
        'money', 'rage', 'pose', 'tactic', 'club', 'icon', 'gender', 'sNo', 'teamID',
        'wep', 'wepk', 'wepe', 'weps', 'wepsk', 'arb', 'arbk', 'arbe', 'arbs', 'arbsk',
        'arh', 'arhk', 'arhe', 'arhs', 'arhsk', 'ara', 'arak', 'arae', 'aras', 'arask',
        'arf', 'arfk', 'arfe', 'arfs', 'arfsk', 'art', 'artk', 'arte', 'arts', 'artsk',
        'itm1', 'itmk1', 'itme1', 'itms1', 'itmsk1', 'itm2', 'itmk2', 'itme2', 'itms2', 'itmsk2',
        'itm3', 'itmk3', 'itme3', 'itms3', 'itmsk3', 'itm4', 'itmk4', 'itme4', 'itms4', 'itmsk4',
        'itm5', 'itmk5', 'itme5', 'itms5', 'itmsk5', 'itm6', 'itmk6', 'itme6', 'itms6', 'itmsk6',
        'wep2', 'wep2k', 'wep2e', 'wep2s', 'wep2sk', 'wep2c', 'ss', 'mss', 'inf', 'skill',
        'wp', 'wk', 'wg', 'wc', 'wd', 'wf', 'killnum', 'state'
    );

    foreach ($playerFields as $field) {
        $platformKey = 'PlatformPlayer' . ucfirst($field);
        if (isset($itmpara[$platformKey])) {
            $platformData[$field] = $itmpara[$platformKey];
        }
    }

    return $platformData;
}

/**
 * 保存玩家原始数据到 clbpara 数组
 *
 * @param array &$data 玩家数据
 */
function savePlayerOriginalData(&$data) {
    extract($data, EXTR_REFS);

    // 保存需要恢复的字段
    $fieldsToSave = array(
        'name', 'type', 'hp', 'mhp', 'sp', 'msp', 'att', 'def', 'pls', 'lvl', 'exp',
        'money', 'rage', 'pose', 'tactic', 'club', 'icon', 'gender', 'sNo', 'teamID',
        'wep', 'wepk', 'wepe', 'weps', 'wepsk', 'arb', 'arbk', 'arbe', 'arbs', 'arbsk',
        'arh', 'arhk', 'arhe', 'arhs', 'arhsk', 'ara', 'arak', 'arae', 'aras', 'arask',
        'arf', 'arfk', 'arfe', 'arfs', 'arfsk', 'art', 'artk', 'arte', 'arts', 'artsk',
        'itm1', 'itmk1', 'itme1', 'itms1', 'itmsk1', 'itm2', 'itmk2', 'itme2', 'itms2', 'itmsk2',
        'itm3', 'itmk3', 'itme3', 'itms3', 'itmsk3', 'itm4', 'itmk4', 'itme4', 'itms4', 'itmsk4',
        'itm5', 'itmk5', 'itme5', 'itms5', 'itmsk5', 'itm6', 'itmk6', 'itme6', 'itms6', 'itmsk6',
        'wep2', 'wep2k', 'wep2e', 'wep2s', 'wep2sk', 'wep2c', 'ss', 'mss', 'inf', 'skill',
        'wp', 'wk', 'wg', 'wc', 'wd', 'wf', 'killnum', 'state'
    );

    foreach ($fieldsToSave as $field) {
        if (isset($$field)) {
            $clbpara['ori' . ucfirst($field)] = $$field;
        }
    }

    // 设置恢复标记和时间
    $clbpara['platformTransformed'] = true;
    $clbpara['platformTransformTime'] = time();
}

/**
 * 将平台数据应用到玩家
 *
 * @param array &$data 玩家数据
 * @param array $platformData 平台数据
 */
function applyPlatformDataToPlayer(&$data, $platformData) {
    extract($data, EXTR_REFS);

    // 应用平台数据到玩家
    foreach ($platformData as $field => $value) {
        if (isset($$field)) {
            $$field = $value;
        }
    }
}

/**
 * 恢复玩家原始数据
 * 用于解除NPC变身状态，将保存在clbpara中的原始数据重新赋值给玩家
 *
 * @param array &$data 玩家数据
 * @return bool 是否成功恢复数据
 */
function restorePlayerOriginalData(&$data) {
    global $log;
    extract($data, EXTR_REFS);

    // 检查是否有保存的原始数据
    if (!isset($clbpara['platformTransformed']) || $clbpara['platformTransformed'] !== true) {
        $log .= "没有找到保存的原始数据，无法恢复。<br>";
        return false;
    }

    // 获取保存的字段列表
    $fieldsToRestore = array(
        'name', 'type', 'hp', 'mhp', 'sp', 'msp', 'att', 'def', 'pls', 'lvl', 'exp',
        'money', 'rage', 'pose', 'tactic', 'club', 'icon', 'gender', 'sNo', 'teamID',
        'wep', 'wepk', 'wepe', 'weps', 'wepsk', 'arb', 'arbk', 'arbe', 'arbs', 'arbsk',
        'arh', 'arhk', 'arhe', 'arhs', 'arhsk', 'ara', 'arak', 'arae', 'aras', 'arask',
        'arf', 'arfk', 'arfe', 'arfs', 'arfsk', 'art', 'artk', 'arte', 'arts', 'artsk',
        'itm1', 'itmk1', 'itme1', 'itms1', 'itmsk1', 'itm2', 'itmk2', 'itme2', 'itms2', 'itmsk2',
        'itm3', 'itmk3', 'itme3', 'itms3', 'itmsk3', 'itm4', 'itmk4', 'itme4', 'itms4', 'itmsk4',
        'itm5', 'itmk5', 'itme5', 'itms5', 'itmsk5', 'itm6', 'itmk6', 'itme6', 'itms6', 'itmsk6',
        'wep2', 'wep2k', 'wep2e', 'wep2s', 'wep2sk', 'wep2c', 'ss', 'mss', 'inf', 'skill',
        'wp', 'wk', 'wg', 'wc', 'wd', 'wf', 'killnum', 'state'
    );

    // 恢复原始数据
    $restoredFields = 0;
    foreach ($fieldsToRestore as $field) {
        $oriField = 'ori' . ucfirst($field);
        if (isset($clbpara[$oriField])) {
            $$field = $clbpara[$oriField];
            unset($clbpara[$oriField]); // 清除已恢复的数据
            $restoredFields++;
        }
    }

    // 清除变身标记
    unset($clbpara['platformTransformed']);
    unset($clbpara['platformTransformTime']);

    // 记录恢复日志
    if ($restoredFields > 0) {
        $log .= "变身状态已解除，你恢复了原来的样子。<br>";
        return true;
    } else {
        $log .= "尝试恢复原始数据，但没有找到有效的数据。<br>";
        return false;
    }
}
