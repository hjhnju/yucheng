<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 15-1-3
 * Time: 下午1:50
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->assign('title', '小毛驴');
$smarty->assign('ctx', '我有一头小毛驴我从来也不骑，有一天我骑着它到市上去赶集，我手里拿着小皮鞭我心里正得意，不知怎么哗啦啦啦摔了我一身泥~！');
$smarty->assign('author', '武小琦');
$smarty->assign('publish_time', 1420266778);

$smarty->assign('webroot', '/');

$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('company/detail.phtml');

echo $output;