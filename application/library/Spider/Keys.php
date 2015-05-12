<?php
class Spider_Keys {

    const SPIDER_SCHOOL_NAME_KEY = 'hset_school_names';

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
}
