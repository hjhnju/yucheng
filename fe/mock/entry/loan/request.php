<?php
/**
 * Created by IntelliJ IDEA.
 * User: mySunShinning(441984145@qq.com)
 *       yangbinYB(1033371745@qq.com)
 * Date: 14-12-16
 * Time: 上午11:31
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty -> assign("school",
    array (
        "1" => "学前教育",
        "2" => "基础教育",
        "3" => "职业教育",
        "4" => "高等教育",
        "5" => "教育机构培训"
    )
);
$smarty -> assign("usage",
    array (
        "1" => "资金周转",
        "2" => "设备更新",
        "3" => "改建扩建",
        "4" => "其他"
    )
);
$smarty -> assign("refund_type",
    array (
        "1" => "等额本息",
        "2" => "按月付息,到期还本"
    )
);
$smarty -> assign("province",
    array (
        0 => array(
            "id" => 1,
            "name" => "北京"
        ),
        1 => array(
            "id" => 21,
            "name" => "天津"
        ),
        2 => array(
            "id" => 40,
            "name" => "上海"
        ),
        3 => array(
            "id" => 61,
            "name" => "重庆"
        ),
        4 => array(
            "id" => 102,
            "name" => "河北"
        ),
        5 => array(
            "id" => 297,
            "name" => "山西省"
        ),
        6 => array(
            "id" => 439,
            "name" => "内蒙古区"
        ),
        7 => array(
            "id" => 561,
            "name" => "辽宁省"
        ),
        8 => array(
            "id" => 690,
            "name" => "吉林省"
        ),
        9 => array(
            "id" => 768,
            "name" => "黑龙江省"
        ),
        10 => array(
            "id" => 924,
            "name" => "江苏省"
        ),
        11 => array(
            "id" => 1057,
            "name" => "浙江省"
        ),
        12 => array(
            "id" => 1170,
            "name" => "安徽省"
        ),
        13 => array(
            "id" => 1310,
            "name" => "福建省"
        ),
        14 => array(
            "id" => 1414,
            "name" => "江西省"
        ),
        15 => array(
            "id" => 1536,
            "name" => "山东省"
        ),
        16 => array(
            "id" => 1711,
            "name" => "河南省"
        ),
        17 => array(
            "id" => 1905,
            "name" => "湖北省"
        ),
        18 => array(
            "id" => 2034,
            "name" => "湖南省"
        ),
        19 => array(
            "id" => 2184,
            "name" => "广东省"
        ),
        20 => array(
            "id" => 2403,
            "name" => "广西省"
        ),
        21 => array(
            "id" => 2541,
            "name" => "海南省"
        ),
        22 => array(
            "id" => 2570,
            "name" => "四川省"
        ),
        23 => array(
            "id" => 2791,
            "name" => "贵州省"
        ),
        24 => array(
            "id" => 2892,
            "name" => "云南省"
        ),
        25 => array(
            "id" => 3046,
            "name" => "西藏"
        ),
        26 => array(
            "id" => 3128,
            "name" => "陕西省"
        ),
        27 => array(
            "id" => 3256,
            "name" => "甘肃省"
        ),
        28 => array(
            "id" => 3369,
            "name" => "青海省"
        ),
        29 => array(
            "id" => 3422,
            "name" => "宁夏省"
        ),
        30 => array(
            "id" => 3454,
            "name" => "新疆省"
        ),
    )
);



$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('loan/request.phtml');

echo $output;




