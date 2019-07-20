<?php

$server = new swoole_websocket_server('0.0.0.0', 9503);

$server->on('open',function($server,$request){
    var_dump(222);
    $swoole_mysql = new Swoole\Coroutine\MySQL();
    $swoole_mysql->connect([
        //服务器地址
        'host' => 'rm-wz92savn814517w0jto.mysql.rds.aliyuncs.com',
        //端口号
        'port' => 3306,
        //数据库名称
        'database' => 'wpblog',
        //用户名
        'user' => 'root',
        //密码
        'password' => 'wwt1994512012WU',
        //数据库编码
        'charset'       => 'utf8mb4',
    ]);
    $res = $swoole_mysql->query('select * from wp_count order by id desc limit 10');

    var_dump($res);
});
$server->on('message',function ($server, $frame){
    var_dump(333);
});

$server->on('close',function ($server,$fd){
    var_dump(44);
});

$server->start();
