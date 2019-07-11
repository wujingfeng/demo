<?php


namespace library;


use Exception;
use PDO;
use PDOException;

const CONF_PATH = '../../database.php';

class Db
{
    protected static $instance = null;
    protected $dbName = '';
    protected $dsn;
    protected $connection;
    protected $config = [
        //数据库类型
        'type'          => 'mysql',
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
        //是否需要使用长连接
        'keep_alive'    => true
    ];

    /*
     * 构造函数初始化数据库连接对象
     */
    public function __construct()
    {
        if (file_exists(CONF_PATH)) {
            $conf = require_once CONF_PATH;
            $this->config = array_merge($this->config, $conf);
        }
        try {
            $this->dsn = $this->config['type'] . ':host=' . $this->config['host'] . ';dbname=' . $this->config['database'];
            var_dump($this->dsn);
            //获取连接对象
            $this->connection = new PDO($this->dsn, $this->config['user'], $this->config['password'], [PDO::ATTR_PERSISTENT => $this->config['keep_alive']]);
            //设置字符集
            $this->connection->exec('SET character_set_connection=' . $this->config['charset'] . ', character_set_results=' . $this->config['charset'] . ', character_set_client=binary');
        } catch (PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    /*
     * 禁止克隆
     */
    private function __clone()
    {
    }

    /*
     * debug
     */
    private function debug($debugMsg)
    {
        var_dump($debugMsg);
        exit();
    }

    /*
     * 输出错误信息
     */
    private function outputError($errorMsg)
    {
        throw new Exception('MySQL Error: ' . $errorMsg);
    }

    /*
     * 捕获PDO错误信息
     */
    private function getPDOError()
    {
        if ($this->connection->errorCode() != '00000') {
            $errorArr = $this->connection->errorInfo();
            $this->outputError($errorArr[2]);
        }
    }


    /*
     * 单例模式  避免全局调用时重复实例化
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function exec($sql)
    {
        return $this->connection->exec($sql);
    }

    /*
     * 执行SQL语句,debug=>true可打印sql调试
     * @param string $sql
     * @param bool $debug
     * @return int
     */
    public function execSql($sql, $debug = false)
    {
        $debug === true ? $this->debug($sql) : '';
        $result = $this->exec($sql);
        $this->getPDOError();
        return $result;
    }

    /*
     * query 查询
     * @param string $sql       sql语句
     * @param string $type      查询方式(all(所有) or row(一条))
     * @param bool $debug       debug模式  是否打印sql语句
     * @return array|mixed|null
     */
    public function query($sql, $type = 'all', $debug = false)
    {
        $debug === true ? $this->debug($sql) : '';
        $queryResult = $this->connection->query($sql);
        $this->getPDOError();
        $result = null;
        if ($queryResult) {
            $queryResult->setFetchMode(PDO::FETCH_ASSOC);
            if (strtolower($type) == 'all') {
                $result = $queryResult->fetchAll();
            } elseif (strtolower($type) == 'row') {
                $result = $queryResult->fetch();
            }
        }

        return $result;
    }

    /*
     * update 更新
     * @param $table            表名
     * @param array $data       更新的数据
     * @param string $where     更新条件
     * @param bool $debug       是否开启debug模式
     * @return int
     */
    public function update($table, array $data, $where = '1=1', $debug = false)
    {
        $this->checkFields($table, $data);
        $setField = '';
        foreach ($data as $key => $datum) {
            $setField .= "`$key`='$datum',";
        }
        $setField = trim($setField, ',');
        $sql = "UPDATE `$table` SET $setField WHERE $where";

        $debug === true ? $this->debug($sql) : '';
        $result = $this->connection->exec($sql);
        $this->getPDOError();
        return $result;
    }

    /*
     * insert 插入
     * @param $table        表名
     * @param array $data   数据
     * @param bool $debug   debug
     * @return int
     */
    public function insert($table, array $data, $debug = false)
    {
        $this->checkFields($table, $data);
        $sql = "INSERT INTO `$table` ( `" . implode('`,`', array_keys($data)) . " `) VALUES ('" . implode("','", $data) . "')";
        $debug === true ? $this->debug($sql) : '';
        $result = $this->connection->exec($sql);
        $this->getPDOError();
        return $result;
    }

    /*
     * delete 删除
     * @param $table        表名
     * @param string $where 条件
     * @param bool $debug   debug
     * @return int
     */
    public function delete($table, $where = '1=1', $debug = false)
    {
        $sql = "DELETE FROM `$table` WHERE $where";
        $debug === true ? $this->debug($sql) : '';
        $result = $this->connection->exec($sql);
        $this->getPDOError();
        return $result;
    }

    /*
     * 获取表字段
     */
    private function getFields($table)
    {
        $queryResult = $this->connection->query('SHOW COLUMNS FROM ' . $table);
        $this->getPDOError();
        $queryResult->setFetchMode(PDO::FETCH_ASSOC);
        $result = $queryResult->fetchAll();
        $fields = array_column($result, 'Field');

        return $fields;
    }

    /*
     * 检查字段合法性
     * @param $table        表名
     * @param $arrayFields  需要对比的数据(字段名做下标)
     * @throws Exception
     */
    public function checkFields($table, $arrayFields)
    {
        $tableFields = $this->getFields($table);
        foreach ($arrayFields as $key => $item) {
            if (!in_array($key, $tableFields)) {
                $this->outputError("Unknown column `$key` in field list.");
            }
        }
    }

}

