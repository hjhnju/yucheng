<?php
/**
 * 用户登录历史纪录表 列表类
 * @author jiangsongfang
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
        'id'          => 1,
        'userid'      => 1,
        'status'      => 1,
        'create_time' => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|User_Object_Record[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('User_Object_Record');
    }

}