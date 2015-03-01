<?php
/**
 *  列表类
 * @author hejunhua
 */
class User_List_Info extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_info';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'realname', 'certificate_type', 'certificate_content', 'headurl', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'userid'              => 1,
        'certificate_type'    => 1,
        'create_time'         => 1,
        'update_time'         => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|User_Object_Info[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('User_Object_Info');
    }

}