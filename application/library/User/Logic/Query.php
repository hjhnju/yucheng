<?php
/**
 * 注册Logic层
 */
class User_Logic_Query{

    //用户类型－个人用户
    const TYPE_PRIV = 1;
    //用户类型－企业用户
    const TYPE_CORP = 2;
    
    /**
     * 查询用户
     */
    public function queryCorpUsers($page, $pagesize){
        //TODO:User_List
        $list    = new User_List_Login();
        $filters = array('usertype' => self::TYPE_CORP);
        $list->setFilter($filters);
        $list->setOrder('create_time desc');
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list = $list->toArray();
        //TODO:增加用户信息
        return $list;
    }

}
