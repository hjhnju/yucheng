<?php
/**
 * @author: hejunhua@baidu.com
 * @date: 2013-05-12
 * 哈希函数簇
 * 应用于分表
 */
class Base_Util_Hasher {

    /**
     * 取模函数
     * @param $mod 
     * @param $id id for sharding
     * @param $getbuckets boolean weather to get buckets number
     */
    private static function mod($mod, $id, $getbuckets) {
        $id = intval($id);
        $bucketid = $id & ($mod-1);
        if($getbuckets){
            return $mod;
        }
        return $bucketid;
    }

    public static function mod1($id, $getbuckets=false) {
        return self::mod(1, $id, $getbuckets);
    }

    public static function mod2($id, $getbuckets=false) {
        return self::mod(2, $id, $getbuckets);
    }

    public static function mod4($id, $getbuckets=false) {
        return self::mod(4, $id, $getbuckets);
    }
    
    public static function mod8($id, $getbuckets=false) {
    	return self::mod(8, $id, $getbuckets);
    }
    
    public static function mod16($id, $getbuckets=false) {
    	return self::mod(16, $id, $getbuckets);
    }
    
    public static function mod32($id, $getbuckets=false) {
    	return self::mod(32, $id, $getbuckets);
    }
    
	public static function mod64($id, $getbuckets=false) {
    	return self::mod(64, $id, $getbuckets);
    }
    
	public static function mod128($id, $getbuckets=false) {
    	return self::mod(128, $id, $getbuckets);
    }

    // 默认返回空,表示不分表
    public static function noshard($id, $getbuckets=false) {
        return NULL;
    }
}
