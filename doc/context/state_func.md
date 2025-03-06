# state.func.php 函数分析

`state.func.php`是游戏状态管理函数库，负责处理游戏中各种状态的变化和管理。

## 函数列表

### 状态初始化和加载

#### `init_game_state($gid)`
- **功能**: 初始化游戏状态
- **参数**: `$gid`: 游戏ID
- **返回值**: 初始化结果

#### `load_game_state($gid)`
- **功能**: 加载游戏状态
- **参数**: `$gid`: 游戏ID
- **返回值**: 游戏状态数组

#### `save_game_state($gid, $state)`
- **功能**: 保存游戏状态
- **参数**: 
  - `$gid`: 游戏ID
  - `$state`: 状态数据
- **返回值**: 保存结果

#### `reset_game_state($gid)`
- **功能**: 重置游戏状态
- **参数**: `$gid`: 游戏ID
- **返回值**: 重置结果

### 玩家状态管理

#### `get_player_state($uid, $gid)`
- **功能**: 获取玩家状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 玩家状态数组

#### `update_player_state($uid, $gid, $state)`
- **功能**: 更新玩家状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state`: 新状态
- **返回值**: 更新结果

#### `add_player_state($uid, $gid, $state_type, $duration, $effect)`
- **功能**: 添加玩家状态效果
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_type`: 状态类型
  - `$duration`: 持续时间
  - `$effect`: 效果数据
- **返回值**: 添加结果

#### `remove_player_state($uid, $gid, $state_id)`
- **功能**: 移除玩家状态效果
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_id`: 状态ID
- **返回值**: 移除结果

### 状态效果处理

#### `apply_state_effects($uid, $gid)`
- **功能**: 应用状态效果
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 应用结果

#### `update_state_durations($gid)`
- **功能**: 更新状态持续时间
- **参数**: `$gid`: 游戏ID
- **返回值**: 更新结果

#### `check_state_expiration($gid)`
- **功能**: 检查状态是否过期
- **参数**: `$gid`: 游戏ID
- **返回值**: 过期状态列表

#### `handle_expired_states($gid)`
- **功能**: 处理过期状态
- **参数**: `$gid`: 游戏ID
- **返回值**: 处理结果

### 特定状态类型

#### `apply_buff_state($uid, $gid, $buff_type, $value, $duration)`
- **功能**: 应用增益状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$buff_type`: 增益类型
  - `$value`: 增益值
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_debuff_state($uid, $gid, $debuff_type, $value, $duration)`
- **功能**: 应用减益状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$debuff_type`: 减益类型
  - `$value`: 减益值
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_dot_state($uid, $gid, $damage, $interval, $duration)`
- **功能**: 应用持续伤害状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$damage`: 伤害值
  - `$interval`: 伤害间隔
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_hot_state($uid, $gid, $healing, $interval, $duration)`
- **功能**: 应用持续治疗状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$healing`: 治疗值
  - `$interval`: 治疗间隔
  - `$duration`: 持续时间
- **返回值**: 应用结果

### 控制状态

#### `apply_stun_state($uid, $gid, $duration)`
- **功能**: 应用眩晕状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_silence_state($uid, $gid, $duration)`
- **功能**: 应用沉默状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_root_state($uid, $gid, $duration)`
- **功能**: 应用定身状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$duration`: 持续时间
- **返回值**: 应用结果

#### `apply_disarm_state($uid, $gid, $duration)`
- **功能**: 应用缴械状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$duration`: 持续时间
- **返回值**: 应用结果

### 状态抵抗和清除

#### `check_state_resistance($uid, $gid, $state_type)`
- **功能**: 检查状态抵抗
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_type`: 状态类型
- **返回值**: 布尔值，表示是否抵抗

#### `clear_all_states($uid, $gid)`
- **功能**: 清除所有状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 清除结果

#### `clear_debuff_states($uid, $gid)`
- **功能**: 清除所有减益状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 清除结果

#### `clear_control_states($uid, $gid)`
- **功能**: 清除所有控制状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 清除结果

### 状态查询和检查

#### `has_state($uid, $gid, $state_type)`
- **功能**: 检查玩家是否有特定状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_type`: 状态类型
- **返回值**: 布尔值，表示是否有该状态

#### `get_state_effect($uid, $gid, $state_type)`
- **功能**: 获取状态效果值
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_type`: 状态类型
- **返回值**: 效果值

#### `get_state_duration($uid, $gid, $state_id)`
- **功能**: 获取状态剩余持续时间
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_id`: 状态ID
- **返回值**: 剩余持续时间

#### `is_state_active($uid, $gid, $state_id)`
- **功能**: 检查状态是否激活
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_id`: 状态ID
- **返回值**: 布尔值，表示状态是否激活

### 状态通知和显示

#### `notify_state_change($uid, $gid, $state_id, $action)`
- **功能**: 通知状态变化
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_id`: 状态ID
  - `$action`: 变化动作
- **返回值**: 通知结果

#### `get_state_icon($state_type)`
- **功能**: 获取状态图标
- **参数**: `$state_type`: 状态类型
- **返回值**: 图标路径

#### `get_state_description($state_type, $value = 0)`
- **功能**: 获取状态描述
- **参数**: 
  - `$state_type`: 状态类型
  - `$value`: 状态值
- **返回值**: 状态描述

#### `format_state_display($uid, $gid, $state_id)`
- **功能**: 格式化状态显示
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$state_id`: 状态ID
- **返回值**: 格式化的状态显示

这只是部分函数的分析，完整的state.func.php文件包含更多状态管理函数，用于支持游戏中各种状态效果的处理和管理。