# messages.func.php 函数分析

`messages.func.php`是消息系统函数库，负责处理游戏中的消息发送、接收和管理。

## 函数列表

### 消息发送和创建

#### `send_message($sender_id, $receiver_id, $subject, $content, $type = 'normal')`
- **功能**: 发送消息
- **参数**: 
  - `$sender_id`: 发送者ID
  - `$receiver_id`: 接收者ID
  - `$subject`: 消息主题
  - `$content`: 消息内容
  - `$type`: 消息类型
- **返回值**: 消息ID或错误信息

#### `send_system_message($receiver_id, $subject, $content, $type = 'system')`
- **功能**: 发送系统消息
- **参数**: 
  - `$receiver_id`: 接收者ID
  - `$subject`: 消息主题
  - `$content`: 消息内容
  - `$type`: 消息类型
- **返回值**: 消息ID或错误信息

#### `send_group_message($sender_id, $group_id, $subject, $content, $type = 'group')`
- **功能**: 发送群组消息
- **参数**: 
  - `$sender_id`: 发送者ID
  - `$group_id`: 群组ID
  - `$subject`: 消息主题
  - `$content`: 消息内容
  - `$type`: 消息类型
- **返回值**: 消息ID或错误信息

#### `broadcast_message($sender_id, $subject, $content, $receivers = array(), $type = 'broadcast')`
- **功能**: 广播消息
- **参数**: 
  - `$sender_id`: 发送者ID
  - `$subject`: 消息主题
  - `$content`: 消息内容
  - `$receivers`: 接收者列表
  - `$type`: 消息类型
- **返回值**: 发送结果

### 消息获取和查询

#### `get_message($message_id, $user_id = 0)`
- **功能**: 获取消息
- **参数**: 
  - `$message_id`: 消息ID
  - `$user_id`: 用户ID
- **返回值**: 消息信息数组

#### `get_user_messages($user_id, $folder = 'inbox', $status = 0, $limit = 20, $offset = 0)`
- **功能**: 获取用户消息
- **参数**: 
  - `$user_id`: 用户ID
  - `$folder`: 文件夹
  - `$status`: 消息状态
  - `$limit`: 限制数量
  - `$offset`: 偏移量
- **返回值**: 消息列表数组

#### `get_unread_messages_count($user_id)`
- **功能**: 获取未读消息数量
- **参数**: `$user_id`: 用户ID
- **返回值**: 未读消息数量

#### `search_messages($user_id, $keywords, $folder = 'all', $limit = 20, $offset = 0)`
- **功能**: 搜索消息
- **参数**: 
  - `$user_id`: 用户ID
  - `$keywords`: 关键词
  - `$folder`: 文件夹
  - `$limit`: 限制数量
  - `$offset`: 偏移量
- **返回值**: 搜索结果数组

### 消息状态和操作

#### `mark_message_read($message_id, $user_id)`
- **功能**: 标记消息为已读
- **参数**: 
  - `$message_id`: 消息ID
  - `$user_id`: 用户ID
- **返回值**: 标记结果

#### `mark_all_messages_read($user_id, $folder = 'inbox')`
- **功能**: 标记所有消息为已读
- **参数**: 
  - `$user_id`: 用户ID
  - `$folder`: 文件夹
- **返回值**: 标记结果

#### `delete_message($message_id, $user_id, $permanently = false)`
- **功能**: 删除消息
- **参数**: 
  - `$message_id`: 消息ID
  - `$user_id`: 用户ID
  - `$permanently`: 是否永久删除
- **返回值**: 删除结果

#### `empty_trash($user_id)`
- **功能**: 清空垃圾箱
- **参数**: `$user_id`: 用户ID
- **返回值**: 清空结果

### 消息回复和转发

#### `reply_message($original_message_id, $user_id, $content, $quote_original = true)`
- **功能**: 回复消息
- **参数**: 
  - `$original_message_id`: 原始消息ID
  - `$user_id`: 用户ID
  - `$content`: 回复内容
  - `$quote_original`: 是否引用原消息
- **返回值**: 回复结果

#### `forward_message($message_id, $user_id, $receiver_id, $additional_content = '')`
- **功能**: 转发消息
- **参数**: 
  - `$message_id`: 消息ID
  - `$user_id`: 用户ID
  - `$receiver_id`: 接收者ID
  - `$additional_content`: 附加内容
- **返回值**: 转发结果

#### `create_conversation($user_id, $participants, $subject, $initial_message)`
- **功能**: 创建对话
- **参数**: 
  - `$user_id`: 用户ID
  - `$participants`: 参与者列表
  - `$subject`: 对话主题
  - `$initial_message`: 初始消息
- **返回值**: 对话ID或错误信息

### 消息通知和提醒

#### `notify_new_message($user_id, $message_id)`
- **功能**: 通知新消息
- **参数**: 
  - `$user_id`: 用户ID
  - `$message_id`: 消息ID
- **返回值**: 通知结果

#### `get_message_notifications($user_id, $limit = 10)`
- **功能**: 获取消息通知
- **参数**: 
  - `$user_id`: 用户ID
  - `$limit`: 限制数量
- **返回值**: 通知列表数组

#### `clear_message_notification($notification_id, $user_id)`
- **功能**: 清除消息通知
- **参数**: 
  - `$notification_id`: 通知ID
  - `$user_id`: 用户ID
- **返回值**: 清除结果

#### `clear_all_message_notifications($user_id)`
- **功能**: 清除所有消息通知
- **参数**: `$user_id`: 用户ID
- **返回值**: 清除结果

### 消息附件和格式

#### `add_message_attachment($message_id, $file_path, $file_name, $file_type)`
- **功能**: 添加消息附件
- **参数**: 
  - `$message_id`: 消息ID
  - `$file_path`: 文件路径
  - `$file_name`: 文件名
  - `$file_type`: 文件类型
- **返回值**: 附件ID或错误信息

#### `get_message_attachments($message_id)`
- **功能**: 获取消息附件
- **参数**: `$message_id`: 消息ID
- **返回值**: 附件列表数组

#### `format_message_content($content, $format = 'html')`
- **功能**: 格式化消息内容
- **参数**: 
  - `$content`: 消息内容
  - `$format`: 格式类型
- **返回值**: 格式化后的内容

#### `parse_message_bbcode($content)`
- **功能**: 解析消息BBCode
- **参数**: `$content`: 消息内容
- **返回值**: 解析后的内容

### 消息权限和安全

#### `can_send_message($sender_id, $receiver_id)`
- **功能**: 检查是否可以发送消息
- **参数**: 
  - `$sender_id`: 发送者ID
  - `$receiver_id`: 接收者ID
- **返回值**: 布尔值，表示是否可以发送

#### `can_read_message($user_id, $message_id)`
- **功能**: 检查是否可以阅读消息
- **参数**: 
  - `$user_id`: 用户ID
  - `$message_id`: 消息ID
- **返回值**: 布尔值，表示是否可以阅读

#### `block_user_messages($user_id, $blocked_user_id)`
- **功能**: 屏蔽用户消息
- **参数**: 
  - `$user_id`: 用户ID
  - `$blocked_user_id`: 被屏蔽用户ID
- **返回值**: 屏蔽结果

#### `unblock_user_messages($user_id, $blocked_user_id)`
- **功能**: 解除用户消息屏蔽
- **参数**: 
  - `$user_id`: 用户ID
  - `$blocked_user_id`: 被屏蔽用户ID
- **返回值**: 解除结果

这些函数共同构成了一个完整的消息系统，用于处理游戏中的用户间通信、系统通知和消息管理等功能。