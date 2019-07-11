<?php

use library\Db;

include '../library/Db/Db.php';
class Server
{
    const HOST = "0.0.0.0";
    const PORT = 9503;
    private $server = null;
    private $connectList = [];

    public function __construct()
    {
        $db = new Db();
        $result = $db->query("select count(*) from wp_count");
        var_dump($result);
        //实例化swoole_websocket_server
        $this->server = new swoole_websocket_server(self::HOST, self::PORT);
        //建立监听连接
        $this->server->on('open', [$this, 'onOpen']);
        //监听消息接收事件
        $this->server->on('message', [$this, 'onMessage']);
        //监听关闭事件
        $this->server->on('close', [$this, 'onClose']);
        //开启服务
        $this->server->start();
    }

    public function onOpen($server, $request)
    {
        echo $request->fd . '连接了' . PHP_EOL;
        $this->connectList[] = $request->fd;
        foreach ($this->connectList as $fd) {
            $server->push($fd, json_encode(['no' => $fd, 'msg' => "$request->fd" . ' 加入了群聊', 'type' => 'open']));
        }
    }

    public function onMessage($server, $frame)
    {

        echo $frame->fd . '来了, 说:' . $frame->data . PHP_EOL;
        foreach ($this->connectList as $fd) {
            if ($frame->fd != $fd) {
                $server->push($fd, json_encode(['no' => $frame->fd, 'msg' => $frame->data, 'type' => 'chat']));
            }
        }
    }

    public function onClose($server, $fd)
    {
        echo $fd . '走了' . PHP_EOL;
        $this->connectList = array_diff($this->connectList, [$fd]);
        foreach ($this->connectList as $fdItem) {
            $server->push($fdItem, json_encode(['no' => $fd, 'msg' => "$fd" . ' 离开了群聊', 'type' => 'close']));
        }
    }

}

$obj = new Server();
