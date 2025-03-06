# db_mysqli.class.php 类分析

`db_mysqli.class.php`是基于MySQLi的数据库操作类，提供了数据库连接和操作的核心功能。

## 类结构

### dbstuff 类

这是主要的数据库操作类，基于MySQLi扩展实现。

#### 属性

- **$querynum** - 查询次数计数器
- **$link** - MySQLi连接对象
- **$charset** - 字符集
- **$version** - 数据库版本
- **$querylog** - 查询日志数组
- **$debug** - 调试模式开关
- **$safecheck** - 安全检查开关

#### 方法

##### 连接和初始化

###### `connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $halt = TRUE)`
- **功能**: 连接数据库
- **参数**: 
  - `$dbhost`: 数据库主机
  - `$dbuser`: 数据库用户名
  - `$dbpw`: 数据库密码
  - `$dbname`: 数据库名
  - `$dbcharset`: 字符集
  - `$pconnect`: 是否持久连接
  - `$halt`: 错误时是否终止
- **返回值**: 连接对象

###### `select_db($dbname)`
- **功能**: 选择数据库
- **参数**: `$dbname`: 数据库名
- **返回值**: 布尔值，表示操作是否成功

###### `set_charset($charset)`
- **功能**: 设置字符集
- **参数**: `$charset`: 字符集
- **返回值**: 布尔值，表示操作是否成功

##### 查询操作

###### `query($sql, $type = '', $cachetime = FALSE)`
- **功能**: 执行SQL查询
- **参数**: 
  - `$sql`: SQL语句
  - `$type`: 查询类型
  - `$cachetime`: 缓存时间
- **返回值**: 查询结果

###### `fetch_array($query, $result_type = MYSQLI_ASSOC)`
- **功能**: 获取结果行为数组
- **参数**: 
  - `$query`: 查询结果
  - `$result_type`: 结果类型
- **返回值**: 结果数组

###### `fetch_first($sql)`
- **功能**: 获取查询的第一行结果
- **参数**: `$sql`: SQL语句
- **返回值**: 结果数组

###### `fetch_all($sql, $id = '')`
- **功能**: 获取所有查询结果
- **参数**: 
  - `$sql`: SQL语句
  - `$id`: 索引字段
- **返回值**: 结果数组

###### `result($query, $row = 0)`
- **功能**: 获取特定行的第一个字段值
- **参数**: 
  - `$query`: 查询结果
  - `$row`: 行号
- **返回值**: 字段值

###### `result_first($sql)`
- **功能**: 获取查询结果的第一行第一个字段值
- **参数**: `$sql`: SQL语句
- **返回值**: 字段值

###### `num_rows($query)`
- **功能**: 获取结果行数
- **参数**: `$query`: 查询结果
- **返回值**: 行数

###### `num_fields($query)`
- **功能**: 获取结果字段数
- **参数**: `$query`: 查询结果
- **返回值**: 字段数

###### `affected_rows()`
- **功能**: 获取受影响的行数
- **参数**: 无
- **返回值**: 受影响的行数

###### `insert_id()`
- **功能**: 获取最后插入的ID
- **参数**: 无
- **返回值**: 插入ID

##### 数据操作

###### `insert($table, $data, $return_insert_id = false, $replace = false, $silent = false)`
- **功能**: 插入数据
- **参数**: 
  - `$table`: 表名
  - `$data`: 数据数组
  - `$return_insert_id`: 是否返回插入ID
  - `$replace`: 是否使用REPLACE
  - `$silent`: 是否静默执行
- **返回值**: 插入结果

###### `update($table, $data, $condition, $unbuffered = false, $low_priority = false)`
- **功能**: 更新数据
- **参数**: 
  - `$table`: 表名
  - `$data`: 数据数组
  - `$condition`: 条件
  - `$unbuffered`: 是否非缓冲
  - `$low_priority`: 是否低优先级
- **返回值**: 更新结果

###### `delete($table, $condition, $limit = 0, $unbuffered = true)`
- **功能**: 删除数据
- **参数**: 
  - `$table`: 表名
  - `$condition`: 条件
  - `$limit`: 限制数量
  - `$unbuffered`: 是否非缓冲
- **返回值**: 删除结果

##### 事务处理

###### `begin_transaction()`
- **功能**: 开始事务
- **参数**: 无
- **返回值**: 布尔值，表示操作是否成功

###### `commit()`
- **功能**: 提交事务
- **参数**: 无
- **返回值**: 布尔值，表示操作是否成功

###### `rollback()`
- **功能**: 回滚事务
- **参数**: 无
- **返回值**: 布尔值，表示操作是否成功

##### 辅助功能

###### `free_result($query)`
- **功能**: 释放结果集
- **参数**: `$query`: 查询结果
- **返回值**: 布尔值，表示操作是否成功

###### `escape_string($str)`
- **功能**: 转义字符串
- **参数**: `$str`: 需要转义的字符串
- **返回值**: 转义后的字符串

###### `error()`
- **功能**: 获取最后一个错误
- **参数**: 无
- **返回值**: 错误信息

###### `errno()`
- **功能**: 获取错误代码
- **参数**: 无
- **返回值**: 错误代码

###### `halt($message = '', $sql = '')`
- **功能**: 处理错误并终止
- **参数**: 
  - `$message`: 错误消息
  - `$sql`: SQL语句
- **返回值**: 无

###### `close()`
- **功能**: 关闭数据库连接
- **参数**: 无
- **返回值**: 布尔值，表示操作是否成功

##### 调试功能

###### `debug_info()`
- **功能**: 获取调试信息
- **参数**: 无
- **返回值**: 调试信息数组

###### `get_version()`
- **功能**: 获取数据库版本
- **参数**: 无
- **返回值**: 版本信息

###### `get_table_status($table)`
- **功能**: 获取表状态
- **参数**: `$table`: 表名
- **返回值**: 表状态信息

###### `get_tables_list($database = '')`
- **功能**: 获取数据库中的表列表
- **参数**: `$database`: 数据库名
- **返回值**: 表列表数组

###### `get_table_fields($table)`
- **功能**: 获取表字段信息
- **参数**: `$table`: 表名
- **返回值**: 字段信息数组

这个类提供了完整的数据库操作功能，包括连接管理、查询执行、数据操作、事务处理和调试功能，是整个游戏系统数据库操作的核心组件。