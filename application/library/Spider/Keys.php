<?php
class Spider_Keys {

    const SPIDER_SCHOOL_NAME_KEY = 'hset_school_names';

    const SPIDER_SCHOOL_REFER_KEY = 'set_school_%s_%s_%s';
    
    const SPIDER_SCHOOL_BASIC_KEY = 'hset_school_%d';

    /**
     * @param null
     * @return 返回redis中学校名字的集合
     */
    public static function getSchoolNameKey(){
        return self::SPIDER_SCHOOL_NAME_KEY;
    }

    /**
     * @param $school_id 学校的id
     * @return 根据学校的id，返回redis中学校基本信息的集合
     */
    public static function getSchoolBasicKey($school_id){
        return sprintf(self::SPIDER_SCHOOL_BASIC_KEY, $school_id);
    }
    
    /**
     * 索引键名
     * @param string $school_place 学校所在地
     * @param string $school_type  学校类型，小学、幼儿园...
     * @param string $school_nature 学校性质，公立、私立...
     * @return string
     */
    public static function getSchoolReferKey($school_place,$school_type,$school_nature){
        return sprintf(self::SPIDER_SCHOOL_REFER_KEY, $school_place,$school_type,$school_nature);
    }
}
