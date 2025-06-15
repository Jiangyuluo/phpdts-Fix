<?php

if(!defined('IN_GAME')) {
    exit('Access Denied');
}

/*
 * RuleSet系统剧情配置文件
 * 用于配置不同版本的开场和结束剧情
 */

$ruleset_stories = Array(
    'ACBRA_2009' => Array(
        'opening' => Array(
            'title' => '时光重现：ACBRA 2009',
            'content' => '
                <div class="story-content">
                    <h3>欢迎来到2009年的ACBRA世界！</h3>
                    <p>时光倒流，回到了那个最初的年代...</p>
                    <p>在这里，你将体验到最原始的大逃杀玩法：</p>
                    <ul>
                        <li>经典的武器系统和道具配置</li>
                        <li>原版的NPC和地图设置</li>
                        <li>怀旧的界面风格和音效</li>
                        <li>2009年的平衡性调整</li>
                    </ul>
                    <p>准备好重温那份最初的感动了吗？</p>
                    <p class="story-note">注意：本房间使用ACBRA 2009版本的游戏规则和资源。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '开始游戏', 'action' => 'close'),
            )
        ),
        'ending' => Array(
            'title' => '时光重现结束',
            'content' => '
                <div class="story-content">
                    <h3>2009年的冒险结束了</h3>
                    <p>感谢你体验了这段怀旧的时光重现之旅。</p>
                    <p>希望你在这个经典版本中找到了当年的感动。</p>
                    <p>时光荏苒，但经典永恒。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '返回大厅', 'action' => 'redirect', 'url' => 'index.php'),
            )
        )
    ),
    
    'ACDTS_2011' => Array(
        'opening' => Array(
            'title' => '时光重现：ACDTS 2011',
            'content' => '
                <div class="story-content">
                    <h3>穿越到2011年的ACDTS世界</h3>
                    <p>这里是ACDTS的黄金时代...</p>
                    <p>在这个版本中，你将体验到：</p>
                    <ul>
                        <li>2011年的独特系统设计</li>
                        <li>当时的特色道具和武器</li>
                        <li>经典的NPC配置</li>
                        <li>那个时代的游戏平衡</li>
                    </ul>
                    <p>让我们一起回到那个充满回忆的年代！</p>
                    <p class="story-note">注意：本房间使用ACDTS 2011版本的游戏规则和资源。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '进入游戏', 'action' => 'close'),
            )
        ),
        'ending' => Array(
            'title' => '2011年的回忆',
            'content' => '
                <div class="story-content">
                    <h3>ACDTS 2011的旅程结束</h3>
                    <p>你已经完成了这次时光重现的体验。</p>
                    <p>2011年的ACDTS承载着无数玩家的青春回忆。</p>
                    <p>希望这次旅程让你重新感受到了当年的快乐。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '返回现代', 'action' => 'redirect', 'url' => 'index.php'),
            )
        )
    ),
    
    'ACDTS_298SP4' => Array(
        'opening' => Array(
            'title' => '时光重现：ACDTS 298SP4',
            'content' => '
                <div class="story-content">
                    <h3>最后的经典：298SP4版本</h3>
                    <p>这是经典时代的最后辉煌...</p>
                    <p>298SP4版本包含了：</p>
                    <ul>
                        <li>最完善的经典系统</li>
                        <li>丰富的道具和装备</li>
                        <li>成熟的游戏机制</li>
                        <li>经典时代的巅峰体验</li>
                    </ul>
                    <p>这是告别过去，迎接未来的最后一站。</p>
                    <p class="story-note">注意：本房间使用ACDTS 298SP4版本的游戏规则和资源。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '最后一战', 'action' => 'close'),
            )
        ),
        'ending' => Array(
            'title' => '经典时代的终章',
            'content' => '
                <div class="story-content">
                    <h3>298SP4的传奇落下帷幕</h3>
                    <p>你见证了经典时代的最后辉煌。</p>
                    <p>298SP4代表着一个时代的结束，也是新时代的开始。</p>
                    <p>感谢你参与了这段珍贵的历史重现。</p>
                    <p>愿经典永远在我们心中闪耀。</p>
                </div>
            ',
            'buttons' => Array(
                Array('text' => '踏向未来', 'action' => 'redirect', 'url' => 'index.php'),
            )
        )
    )
);

// 获取指定RuleSet的剧情配置
function get_ruleset_story($ruleset_id, $story_type = 'opening') {
    global $ruleset_stories;
    
    if (isset($ruleset_stories[$ruleset_id]) && isset($ruleset_stories[$ruleset_id][$story_type])) {
        return $ruleset_stories[$ruleset_id][$story_type];
    }
    
    return false;
}

// 检查RuleSet是否有自定义剧情
function has_ruleset_story($ruleset_id) {
    global $ruleset_stories;
    
    return isset($ruleset_stories[$ruleset_id]);
}

?>
