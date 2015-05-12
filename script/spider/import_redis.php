<?php
/**
 * @file   解析指定文件夹中的文件
 * @author 郭金利
 */

//载入环境配置文件
Yaf_Loader::import(dirname(dirname(__FILE__)) . '/env.inc.php');

class ImportRedis{
    private $_school_name_key;

    public function __construct(){
        $this->_school_name_key = Spider_Keys::getSchoolNameKey();
    }
    /**
     * @param $delete 是否删除redis中的数据
     * @return null
     * 获得需要入到redis中的数据
     */
    public function getSchoolList($province, $type, $index, $delete=false){
        $collect = Spider_Collect_Base::getInstance();
        $list = $collect->readAllFiles($province, $type);
        if(empty($index)){
            $index = 1000;
        }
        if($delete){
            $this->deleteFromRedis();
            return ;
        }
        foreach ($list[$province][$type] as $key=>$item){
            $this->writeIntoRedis($item, $index+$key);
        }        
        return $index+$key;
    }

    /**
     * @param $school    包含学校详细信息
     * @param $school_id 学校的自增id
     * @return null
     * 将一个学校数据写入redis
     */
    public function writeIntoRedis($school, $school_id) {
        $school_basic_key = Spider_Keys::getSchoolBasicKey($school_id);
        $redis = Base_Redis::getInstance();
        $redis->multi();
        $redis->watch(array($this->_school_name_key, $school_basic_key));
        //添加学校hash set
        $redis->hset($this->_school_name_key, $school['name'], $school_id);

        //添加学校基本信息
        foreach ($school as $key=>$value){
            $redis->hset($school_basic_key, $key , $value);
        }
        $redis->exec();
    }

    public function deleteFromRedis() {
        $redis     = Base_Redis::getInstance();
        $redis->delete("hset_school_names");
        $arrSchool = $redis->keys("hset_school_*");
        foreach ($arrSchool as $school){
            $redis->delete($school);        
        }
    }
}

$obj = new ImportRedis();
$list = array(
    'beijing' => array('kindergarten', 'middle'),
    'guangxi' => array('kindergarten', 'middle'),
);
$index = 0;
foreach ($list as $province=>$item){
    foreach ($item as $value){
        $index = $obj->getSchoolList($province, $value, $index, false);
    }
}

