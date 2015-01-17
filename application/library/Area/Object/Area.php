<?php
/**
 * 省份城市信息
 * @author jiangsongfang
 */
class Area_Object_Area extends Base_Object {
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
     * 类名
     * @var string
     */
    const CLASSNAME = 'Area_Object_Area';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'name', 'pinyin', 'status', 'pid', 'province', 'city', 'huifu_cityid', 'sort');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'name'        => 'name',
        'pinyin'      => 'pinyin',
        'status'      => 'status',
        'pid'         => 'pid',
        'province'    => 'province',
        'city'        => 'city',
        'huifu_cityid'=> 'huifuCityid',
        'sort'        => 'sort',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'status'      => 1,
        'pid'         => 1,
        'province'    => 1,
        'city'        => 1,
        'huifu_cityid'=> 1,
        'sort'        => 1,
    );

    /**
     * @param array $data
     * @return Area_Object_Area
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 
     * @var integer
     */
    public $id;

    /**
     * 名称
     * @var string
     */
    public $name;

    /**
     * 拼音
     * @var string
     */
    public $pinyin;

    /**
     * 状态
     * @var integer
     */
    public $status;

    /**
     * 父ID 0为省
     * @var integer
     */
    public $pid;

    /**
     * 所属省
     * @var integer
     */
    public $province;

    /**
     * 所属市
     * @var integer
     */
    public $city;

    /**
     * 汇付城市映射ID
     * @var integer
     */
    public $huifuCityid;

    /**
     * 排序
     * @var integer
     */
    public $sort;

}
