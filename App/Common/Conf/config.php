<?php 
return array(
// /*数据库配置*/
'DB_TYPE' => 'MYSQL', //数据库类型
'DB_HOST' => 'localhost', // 服务器地址
'DB_NAME' => 'bamengdb', // 数据库名
'DB_USER' => 'root', // 用户名
'DB_PWD' => '1988chenyi@', // 密码
'DB_PORT' => '3306', // 端口
'DB_PREFIX' => 'b_', // 数据库表前缀


// 	/*数据库配置*/
// 'DB_TYPE'               => 'MYSQL',//数据库类型
// 'DB_HOST'               =>  'localhost', // 服务器地址
// 'DB_NAME'               =>  'bamengdb',          // 数据库名
// 'DB_USER'               =>  'bamengSQL',      // 用户名
// 'DB_PWD'                =>  'bamengSQL002!@#',          // 密码
// 'DB_PORT'               =>  '3306',        // 端口
// 'DB_PREFIX'             =>  'b_',    // 数据库表前缀

'LOG_EXCEPTION_RECORD' =>true,
'SHOW_PAGE_TRACE' =>true, //显示页面读取数据库信息

'SESSION_TYPE' => 'Db', //自定义session数据库存储
'URL_MODEL' => 2, //URL路径模型0、普通模式1、PATCH模式2、REWITE模式、3兼容模式
);