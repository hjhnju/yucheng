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
        return $list['list'][0];
    }   
}
