<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 14-12-21
 * Time: 下午8:56
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->assign('user',
    array(
        'amount' => '100,000.00'
    )
);

$smarty->assign('id', 'JK_201412181222');
$smarty->assign('user_id', 1);
$smarty->assign('title', '借点钱买车');
$smarty->assign('pic', 'http://');
$smarty->assign('content', '不说明看能不能借到钱');
$smarty->assign('type_id', 1);
$smarty->assign('cat_id', 1);
$smarty->assign('duration', 12);
$smarty->assign('level', 1);
$smarty->assign('amount', '200000.00');
$smarty->assign('interest', '12');
$smarty->assign('invest_cnt', 1000000);
$smarty->assign('amount_rest', '200,000.00');
$smarty->assign('invest_amount', 0.00);
$smarty->assign('safe_id', 1);
$smarty->assign('refund_type', 1);
$smarty->assign('audit_info', '经过审核可以借');
$smarty->assign('deadline', 1420291877);
$smarty->assign('status', 1);
$smarty->assign('create_time', 1419312345);
$smarty->assign('update_time', 1419344538);
$smarty->assign('create_uid', 1);
$smarty->assign('full_time', '');
$smarty->assign('pay_time', '');
$smarty->assign('days', 365);
$smarty->assign('loan_type', '实地认证标');
$smarty->assign('loan_cat', '学校助力贷');
$smarty->assign('duration_type', '个月');
$smarty->assign('safemode', array(

    '1' => '本金保障计划',
    '2' => '本金计划',
    '3' => '妈蛋计划'



));
$smarty->assign('refund_typename', '等额本息');
$smarty->assign('percent', 0.00);
$smarty->assign('company',
    array(
        'id' => 2,
        'loan_id' => 1,
        'user_id' => 1,
        'school' => '北京师范大学',
        'area' => '北京西城',
        'assets' => '2000万',
        'employers' => 25,
        'years' => 2,
        'funds' => '1000万',
        'students' => 500,
        'create_time' => '2014-12-20 12:49:12',
        'update_time' => '2014-12-20 12:49:12'
    )
);
$smarty->assign('counter',
    array(
        'user_id' => 1,
        'success' => 2,
        'limit' => '100,000,000',
        'total' => '100,000,000',
        'finished' => 6,
        'refund' => '100,000',
        'rest' => '100,000,000',
        'status' => 1,
        'create_time' => '2014-12-20 12:49:12',
        'update_time' => '2014-12-20 12:49:12'
    )
);
$smarty->assign('guarantee',
    array(
        'id' => 2,
        'loan_id' => 1,
        'user_id' => 1,
        'name' => '王某某',
        'account' => '北京安顺',
        'age' => 39,
        'marriage' => 1,
        'company_type' => '基础教育学校',
        'job_title' => '校长',
        'income' => '20-30万',
        'status'=> 1,
        'create_time' => '2014-12-20 12:49:12',
        'update_time' => '2014-12-20 12:49:12'
    )
);
$smarty->assign('audit',
    array(
        '1' => array(
            0 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'name' => '实地认证',
                'comment' => '通过',
                'status' => 1,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            ),
            1 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'name' => '营业执照',
                'comment' => '通过',
                'status' => 1,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            )
        ),
        '2' => array(
            0 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'name' => '身份证',
                'comment' => '通过',
                'status' => 1,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            ),
            1 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'name' => '户口本',
                'comment' => '通过',
                'status' => 1,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            )
        )
    )
);

$smarty->assign('attach',
    array(
        '1' => array(
            0 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'title' => '身份证',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            ),
            1 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 1,
                'title' => '身份证',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            )
        ),
        '2' => array(
            0 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 2,
                'title' => '合同',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            ),
            1 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 2,
                'title' => '合同',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            )
        ),
        '3' => array(
            0 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 3,
                'title' => '场地',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            ),
            1 => array(
                'id' => 5,
                'loan_id' => 1,
                'user_id' => 1,
                'type' => 3,
                'title' => '场地',
                'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
                'status' => 0,
                'create_time' => '2014-12-20 12:15:50',
                'update_time' => '2014-12-20 12:15:50'
            )
        )
    )
);
$smarty->assign('refunds',
    array(
        0 => array(
            'period' => 1,
            'amount' => '2,038.36',
            'capital' => 0,
            'interest' => '2,038.36',
            'promise_time' => 1484848484,
            'status' => 1
        ),
        1 => array(
            'period' => 1,
            'amount' => 2,038.36,
            'capital' => 0,
            'interest' => 2,038.36,
            'promise_time' => 1484848484,
            'status' => 2
        ),
        2 => array(
            'period' => 1,
            'amount' => 2,038.36,
            'capital' => 0,
            'interest' => 2,038.36,
            'promise_time' => 1484848484,
            'status' => 3
        )
    )
);


$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('project/detail.phtml');

echo $output;