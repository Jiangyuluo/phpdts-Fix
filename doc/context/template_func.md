# template.func.php 函数分析

`template.func.php`是模板处理函数库，负责处理HTML模板的加载、解析和渲染。

## 函数列表

### 模板加载和解析

#### `template($file, $templateid = 0, $tpldir = '')`
- **功能**: 加载模板文件
- **参数**: 
  - `$file`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 模板内容

#### `parse_template($tplfile, $templateid = 0, $tpldir = '')`
- **功能**: 解析模板文件
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 解析后的模板内容

#### `subtemplates($subtpl, $templateid = 0, $tpldir = '')`
- **功能**: 加载子模板
- **参数**: 
  - `$subtpl`: 子模板名称
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 子模板内容

### 模板变量和数据

#### `template_variables($data, $force = 0)`
- **功能**: 设置模板变量
- **参数**: 
  - `$data`: 变量数据
  - `$force`: 是否强制覆盖
- **返回值**: 无

#### `get_template_vars($var = '')`
- **功能**: 获取模板变量
- **参数**: `$var`: 变量名
- **返回值**: 变量值或所有变量数组

#### `assign($name, $value)`
- **功能**: 分配变量到模板
- **参数**: 
  - `$name`: 变量名
  - `$value`: 变量值
- **返回值**: 无

#### `assign_by_ref($name, &$value)`
- **功能**: 通过引用分配变量
- **参数**: 
  - `$name`: 变量名
  - `$value`: 变量值引用
- **返回值**: 无

### 模板渲染和输出

#### `render_template($tpl, $data = array())`
- **功能**: 渲染模板
- **参数**: 
  - `$tpl`: 模板名称
  - `$data`: 模板数据
- **返回值**: 渲染后的HTML

#### `display($tpl)`
- **功能**: 显示模板
- **参数**: `$tpl`: 模板名称
- **返回值**: 无，直接输出HTML

#### `fetch($tpl)`
- **功能**: 获取模板渲染结果
- **参数**: `$tpl`: 模板名称
- **返回值**: 渲染后的HTML

#### `output_template($content)`
- **功能**: 输出模板内容
- **参数**: `$content`: 模板内容
- **返回值**: 无，直接输出HTML

### 模板缓存

#### `template_cache($tplfile, $templateid = 0, $tpldir = '')`
- **功能**: 获取模板缓存
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 缓存内容或false

#### `save_template_cache($tplfile, $content, $templateid = 0, $tpldir = '')`
- **功能**: 保存模板缓存
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$content`: 缓存内容
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 保存结果

#### `clear_template_cache($tplfile = '', $templateid = 0, $tpldir = '')`
- **功能**: 清除模板缓存
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 清除结果

### 模板处理和修改

#### `template_include($file, $templateid = 0, $tpldir = '')`
- **功能**: 包含其他模板
- **参数**: 
  - `$file`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 包含后的内容

#### `template_exists($tplfile, $templateid = 0, $tpldir = '')`
- **功能**: 检查模板是否存在
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 布尔值，表示模板是否存在

#### `template_format($template)`
- **功能**: 格式化模板内容
- **参数**: `$template`: 模板内容
- **返回值**: 格式化后的内容

#### `template_compile($template)`
- **功能**: 编译模板
- **参数**: `$template`: 模板内容
- **返回值**: 编译后的PHP代码

### 模板标签和语法

#### `parse_template_tag($tag, $parameter)`
- **功能**: 解析模板标签
- **参数**: 
  - `$tag`: 标签名
  - `$parameter`: 标签参数
- **返回值**: 解析结果

#### `parse_template_loop($code, $loopname, $key, $value)`
- **功能**: 解析循环标签
- **参数**: 
  - `$code`: 循环代码
  - `$loopname`: 循环名称
  - `$key`: 键名
  - `$value`: 值名
- **返回值**: 解析后的PHP代码

#### `parse_template_if($condition)`
- **功能**: 解析条件标签
- **参数**: `$condition`: 条件表达式
- **返回值**: 解析后的PHP代码

#### `parse_template_function($func, $parameter)`
- **功能**: 解析函数标签
- **参数**: 
  - `$func`: 函数名
  - `$parameter`: 函数参数
- **返回值**: 解析后的PHP代码

### 辅助功能

#### `template_path($tplfile, $templateid = 0, $tpldir = '')`
- **功能**: 获取模板文件路径
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 模板文件完整路径

#### `template_cache_path($tplfile, $templateid = 0, $tpldir = '')`
- **功能**: 获取模板缓存路径
- **参数**: 
  - `$tplfile`: 模板文件名
  - `$templateid`: 模板ID
  - `$tpldir`: 模板目录
- **返回值**: 缓存文件完整路径

#### `template_error($message)`
- **功能**: 处理模板错误
- **参数**: `$message`: 错误消息
- **返回值**: 无，直接输出错误

这些函数共同构成了一个完整的模板系统，用于处理游戏中的HTML页面生成和显示，支持模板加载、解析、缓存和渲染等功能。