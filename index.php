<?php

/*
 * Feedback [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * FeedBack项目入口文件
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

if(version_compare(PHP_VERSION,'5.3.0','<') ) {
    die('require PHP 5.3+');
}
define("ROOT", dirname(__FILE__));
include ROOT.'/Inc/AutoLoad.Inc.php';
\FeedBack\Inc\Core::getInstance()->run();
