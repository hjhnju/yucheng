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
	    if(TRUE == $redis->setnx($key,self::LOCKED)){
	        $redis->expire($key, $intTimeOut);
	        return true;
	    }
	    return false;
	}
    
	/**
	 * 解锁
	 * @param string $key
	 */
	public static function unlock($key){
	    $redis = Base_Redis::getInstance();
	    return $redis->delete($key);
	}
}