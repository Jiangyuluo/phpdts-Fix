<?php

if(!defined('IN_GAME')) {
    exit('Access Denied');
}

/**
 * 处理nouveau版本中加入的特殊物品
 * 包括鱼篓子等
 *
 * @param int $itmn 物品在物品栏中的位置
 * @param array &$data 玩家数据
 * @return bool 是否成功处理
 */
function item_nouveau_booster1($itmn, &$data) {
    global $log, $mode, $cmd, $command;
    extract($data, EXTR_REFS);

    // 如果是选择鱼篓子中的物品
    if ($command == 'choose_fish' && isset($clbpara['fish_basket'])) {
        // 获取鱼篓子的位置和物品
        $basket_position = $clbpara['fish_basket']['position'];
        $basket_items = $clbpara['fish_basket']['items'];

        // 获取选择的物品索引
        $chosen_index = isset($_POST['choose']) ? intval($_POST['choose']) : -1;

        if ($chosen_index < 0 || !isset($basket_items[$chosen_index])) {
            $log .= '你没有选择任何物品，或者选择的物品不存在。<br>';
            $mode = 'command';
            $cmd = '';
            return true;
        }

        // 获取选择的物品
        $chosen_item = $basket_items[$chosen_index];

        // 将物品放入玩家物品栏
        global $itm0, $itmk0, $itme0, $itms0, $itmsk0, $itmpara0;
        $itm0 = $chosen_item['itm'];
        $itmk0 = $chosen_item['itmk'];
        $itme0 = $chosen_item['itme'];
        $itms0 = $chosen_item['itms'];
        $itmsk0 = $chosen_item['itmsk'];
        $itmpara0 = isset($chosen_item['itmpara']) ? $chosen_item['itmpara'] : '';

        // 从鱼篓子中移除该物品
        unset($basket_items[$chosen_index]);

        // 重新索引数组
        $basket_items = array_values($basket_items);

        // 更新鱼篓子
        if (empty($basket_items)) {
            // 如果鱼篓子为空，移除它
            $log .= '你从鱼篓子中取出了<span class="yellow">' . $itm0 . '</span>。<br>';
            $log .= '鱼篓子已经空了，你将它丢弃了。<br>';

            ${'itm'.$basket_position} = '';
            ${'itmk'.$basket_position} = '';
            ${'itme'.$basket_position} = 0;
            ${'itms'.$basket_position} = 0;
            ${'itmsk'.$basket_position} = '';
            ${'itmpara'.$basket_position} = '';
        } else {
            // 更新鱼篓子的内容
            ${'itme'.$basket_position} = count($basket_items); // 更新容量
            ${'itms'.$basket_position} = count($basket_items); // 更新已使用空间
            ${'itmpara'.$basket_position} = json_encode($basket_items, JSON_UNESCAPED_UNICODE);

            $log .= '你从鱼篓子中取出了<span class="yellow">' . $itm0 . '</span>。<br>';
            $log .= '鱼篓子中还有 ' . count($basket_items) . ' 件物品。<br>';
        }

        // 清除临时数据
        unset($clbpara['fish_basket']);

        // 调用物品获取函数
        include_once GAME_ROOT.'./include/game/itemmain.func.php';
        itemget($data);

        $mode = 'command';
        $cmd = '';
        return true;
    }

    // 如果是打开鱼篓子
    $itm = ${'itm' . $itmn};
    $itmk = ${'itmk' . $itmn};
    $itmsk = ${'itmsk' . $itmn};
    $itmpara = ${'itmpara' . $itmn};

    if ($itm == '鱼篓子' && $itmk == 'Z' && $itmsk == 'Z') {
        // 解析鱼篓子中的物品
        $basket_items = json_decode($itmpara, true);
        if (!$basket_items || empty($basket_items)) {
            $log .= '这个鱼篓子是空的！<br>';
            return true;
        }

        // 设置为特殊模式，显示鱼篓子界面
        $mode = 'fish_basket';
        $cmd = '';

        // 保存鱼篓子的位置，以便后续处理
        $clbpara['fish_basket'] = array(
            'position' => $itmn,
            'items' => $basket_items
        );

        // 返回 true，表示已经处理了鱼篓子
        return true;
    }

    // 如果没有匹配的物品，返回 false
    return false;
}

?>
