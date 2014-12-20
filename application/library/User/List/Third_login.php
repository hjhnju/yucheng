<?php
/**
 *  列表类
 * @author 
 */
class User_List_Third_login extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_third_login';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'status';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'type', 'nickname', 'openid', 'status', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}