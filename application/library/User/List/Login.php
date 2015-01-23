<?php
/**
 *  列表类
 * @author hejunhua
 */
class User_List_Login extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_login';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'usertype', 'status', 'name', 'passwd', 'phone', 'email', 'lastip', 'login_time', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'userid'      => 1,
        'usertype'    => 1,
        'status'      => 1,
        'login_time'  => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}