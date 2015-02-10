<?php
ini_set('date.timezone', 'Asia/Shanghai');
$dt = new DateTime('today');
var_dump($dt);

$dt->modify('+1days');
var_dump($dt);

$date = new DateTime();
$str = $date->format('YmdHisu');
var_dump($str);

