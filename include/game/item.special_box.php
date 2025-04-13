<?php

if (! defined('IN_GAME')) {
    exit('Access Denied');
}

/**
 * 处理特殊物品箱
 * 这些物品会提供各种随机效果
 * 
 * @param int $itmn 物品在物品栏中的位置
 * @param array &$data 玩家数据
 */
function item_special_box($itmn, &$data) {
    global $log, $db, $tablepre, $now;
    extract($data, EXTR_REFS);
    
    $itm = & ${'itm' . $itmn};
    $itmk = & ${'itmk' . $itmn};
    $itme = & ${'itme' . $itmn};
    $itms = & ${'itms' . $itmn};
    $itmsk = & ${'itmsk' . $itmn};
    
    if ($itm == '小叶子的妙妙箱') {
       // A multiuse item that will provide various of items for you, mainly traps.
			// However, there will be an increasing possibity that this item will self-explode.
			// And when it does, there will also be a possibity that you'll lose HP and SP.
			// Very low chance of insta-death.

			//init itm0.
			$itm0 = '';
			$itmk0 = '';
			$itme0 = 0;
			$itms0 = 0;
			$itmsk0 = '';
			$itmpara = '';

			//Par 低维生物's suggestion, the explode-rate will be stored in its $itmsk.
			$log.="你下定决心，打开了这个可疑的<span class='yellow'>$itm</span>，开始翻找起来……<br>";
			//Getting the item's current self-destruct rate.
			$harukaBoxExplodeRate = intval($itmsk);
			//Generate a random number based on the user's 1st Yume value.
			$harukaBoxCheck = diceroll($clbpara['randver1']);

			if ($harukaBoxCheck <= 17){
				//Get random low-mid effect trap.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>略微有趣的陷阱</span>的东西！<br>";

				$itm0 = '略微有趣的玻璃珠';
				$itmk0 = 'TN';
				$itme0 = diceroll($clbpara['randver1']);
				$itms0 = diceroll(5);
				$itmsk0 = '';
			}elseif ($harukaBoxCheck <= 23){
				//Get random HB item.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>有趣的补给</span>的东西！<br>";

				$itm0 = '有趣的零食';
				$itmk0 = 'HB';
				$itme0 = diceroll($clbpara['randver1']) * diceroll(3);
				$itms0 = diceroll(17);
				$itmsk0 = 'z';
			}elseif ($harukaBoxCheck <= 42){
				// Get random mid effect true damage trap.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>精心制作的陷阱</span>的东西！<br>";

				$itm0 = '精心制作的玻璃珠阵';
				$itmk0 = 'TNt';
				$itme0 = diceroll($clbpara['randver2']);
				$itms0 = diceroll(5);
				$itmsk0 = '';
			}elseif ($harukaBoxCheck <= 61){
				// Get random high effect trap.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>非常有趣的陷阱</span>的东西！<br>";

				$itm0 = '非常有趣的玻璃珠';
				$itmk0 = 'TN';
				$itme0 = diceroll($clbpara['randver3']);
				$itms0 = diceroll(5);
				$itmsk0 = '';				
			}elseif ($harukaBoxCheck <= 80){
				// Get random percent damage trap.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>十分强力的陷阱</span>的东西！<br>";

				$itm0 = '强而有力的玻璃珠';
				$itmk0 = 'TN8';
				$itme0 = 1;
				$itms0 = diceroll(2);
				$itmsk0 = 'x';				
			}elseif ($harukaBoxCheck <= 109){
				// Get high true damage trap.
				$log.="你从里面翻找出了看起来能作为<span class='yellow'>精心制作的可怕陷阱</span>的东西！<br>";

				$itm0 = '精心制作的可怕玻璃珠阵';
				$itmk0 = 'TNt';
				$itme0 = diceroll($clbpara['randver3']);
				$itms0 = diceroll(5);
				$itmsk0 = '';
			}else{
				// Get Chaos Normal Trap.
				$log.="你从里面翻找出了一些<span class='yellow'>不可名状</span>的东西！<br>它似乎可以当作陷阱使用……<br>";

				$itm0 = '不可名状之物';
				$itmk0 = 'TN';
				$itme0 = diceroll(114514);
				$itms0 = diceroll(69);
				$itmsk0 = '';
			}

			//Troll the player if itms0 somehow rolled an 0. YSK: I encountered that 4 times in a row.
			if ($itms0 == 0){
				$log.="然而，<span class='yellow'>$itm0</span>却伴随着一阵少女银铃般的笑声，<br>在你的手上化作一阵青烟消失了！<br>靠！<br>";
				$itm0 = '';
				$itmk0 = '';
				$itme0 = 0;
				$itms0 = 0;
				$itmsk0 = '';

				//Refund some of explode rate.
				//$harukaBoxCheck -= 30;
			}

			//Add to explode rate.
			$harukaBoxExplodeRate += $harukaBoxCheck;
			if ($harukaBoxExplodeRate < 667){
				$log.="<span class='yellow'>妙妙箱不怀好意地颤抖了一下。</span>但最终什么都没发生！<br>";
				//Write explode rate back to itmsk.
				$itmsk = strval($harukaBoxExplodeRate);
			}else{
				//BOOM!!
				$log.="<span class='yellow'>妙妙箱不怀好意地颤抖了一下。</span>然后华丽地在你的手上炸开了！<br>";
				//Destroy this item.
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				//Also Destroy item0.
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;				
				//Get damage.
				$harukaBoxDamage = diceroll($clbpara['randver2']) * (diceroll(3) + 1);
				//Calculate Damage.
				if ($hp < $harukaBoxDamage){
					$dflag = diceroll(1024);
					if ($dflag > 1020){
						//YOU WA SHOCK!!
						include_once GAME_ROOT . './include/state.func.php';
						$log .= '你在一片火焰中失去了知觉。<br>';
						death ( 'event', '', 0, $itm );
					}else{
						$log .= "你受到了<span class='yellow'>巨大的</span>伤害！你感觉你整个人都要折在这里了！<br>";
						$hp = 1;
						$sp = 1;
					}
				}else{
					$hp -= $harukaBoxDamage;
					$sp -= $harukaBoxDamage;
					if ($sp < 1){
						$sp = 1;
					}
					$log .= "你受到了<span class='yellow'>$harukaBoxDamage</span>点伤害！<br>";
					$inf .= 'a';
					$log .= "你的双手也被炸得血肉模糊！真是不幸啊！<br>";
				}
    }
}
}
