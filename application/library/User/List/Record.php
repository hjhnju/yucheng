<?php
/**
 * 用户登录历史纪录表 列表类
 * @author 
 */
class User_List_Record extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_record';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'status', 'ip', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}