<?php
/**
 * 配置文件
 */

return [
    // 数据库类型
    'type'     => 'mysql',
    // 服务器地址
    'hostname' => '127.0.0.1',
    // 数据库名
    'database' => 'tk',
    // 用户名
    'username' => 'root',
    // 密码
    'password' => 'root',
    // 端口
    'hostport' => '3306',
    // 数据库编码默认采用utf8
    'charset'  => 'utf8mb4',
    // 数据库表前缀
    'prefix'   => 'cmf_',
    "authcode" => 'uV1sDvmUeV9Lcva00i',
    //#COOKIE_PREFIX#
	
	/* redis */
	'REDIS_HOST' => "127.0.0.1",
	'REDIS_AUTH' => "123456",
	'REDIS_PORT' => "6379",
	'REDIS_SELECT' => "20",
	
];
