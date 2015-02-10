<?php
/**
 * 对外的API接口
 */
class Infos_Api {
    
    /** 
     * Infos_Api::getNewPost()
     * 获取最新公告
     * @return $arrPost | null
     */
    public static function getNewPost($strType){
        $logic   = new Infos_Logic_Post();
        $list    = $logic->getList(1, 1,$strType);
        unset($list['list'][0]['content']);
        $ret = isset($list['list'][0]) ? $list['list'][0] : null;
        return $ret;
    }   
    
    /**
     * 获取未发布公告列表
     * @param int $page
     * @param int $pagesize
     * @return array
     */
    public static function getAllPost($page, $pagesize){
        $logic   = new Infos_Logic_Post();
        $list1    = $logic->getList($page, $pagesize, 'post', 1);
        $list2    = $logic->getList($page, $pagesize, 'post', 2);
        $list3    = $logic->getList($page, $pagesize, 'media', 1);
        $list4    = $logic->getList($page, $pagesize, 'media', 2);
        return array_merge($list1['list'],$list2['list'],$list3['list'],$list4['list']);
    }
}
