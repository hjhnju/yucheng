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

$smarty->assign('userphone', '189****1234');
$smarty->assign('userurl','http://');
$smarty->assign('inviterinfo',
    array(
        0 => array(
            'id'=> 1,
            'name'=> 'lilu',
            'phone'=> '189****1525',
            'registprogress'=> 1,
            'canBeAwarded'=> 1,
            'tenderAmount'=> 10,
            'award'=> 20
        ),
        1 => array(
            'id'=> 2,
            'name'=> 'lilu',
            'phone'=> '189****1525',
            'registprogress'=> 2,
            'canBeAwarded'=> 0,
            'tenderAmount'=> 10,
            'award'=> 30
        ),
        2 => array(
            'id'=> 3,
            'name'=> 'lilu',
            'phone'=> '189****1525',
            'registprogress'=> 2,
            'canBeAwarded'=> 1,
            'tenderAmount'=> 10,
            'award'=> 30
        )
    )
);


$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('my/reward.phtml');

echo $output;




