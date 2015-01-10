<?php
/**
 * 管理员信息表 列表类
 * @author jiangsongfang
 */
class Admin_List_Admin extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'admin';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'uid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'name', 'role', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'uid'         => 1,
        'role'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}