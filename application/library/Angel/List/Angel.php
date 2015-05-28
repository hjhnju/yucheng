<?php
/**
 * 天使关注信息表 列表类
 * @author huwei
 */
class Angel_List_Angel extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'angel';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'angelid', 'angelcode', 'angelname', 'angelimage', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'angelid'     => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Angel_Object_Angel[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Angel_Object_Angel');
    }

}