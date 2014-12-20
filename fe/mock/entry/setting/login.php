<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 14-12-20
 * Time: 上午10:55
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->assign('message',
    array(
        'addtime' => '1408859074',
        'title' => 'tilte',
        'content' => 'content',
        'coverurl' => 'http://echarts.baidu.com/doc/asset/img/slide-01.png',
        'displaycover' => 'displaycover',
        'author' => 'author',
        'sourceurl' => 'sourceurl'
    )
);

$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('setting/login.phtml');

$livereload = '<script src="http://'.getHostByName($_SERVER['SERVER_NAME']).':8898/livereload.js"></script></body>';

echo str_replace("</body>", $livereload, $output);