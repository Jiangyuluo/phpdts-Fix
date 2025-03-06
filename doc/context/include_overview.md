# include目录文件概述

本文档提供了`include`目录中所有文件的概述和主要功能说明。

## 核心系统文件

- **system.func.php** - 系统核心函数库，包含系统运行所需的基础函数
- **global.func.php** - 全局函数库，提供全局可用的通用函数
- **common.inc.php** - 通用包含文件，定义常量和基础配置
- **init.func.php** - 初始化函数库，负责系统启动和初始化

## 数据库相关

- **db_mysql.class.php** - MySQL数据库操作类
- **db_mysqli.class.php** - MySQLi数据库操作类
- **db_pdo.class.php** - PDO数据库操作类
- **db_mysql_error.inc.php** - MySQL错误处理
- **db_mysqli_error.inc.php** - MySQLi错误处理

## 游戏核心功能

- **game.func.php** - 游戏核心函数库
- **state.func.php** - 游戏状态管理函数
- **roommng.func.php** - 房间管理相关函数
- **resources.func.php** - 资源管理相关函数
- **gameencrypt.php** - 游戏加密相关函数

## 用户和消息系统

- **user.func.php** - 用户相关函数
- **messages.func.php** - 消息系统相关函数
- **news.func.php** - 新闻/公告相关函数
- **weibolog.func.php** - 微博日志相关函数

## 前端和模板

- **template.func.php** - 模板处理函数
- **game.js** - 游戏前端核心JavaScript
- **game20130526.js** - 游戏前端JavaScript（特定版本）
- **common.js** - 通用JavaScript函数
- **json.js** - JSON处理JavaScript
- **record.js** - 记录相关JavaScript
- **pako.js** - 压缩库JavaScript

## JSON处理

- **JSON.php** - PHP的JSON处理库

## 子目录

- **admin/** - 管理员相关功能
- **game/** - 游戏核心功能模块
- **javascript/** - JavaScript库和脚本
- **vnworld/** - 虚拟世界相关功能
- **devtools/** - 开发工具

每个文件的详细函数分析可在对应的单独文档中找到。