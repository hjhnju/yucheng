<?php
/**
 * Adapter for TopazDb
 * usage:
 * $db = Base_Db::getInstance('DB_NAME');
 * $rs = $db->query($sql);
 */
class Base_Db {
    
    private static $_instance;

    private static $_bolMaster = false;

    protected static $_arrInstance = array();
    
    /**
     * 加载数据库分片配置
     * @return array array('{$dbname}'=>$arrInfo)
     */
    protected static function loadConfig() {
        $shards = Base_Config::getConfig('shard', CONF_PATH . '/db.ini');
        $arrRetInfo = array();
        foreach ($shards as $dbkey => $arrInfo) {
            foreach ($arrInfo as $info) {
                $arrRetInfo[$info['dbname']] = $info;
            }
        }
        return $arrRetInfo;
    }

    /**
     * 获取库表对应的db链接
     * @param  string $strName 库名或'库.表'名
     * @return         Db实例
     */
    public static function getInstance($strName) {
        $arrTbl = explode('.', $strName);
        $dbname = $arrTbl[0];
        if(!isset(self::$_arrInstance[$dbname])) {
            $arrShardInfo = self::loadConfig();
            $arrConf = $arrShardInfo[$dbname];
            self::$_arrInstance[$dbname] = new Base_TopazDb($arrConf);
        }
        return self::$_arrInstance[$dbname];
    }

    private static $_pdoInstance;

    public static function getPDOInstance($dbname) {
        if(self::$_pdoInstance == null) {
            $arrShardInfo = self::loadConfig();
            $arrConf = $arrShardInfo[$dbname];
            $arrConf['hosts'] = explode(',', $arrConf['hosts']);
            $key = array_rand($arrConf['hosts']);
            $host = $arrConf['hosts'][$key];
            $port = $arrConf['port'];
            $dbname = $arrConf['dbname'];
            $username = $arrConf['username'];
            $password = $arrConf['password'];
            $charset = $arrConf['charset'];
            self::$_pdoInstance = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname="
                . $dbname . ";charset=" . $charset, $username, $password);
        }
        return self::$_pdoInstance;
    }
}

