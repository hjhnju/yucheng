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
        $arrSchool = $redis->keys("hset_school_*");
        foreach ($arrSchool as $school){
            if("names" == $school){
                continue;
            }
            $info = $redis->hGetAll($school);
            if(($type  == $info['type_en'])&&
                ($place == $info['province'])&&
                (empty($info['nature'])||($nature == $this->getNature($info['nature'])))){
                sscanf($school,"hset_school_%d",$id);                
                $info['id'] = $id;               
                $arrInfos[] = $info;
            }
        } 
        $pageAll = ceil(count($arrInfos)/$intPageSize);
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
     * @param string $str
     * @return string:publi|private|both
     */
    public function getNature($str){
        $arrPublic = array('公立','国立');
        if(strstr($str,'公立')||str){
            return 'public';
        }else{
            return 'private';
        }
    }
}