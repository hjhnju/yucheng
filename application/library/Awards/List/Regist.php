<?php
/**
 * 注册奖励表 列表类
 * @author 
 */
class Awards_List_Regist extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_regist';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'status', 'amount', 'memo', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}