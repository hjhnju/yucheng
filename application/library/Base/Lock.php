<?php
/**
 * 并发控制类
 * @author hejunhua
 * @since 2015-04-20
 */
class Base_Lock {

    /**
     * 上锁
     * @var interger
     */
    CONST LOCKED = 1;

    //concurrency key
    CONST PREFIX = 'cckey_';

    /**
     * 加锁
     * @param string $key
     * @param integer $intTimeOut
     */
    public static function lock($key, $intTimeOut = 60){
        $key   = self::PREFIX . $key;
        try{
            $redis = Base_Redis::getInstance();
            if($redis->setnx($key, self::LOCKED)){
                $redis->expire($key, $intTimeOut);
                return true;
            }
        }catch(Exception $ex){
            //redis不成功，无法加锁
            Base_Log::error(array('msg'=>'redis无法使用，无法加锁', 'key'=>$key));
            //TODO:目前采用不加锁的方式，如果redis够稳定后再修改为返回加锁失败
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
