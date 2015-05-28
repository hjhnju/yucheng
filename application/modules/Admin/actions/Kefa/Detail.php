<?php

/**
 * 客发信息详情及修改
 * @author huwei
 */
class DetailAction extends Yaf_Action_Abstract
{

    public function execute()
    {
        $id    = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $type  = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $redis = Base_Redis::getInstance();
        $info  = $redis->hGetAll("hset_school_".$id);
        $info['id'] = $id;
      
        $this->strFilter($info);
        if((''==$info['tag']) ||(empty($info['tag']))){
            $info['tag'] = '待跟进';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->assign('type', $type);
        
        if ('save' == $type) {
            $arrRet = array(
               'name'       => isset($_REQUEST['name'])?$_REQUEST['name']:'',
               'address'    => isset($_REQUEST['address'])?$_REQUEST['address']:'',
               'president'  => isset($_REQUEST['president'])?$_REQUEST['president']:'',
               'phone'      => isset($_REQUEST['phone'])?$_REQUEST['phone']:'',
               'weight'     => isset($_REQUEST['weight'])?$_REQUEST['weight']:'',
               'website'    => isset($_REQUEST['website'])?$_REQUEST['website']:'',
               'introduct'  => isset($_REQUEST['introduct'])?$_REQUEST['introduct']:'',
               'regulation' => isset($_REQUEST['regulation'])?$_REQUEST['regulation']:'',
               'evaluate'   => isset($_REQUEST['evaluate'])?$_REQUEST['evaluate']:'',
               'tag'        => isset($_REQUEST['tag'])?$_REQUEST['tag']:'',
            );
            foreach ($arrRet as $key => $val){
                if(!empty($val)){
                    $redis->hset("hset_school_".$id, $key , $val);
                }
            }
        }
    }
    
    public function strFilter(&$info){
        $arrFields = array('president','regulation','weight','website','phone','zip_code','address',
            'foundation_time','nature','tag','foundation_time',
        );
        $arrFilters = array('<br />','<p>','</p>');
        foreach ($arrFields as $field){
            if(!isset($info[$field])){
                $info[$field] = '';
            }
        }
        foreach ($arrFilters as $filter){
            foreach ($info as $key => $val){
               $info[$key] = str_ireplace($filter,"",$val);
            }
        }
        
        /*foreach ($info as $key => $val){
            foreach ($arrFilters as $filter){
                $info[$key] = str_ireplace($filter,"",$val);
            }
            if(strstr($val,"暂无")){
                $info[$key] = '';
            }
        }*/
    }
}