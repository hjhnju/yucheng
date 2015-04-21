<?php
/**
 * 并发控制类
 * @author hejunhua
 * @since 2015-04-20
 */
class Base_Concurr {
	
	/**
	 * 上锁
	 * @var interger
	 */
	CONST LOCKED = 1;
	
	/**
	 * 加锁
	 * @param string $key
	 * @param integer $intTimeOut
	 */
	public static function lock($key, $intTimeOut = 60){ 
	    $redis = Base_Redis::getInstance();
	    if(self::LOCKED == $redis->get($key)){
	        return true;
	    }
        $redis->set($key,self::LOCKED, $intTimeOut);
        return false;
	}
    
	/**
	 * 解锁
	 * @param string $key
	 */
	public static function unlock($key){
	    $redis = Base_Redis::getInstance();
	    if(false !== $redis->get($key)){
	        $redis->delete($key);
	    }
	}
}