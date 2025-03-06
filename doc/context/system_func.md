# system.func.php 函数分析

`system.func.php`是系统核心函数库，包含系统运行所需的基础函数。

## 函数列表

### 系统核心函数

#### `get_magic_quotes_gpc_compatible()`
- **功能**: 兼容处理magic_quotes_gpc，返回是否开启了magic_quotes_gpc
- **返回值**: 布尔值，表示是否开启了magic_quotes_gpc

#### `daddslashes($string, $force = 0)`
- **功能**: 对字符串进行addslashes处理，防止SQL注入
- **参数**: 
  - `$string`: 需要处理的字符串或数组
  - `$force`: 是否强制处理，即使magic_quotes_gpc已开启
- **返回值**: 处理后的字符串或数组

#### `rhtmlspecialchars($string)`
- **功能**: 对字符串进行htmlspecialchars处理，转换特殊字符为HTML实体
- **参数**: `$string`: 需要处理的字符串
- **返回值**: 处理后的字符串

#### `raddslashes($string)`
- **功能**: 对字符串进行addslashes处理，防止SQL注入
- **参数**: `$string`: 需要处理的字符串
- **返回值**: 处理后的字符串

#### `rsetcookie($var, $value, $life = 0, $prefix = 1)`
- **功能**: 设置cookie
- **参数**:
  - `$var`: cookie名称
  - `$value`: cookie值
  - `$life`: cookie生存时间
  - `$prefix`: 是否添加前缀
- **返回值**: 无

#### `fileext($filename)`
- **功能**: 获取文件扩展名
- **参数**: `$filename`: 文件名
- **返回值**: 文件扩展名

#### `cutstr($string, $length, $dot = ' ...')`
- **功能**: 截取字符串，支持中文
- **参数**:
  - `$string`: 原始字符串
  - `$length`: 截取长度
  - `$dot`: 省略符号
- **返回值**: 截取后的字符串

#### `showmsg($message, $url_forward = '', $msgtype = 'message', $extra = '')`
- **功能**: 显示消息并跳转
- **参数**:
  - `$message`: 消息内容
  - `$url_forward`: 跳转URL
  - `$msgtype`: 消息类型
  - `$extra`: 额外信息
- **返回值**: 无，直接输出HTML

#### `getusergroup($groupid)`
- **功能**: 获取用户组信息
- **参数**: `$groupid`: 用户组ID
- **返回值**: 用户组信息数组

#### `checkpost($varname, $minlength, $maxlength, $checktype = '')`
- **功能**: 检查POST变量
- **参数**:
  - `$varname`: 变量名
  - `$minlength`: 最小长度
  - `$maxlength`: 最大长度
  - `$checktype`: 检查类型
- **返回值**: 检查结果

### 文件和目录操作

#### `writeover($filename, $data, $method = 'wb', $iflock = 1)`
- **功能**: 写入文件
- **参数**:
  - `$filename`: 文件名
  - `$data`: 写入数据
  - `$method`: 写入方式
  - `$iflock`: 是否锁定文件
- **返回值**: 布尔值，表示操作是否成功

#### `readover($filename, $method = 'rb')`
- **功能**: 读取文件
- **参数**:
  - `$filename`: 文件名
  - `$method`: 读取方式
- **返回值**: 文件内容

#### `makedir($dir)`
- **功能**: 创建目录
- **参数**: `$dir`: 目录路径
- **返回值**: 布尔值，表示操作是否成功

### 时间和日期处理

#### `sgmdate($dateformat, $timestamp = '', $format = 0)`
- **功能**: 格式化时间戳
- **参数**:
  - `$dateformat`: 日期格式
  - `$timestamp`: 时间戳
  - `$format`: 格式化方式
- **返回值**: 格式化后的日期字符串

#### `get_date($time, $type = 0)`
- **功能**: 获取格式化日期
- **参数**:
  - `$time`: 时间戳
  - `$type`: 格式类型
- **返回值**: 格式化后的日期字符串

### 安全和验证

#### `clearcookies()`
- **功能**: 清除所有cookie
- **参数**: 无
- **返回值**: 无

#### `checkformhash($formhash)`
- **功能**: 检查表单hash，防止CSRF攻击
- **参数**: `$formhash`: 表单hash值
- **返回值**: 布尔值，表示检查是否通过

#### `formhash()`
- **功能**: 生成表单hash
- **参数**: 无
- **返回值**: 生成的hash值

### 其他实用函数

#### `debuginfo()`
- **功能**: 输出调试信息
- **参数**: 无
- **返回值**: 无，直接输出HTML

#### `random($length, $numeric = 0)`
- **功能**: 生成随机字符串
- **参数**:
  - `$length`: 字符串长度
  - `$numeric`: 是否仅包含数字
- **返回值**: 随机字符串

#### `multi_array_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)`
- **功能**: 多维数组排序
- **参数**:
  - `$arrays`: 待排序数组
  - `$sort_key`: 排序键名
  - `$sort_order`: 排序顺序
  - `$sort_type`: 排序类型
- **返回值**: 排序后的数组

#### `getmicrotime()`
- **功能**: 获取微秒级时间戳
- **参数**: 无
- **返回值**: 微秒级时间戳

#### `check_ip($ip, $ips)`
- **功能**: 检查IP是否在允许范围内
- **参数**:
  - `$ip`: 待检查IP
  - `$ips`: 允许的IP范围
- **返回值**: 布尔值，表示检查是否通过

#### `convertip($ip, $ipdatafile = '')`
- **功能**: 将IP转换为地理位置
- **参数**:
  - `$ip`: IP地址
  - `$ipdatafile`: IP数据文件
- **返回值**: 地理位置信息

这只是部分核心函数的分析，完整的system.func.php文件包含更多系统级函数，用于支持整个游戏系统的运行。