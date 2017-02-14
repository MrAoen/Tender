<?php
//date_default_timezone_set('Asia/Tbilisi');
//date_default_timezone_set('America/Los_Angeles');
//$globals['serv_home'] = 'http://www.dneproplast.com/';
//$globals['db_user'] = 'dneproplast_tnd';
//$globals['db_pass'] = 'NtyltHLytghjgkfcN';
//$globals['db_name'] = 'tender_dneproplast';
//$globals['serv_home'] = 'http://retal.kz/';
//$globals['serv_home'] = 'http://tender.localhost/';
//$globals['serv_home'] = 'http://retal.kz/';
$globals['serv_home'] = 'http://retal.kz/';
$globals['display_errors'] = 'on';
$globals['db_host'] = 'localhost';
$globals['db_user'] = 'retalkz';
$globals['db_pass'] = '89nb8Jc3Ti';
$globals['db_name'] = 'retalkz_tender';
$globals['default_lang'] = 'ru';
$globals['avail_lang'] = array(
    'ru' => array(
        'title' => 'Русский',
        'menu' => 'РУС',
    ),


);

date_default_timezone_set('Asia/Almaty');
$globals['serv_prefix'] = 'dp';
$globals['datetime'] = array(
    'ymd' => array(
        'long' => 'j M Y',
        'short' => 'd.m.Y',
        'str_mask' => '/^(\\d{1,2})\\.(\\d{1,2})\\.(\\d{4})$/',
        'int_mask' => '$3-$2-$1',
    ),
    'ymdhi' => array(
        'long' => 'j M Y, H:i',
        'short' => 'd.m.Y, H:i',
        'str_mask' => '/^(\\d{1,2})\\.(\\d{1,2})\\.(\\d{4})\\, (\\d{1,2})\\:(\\d{1,2})$/',
        'int_mask' => '$3-$2-$1-$4-$5',
    ),
    'ymdhis' => array(
        'long' => 'j M Y, H:i:s',
        'short' => 'd.m.Y, H:i:s',
        'str_mask' => '/^(\\d{1,2})\\.(\\d{1,2})\\.(\\d{4})\\, (\\d{1,2})\\:(\\d{1,2})\\:(\\d{1,2})$/',
        'int_mask' => '$3-$2-$1-$4-$5-$6',
    ),
);
?>