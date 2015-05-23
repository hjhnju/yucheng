<?php
/**
 *  列表类
 * @author hejunhua
 */
class Apply_List_Apply extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'amount', 'duration', 'duration_type', 'userid', 'service_charge', 'rate', 'status', 'start_time', 'end_time', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'duration'        => 1,
        'duration_type'   => 1,
        'userid'          => 1,
        'status'          => 1,
        'start_time'      => 1,
        'end_time'        => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Apply_Object_Apply[]
     * 返回的是一个数组，每个元素是一个Apply_Object_Apply对象
     */
    public function getObjects() {
        return parent::getObjects('Apply_Object_Apply');
    }

}