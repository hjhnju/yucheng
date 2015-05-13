<?php
/**
 * 客发信息查询
 * @author huwei
 *
 */
class ListAction extends Yaf_Action_Abstract {
    public function execute() { 
        $intPageSize = 15; 
        $page    = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $type    = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'kindergarten';
        $place   = isset($_REQUEST['place']) ? $_REQUEST['place'] : 'beijing';
        $nature  = isset($_REQUEST['nature']) ? $_REQUEST['nature'] : 'private';
        
        $redis     = Base_Redis::getInstance();

        $key = Spider_Keys::getSchoolReferKey($place, $type, $nature);
        $count = $redis->sCard($key);
        $pageAll = ceil($count/$intPageSize);
        
        
        $arrSchool = $redis->sMembers($key);        
        $arrInfos = array();
        foreach ($arrSchool as $id){
            $info = $redis->hGetAll("hset_school_".$id);
            $info['id'] = $id;
            if(empty($info['tag'])){
                $info['tag'] = '待跟进';
            }
            if(empty($info['phone'])||(strstr($info['phone'],'暂无'))){
                $arrInfos[] = $info;
            }else{
                array_unshift($arrInfos,$info);
            }
        }
        
        $arrInfos = array_slice($arrInfos,($page-1)*$intPageSize,$intPageSize);
       
        $this->getView()->assign('arrInfo', $arrInfos);
        $this->getView()->assign('pageall', $pageAll);
        $this->getView()->assign('page', $page);
        $this->getView()->assign('type', $type);
        $this->getView()->assign('place', $place);
        $this->getView()->assign('nature', $nature);
    }
    
    /**
     * 返回公立或私立状态
     * @param array $arr
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