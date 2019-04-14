<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-11
 * Time: 15:47
 */
$conf = array(
    'SITE_URL' => env('APP_URL'),
    'STATIC_URL' => env('APP_URL'),
);

echo "var G = " . json_encode($conf);
