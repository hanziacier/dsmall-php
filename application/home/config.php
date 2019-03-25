<?php

return [
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'default/base/dispatch_jump',
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'default/base/dispatch_jump',
    

    'session'                => [
        // 驱动方式 支持redis memcache memcached
        'type'           => 'redis',
        // 是否自动开启 SESSION
        'auto_start'     => true,
        'host'          => $_SERVER['REDIS_HOST'],
        'port'          => $_SERVER['REDIS_PORT'],
        'password'      => $_SERVER['REDIS_PASSWORD'],
        'select'        => 0,
        'expire'        => 3600,
        'timeout'       => 0,
        'session_name'  => 'dsmall_sess_'
    ],
];
