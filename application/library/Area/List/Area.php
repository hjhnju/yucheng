<?php
/**
 * 省份城市信息 列表类
 * @author jiangsongfang
 */
class Area_List_Area extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'area';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'name', 'pinyin', 'status', 'pid', 'province', 'city', 'sort');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'      => 1,
        'status'  => 1,
        'pid'     => 1,
        'province'=> 1,
        'city'    => 1,
        'sort'    => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Area_Object_Area[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Area_Object_Area');
    }

}