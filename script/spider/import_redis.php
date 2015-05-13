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
            $this->writeIntoRedis($province, $type, $item, $index+$key);
        }        
        return $index+$key;
    }

    /**
     * @param $school    包含学校详细信息
     * @param $school_id 学校的自增id
     * @return null
     * 将一个学校数据写入redis
     */
    public function writeIntoRedis($province, $type, $school, $school_id) {
        $school_basic_key = Spider_Keys::getSchoolBasicKey($school_id);
        $redis = Base_Redis::getInstance();
        $redis->multi();
        $redis->watch(array($this->_school_name_key, $school_basic_key));
        //添加学校hash set
        $redis->hset($this->_school_name_key, $school['name'], $school_id);
        //添加学校基本信息
        $school['province'] = $province;
        $school['type_en']  = $type;
        foreach ($school as $key=>$value){
            $redis->hset($school_basic_key, $key , $value);
        }
        $key = Spider_Keys::getSchoolReferKey($province, $type, $this->getNature($school));
        $redis->sAdd($key,$school_id);
        $redis->exec();
    }

    public function deleteFromRedis() {
        $redis     = Base_Redis::getInstance();
        $redis->delete("hset_school_names");
        $arrSchool = $redis->keys("hset_school_*");
        foreach ($arrSchool as $school){
            $redis->delete($school);        
        }
        
        $arrKeys = $redis->keys("set_school_*");
        foreach($arrKeys as $val){
            $redis->delete($val);
        }
    }
    
    /**
     * 返回公立或私立状态
     * @param string $arr
     * @return string:publi|private|both
     */
    public function getNature($arr){
        $arrPublic = array('公立','国立','公办');
        if(!isset($arr['nature']) || (empty($arr['nature']))){
            return 'unknow';
        }elseif(in_array($arr['nature'],$arrPublic)){
            return 'public';
        }else{
            return 'private';
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
        $index = $obj->getSchoolList($province, $value, $index,false);
    }
}
/*$redis = Base_Redis::getInstance();
$keys = $redis->keys("*");
var_dump($keys);*/