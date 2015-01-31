<?php
/**
 * 重构对Redis接口的调用
 * 1. 增加对调用的日志记录
 * 2. 增加调用失败重连
 * 注意，不要依赖方法调用返回值true/false/0来判断redis是否调用成功。应该通过exception来判断
 * @author hejunhua<hejunhua@baidu.com>
 * @since 2014-08-31
 */
class Base_Redis {

    /**
     * singleton
     */
    protected static $_objInstance;
    protected static $_arrConfig;

    /**
     * redis链接
     */
    protected $_objRedis;

    /**
     * 调用失败重试次数
     * @var integer
     */
    protected $_maxRetry = 1;

    /**
     * singleton
     * @param  int $maxRetry 失败重连次数，每次重连会尝试所有host配置
     * @return Redis
     */
    public static function getInstance() {
        if (!self::$_objInstance) {
            $config = Base_Config::getConfig('redis', CONF_PATH . '/redis.ini');
            self::$_objInstance = new self($config);
        }
        return self::$_objInstance;
    }

    /**
     * 构造函数
     * array(
     *  'max_retry' => n
     *  0 => obj(ip=>, port=>, timeout=>),
     *  1 => obj(ip=>, port=>, timeout=>),
     * );
     */
    public function __construct($arrConfig) {
        if (empty($arrConfig) || !is_array($arrConfig)){
            throw new Base_Exception_Runtime("Config for redis empty.");
        } 
        //max_retry config
        if(isset($arrConfig['max_retry'])){
            $this->_maxRetry  = intval($arrConfig['max_retry']);
            unset($arrConfig['max_retry']);
        }
        //host config
        $this->_arrConfig = $arrConfig;
        shuffle($this->_arrConfig);
        //try connect
        $bol             = false;
        $this->_objRedis = new Redis();
        foreach ($this->_arrConfig as $address) {
            $bol         = $this->_objRedis->connect($address['host'], $address['port'], $address['timeout']);
            if ($bol === true) {
                break;
            }
            Base_Log::warn(array(
                'msg'     => 'Redis conf not work',
                'address' => $address)
            );
        }
        if (!$bol) {
            throw new Base_Exception_Runtime("Connected to redis failed!");
        }
    }

    public function increment($strKey, $intVal = 1){
        if($intVal === 1){
            $this->_objRedis->incr($strKey);
        }else{
            $this->_objRedis->incrBy($strKey, $intVal);
        }
    }

    public function decrement($strKey, $intVal=1){
        if($intVal === 1){
            $this->_objRedis->decr($strKey);
        }else{
            $this->_objRedis->decrBy($strKey, $intVal);
        }
    }

    /**
     * 调用redis方法
     * @param  [type] $method  [description]
     * @param  [type] $arrArgs [description]
     * @return [type]          [description]
     */
    public function __call($method, $arrArgs){
        if(!method_exists('Redis', $method)){
            throw new Base_Exception_Runtime("Not exist method in Redis class");
        }

        $mixRet = false;
        for($i = 0;$i < $this->_maxRetry;$i++){
            try{
                $mixRet = call_user_func_array(array($this->_objRedis, $method), $arrArgs);
                break;
            }catch(Exception $e){
                Base_Log::error(array(
                    'redis_error'  => $e->getMessage(),
                    'redis_method' => $method,
                    'redis_args'   => $arrArgs,
                    )
                );
                if($i < $this->_maxRetry){
                    $this->reconnect();
                }else{
                    throw new Base_Exception_Runtime("Redis calling error");
                }
            }
        }

        Base_Log::debug(array(
            'redis_method' => $method,
            'redis_args'   => $arrArgs,
            'redis_return' => $mixRet)
        );
        return $mixRet;
    }


    /**
     * 遍历配置进行重新链接
     * @return [type] [description]
     */
    protected function reconnect(){
        $bol = false;
        foreach ($this->_arrConfig as $address) {
            $bol = $this->_objRedis->connect($address['host'], $address['port'], $address['timeout']);
            if ($bol === true) {
                break;
            }
        }
        Base_Log::notice(array('msg'=>'Reconnnect to redis', 'ret'=> $bol));
        return $bol;
    }

}
