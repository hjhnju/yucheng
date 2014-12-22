<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 14-12-21
 * Time: 下午8:59
 */
date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->assign('page', 1);
$smarty->assign('pagesize', 10);
$smarty->assign('pageall', 1);
$smarty->assign('total', 1);
$smarty->assign('list',
    array(
        0 => array(
            'id' => 1,
            'user_id' => 1,
            'title' => '借点钱买车1',
            'pic' => 'http://',
            'content' => '不说明看能不能借到钱',
            'type_id' => 1,
            'cat_id' => 1,
            'duration' => 12,
            'level' => 1,
            'amount' => 200000.00,
            'interest' => 12.00,
            'invest_cnt' => 0,
            'invest_amount' => 0.00,
            'safe_id' => 1,
            'audit_info' => '经过审核可以借',
            'deadline' => '2014-12-27 11:19:21',
            'status' => 1,
            'create_time' => '2014-12-20 11:19:21',
            'update_time' => '2014-12-20 11:19:21',
            'create_uid' => 1,
            'full_time' => '2014-12-20 11:19:21',
            'pay_time' => '2014-12-20 11:19:21',
            'loan_type' => '实地认证标',
            'loan_cat' => '学校助力贷',
            'safemode' => '本金保障计划',
            'percent' => 0.00
        ),
        1 => array(
            'id' => 1,
            'user_id' => 1,
            'title' => '借点钱买车2',
            'pic' => 'http://',
            'content' => '不说明看能不能借到钱',
            'type_id' => 1,
            'cat_id' => 1,
            'duration' => 12,
            'level' => 1,
            'amount' => 200000.00,
            'interest' => 12.00,
            'invest_cnt' => 0,
            'invest_amount' => 0.00,
            'safe_id' => 1,
            'audit_info' => '经过审核可以借',
            'deadline' => '2014-12-27 11:19:21',
            'status' => 1,
            'create_time' => '2014-12-20 11:19:21',
            'update_time' => '2014-12-20 11:19:21',
            'create_uid' => 1,
            'full_time' => '2014-12-20 11:19:21',
            'pay_time' => '2014-12-20 11:19:21',
            'loan_type' => '实地认证标',
            'loan_cat' => '学校助力贷',
            'safemode' => '本金保障计划',
            'percent' => 0.00
        )
    )
);

$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('project/list.phtml');

echo $output;