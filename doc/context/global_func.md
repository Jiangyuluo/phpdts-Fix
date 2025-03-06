# global.func.php 函数分析

`global.func.php`是全局函数库，提供全局可用的通用函数，支持游戏系统的各个部分。

## 函数列表

### 游戏核心功能

#### `gamecfg($key = '')`
- **功能**: 获取游戏配置
- **参数**: `$key`: 配置键名
- **返回值**: 配置值或整个配置数组

#### `get_gameinfo($gid)`
- **功能**: 获取游戏信息
- **参数**: `$gid`: 游戏ID
- **返回值**: 游戏信息数组

#### `get_gamenum()`
- **功能**: 获取游戏数量
- **参数**: 无
- **返回值**: 游戏数量

#### `get_gamelist()`
- **功能**: 获取游戏列表
- **参数**: 无
- **返回值**: 游戏列表数组

#### `get_gameinfo_by_gname($gname)`
- **功能**: 通过游戏名称获取游戏信息
- **参数**: `$gname`: 游戏名称
- **返回值**: 游戏信息数组

### 用户相关功能

#### `get_userinfo($uid, $fields = '*')`
- **功能**: 获取用户信息
- **参数**: 
  - `$uid`: 用户ID
  - `$fields`: 需要获取的字段
- **返回值**: 用户信息数组

#### `get_userinfo_by_username($username, $fields = '*')`
- **功能**: 通过用户名获取用户信息
- **参数**: 
  - `$username`: 用户名
  - `$fields`: 需要获取的字段
- **返回值**: 用户信息数组

#### `update_user_info($uid, $data)`
- **功能**: 更新用户信息
- **参数**: 
  - `$uid`: 用户ID
  - `$data`: 更新数据
- **返回值**: 更新结果

#### `check_user_permission($uid, $permission)`
- **功能**: 检查用户权限
- **参数**: 
  - `$uid`: 用户ID
  - `$permission`: 权限名称
- **返回值**: 布尔值，表示是否有权限

### 游戏状态和进程

#### `get_game_status($gid)`
- **功能**: 获取游戏状态
- **参数**: `$gid`: 游戏ID
- **返回值**: 游戏状态信息

#### `update_game_status($gid, $status)`
- **功能**: 更新游戏状态
- **参数**: 
  - `$gid`: 游戏ID
  - `$status`: 新状态
- **返回值**: 更新结果

#### `get_player_status($uid, $gid)`
- **功能**: 获取玩家在特定游戏中的状态
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 玩家状态信息

### 物品和装备

#### `get_item_info($iid)`
- **功能**: 获取物品信息
- **参数**: `$iid`: 物品ID
- **返回值**: 物品信息数组

#### `get_equip_info($eid)`
- **功能**: 获取装备信息
- **参数**: `$eid`: 装备ID
- **返回值**: 装备信息数组

#### `get_player_items($uid, $gid)`
- **功能**: 获取玩家物品列表
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 物品列表数组

### 游戏地图和位置

#### `get_map_info($mid)`
- **功能**: 获取地图信息
- **参数**: `$mid`: 地图ID
- **返回值**: 地图信息数组

#### `get_area_info($aid)`
- **功能**: 获取区域信息
- **参数**: `$aid`: 区域ID
- **返回值**: 区域信息数组

#### `get_player_position($uid, $gid)`
- **功能**: 获取玩家位置
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 位置信息

### 战斗和技能

#### `calculate_damage($attacker, $defender, $skill = 0)`
- **功能**: 计算伤害值
- **参数**: 
  - `$attacker`: 攻击者信息
  - `$defender`: 防御者信息
  - `$skill`: 使用的技能
- **返回值**: 伤害值

#### `get_skill_info($sid)`
- **功能**: 获取技能信息
- **参数**: `$sid`: 技能ID
- **返回值**: 技能信息数组

#### `get_player_skills($uid, $gid)`
- **功能**: 获取玩家技能列表
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
- **返回值**: 技能列表数组

### 游戏事件和日志

#### `log_game_event($gid, $event_type, $event_data)`
- **功能**: 记录游戏事件
- **参数**: 
  - `$gid`: 游戏ID
  - `$event_type`: 事件类型
  - `$event_data`: 事件数据
- **返回值**: 记录结果

#### `get_game_events($gid, $limit = 10)`
- **功能**: 获取游戏事件列表
- **参数**: 
  - `$gid`: 游戏ID
  - `$limit`: 限制数量
- **返回值**: 事件列表数组

#### `log_player_action($uid, $gid, $action, $data)`
- **功能**: 记录玩家行动
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$action`: 行动类型
  - `$data`: 行动数据
- **返回值**: 记录结果

### 工具和辅助函数

#### `generate_random_event($gid, $area_id)`
- **功能**: 生成随机事件
- **参数**: 
  - `$gid`: 游戏ID
  - `$area_id`: 区域ID
- **返回值**: 事件信息

#### `calculate_experience($level, $action_type)`
- **功能**: 计算经验值
- **参数**: 
  - `$level`: 当前等级
  - `$action_type`: 行动类型
- **返回值**: 经验值

#### `check_level_up($uid, $gid, $exp)`
- **功能**: 检查是否升级
- **参数**: 
  - `$uid`: 用户ID
  - `$gid`: 游戏ID
  - `$exp`: 经验值
- **返回值**: 布尔值，表示是否升级

这只是部分函数的分析，完整的global.func.php文件包含更多全局函数，用于支持游戏系统的各个方面。