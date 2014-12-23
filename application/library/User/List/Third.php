<?php
/**
 * 用户第三方登陆表 列表类
 * @author jiangsongfang
 */
class User_List_Third extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_third';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'authtype', 'nickname', 'openid', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'authtype'    => 1,
    );

}