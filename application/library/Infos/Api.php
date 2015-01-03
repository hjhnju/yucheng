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
    public static function getNewPost(){
        $logic   = new Infos_Logic_Post();
        $list    = $logic->getList(1, 1);
        $newPost = null;
        if($list['total'] === 1){
            $newPost = array_pop($list);
        }
        return $newPost;
    }
    
}
