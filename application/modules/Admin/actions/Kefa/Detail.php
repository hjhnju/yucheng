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
        $this->getView()->assign('info', $info);
        $this->getView()->assign('type', $type);
        
        if ('save' == $type) {
            $arrRet = array(
               'name'       => $_REQUEST['name'],
               'address'    => $_REQUEST['address'],
               'president'  => $_REQUEST['president'],
               'phone'      => $_REQUEST['phone'],
               'weight'     => $_REQUEST['weight'],
               'website'    => $_REQUEST['website'],
               'introduct'  => $_REQUEST['introduct'],
               'regulation' => $_REQUEST['regulation'],
               'evaluate' => $_REQUEST['evaluate'],
               'tag' => $_REQUEST['tag'],
            );
            foreach ($arrRet as $key => $val){
                $redis->hset("hset_school_".$id, $key , $val);
            }
        }
    }
    
    public function strFilter(&$info){
        $arrFields = array('president','regulation','weight','website','phone','zip_code','address');
        $arrFilters = array('<br />','<p>','</p>');
        foreach ($arrFields as $field){
            if(!isset($info[$field]) ||empty($info[$field])){
                $info[$field] = '暂无信息';
            }
        }
        foreach ($arrFilters as $filter){
            foreach ($info as $key => $val){
                $info[$key] = str_ireplace($filter,"",$val);
            }
        }
    }
}