<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
return [
    'accessKey'                 => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title'   => 'AccessKey', // 表单的label标题
        'type'    => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value'   => '',// 表单的默认值
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => 'AccessKey不能为空'
        ],
        'tip'     => '<a href="https://portal.qiniu.com/signup?invitation_type=1&invitation_key=1381750588" target="_blank">马上获取</a>,充值使用ThinkCMF七牛专属优惠码<a href="http://www.thinkcmf.com/qiniu/promotion_code.html" target="_blank">507670e8</a>有更多优惠,<a href="http://www.thinkcmf.com/faq.html?url=https://www.kancloud.cn/thinkcmf/faq/507454" target="_blank">查看帮助手册</a>' //表单的帮助提示
    ],
    'secretKey'                 => [// 在后台插件配置表单中的键名 ,会是config[password]
        'title'   => 'SecretKey',
        'type'    => 'text',
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => 'SecretKey不能为空'
        ],
        'tip'     => '<a href="https://portal.qiniu.com/signup?invitation_type=1&invitation_key=1381750588" target="_blank">马上获取</a>, <a href="http://www.thinkcmf.com/faq.html?url=https://www.kancloud.cn/thinkcmf/faq/507454" target="_blank">查看帮助手册</a>'
    ],
    'protocol'                  => [// 在后台插件配置表单中的键名 ,会是config[select]
        'title'   => '域名协议',
        'type'    => 'select',
        'options' => [//select 和radio,checkbox的子选项
            'http'  => 'http',// 值=>显示
            'https' => 'https',
        ],
        'value'   => 'http',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '域名协议不能为空'
        ],
        'tip'     => ''
    ],
    'domain'                    => [
        'title'   => '空间域名',
        'type'    => 'text',
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '空间域名不能为空'
        ],
        'tip'     => ''
    ],
    'bucket'                    => [
        'title'   => '空间名称',
        'type'    => 'text',
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '空间名称不能为空'
        ],
        'tip'     => ''
    ],
    'zone'                      => [// 在后台插件配置表单中的键名 ,会是config[select]
        'title'   => '存储区域',
        'type'    => 'select',
        'options' => [//select 和radio,checkbox的子选项
            'z0'  => '华东',// 值=>显示
            'z1'  => '华北',
            'z2'  => '华南',
            'na0' => '北美',
            'as0' => '东南亚',
        ],
        'value'   => 'http',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '存储区域不能为空'
        ],
        'tip'     => ''
    ],
];
					