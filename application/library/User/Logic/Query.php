<?php
/**
 * 注册Logic层
 */
class User_Logic_Query{
    /**
     * 查询用户
     */
    public function queryBorrowers($page, $pagesize){
        $list    = new User_List_Login();
        $filters = array('isborrower' => 1);
        $list->setFilter($filters);
        $list->setOrder('create_time desc');
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list = $list->toArray();
        return $list;
    }

    /**
     * 查询用户
     */
    public function queryCorpUsers($page, $pagesize){
        //TODO:User_List
        $list    = new User_List_Login();
        $filters = array('usertype' => User_Type_Roles::TYPE_CORP);
        $list->setFilter($filters);
        $list->setOrder('create_time desc');
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list = $list->toArray();
        //TODO:增加用户信息
        return $list;
    }
    
    /**
     * 查询私人用户
     * @param int $page
     * @param int $pagesize
     * @return array
     */
    public function queryPrivUsers($page, $pagesize, $user){
        //TODO:User_List
        $list    = new User_List_Login();
        $filters = array('usertype' => User_Type_Roles::TYPE_PRIV);
        if(!empty($user)){
            $type = User_Logic_Validate::getType($user);
            if(empty($type)){
                $type = 'name';
            }
            $filters = array('usertype' => User_Type_Roles::TYPE_PRIV, $type => $user);
        }
        $list->setFilter($filters);
        $list->setOrder('userid desc');
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list = $list->toArray();
        //TODO:增加用户信息
        return $list;
    }
    

}
