<?php

/*
 * Feedback [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * 程序运行核心支持文件
 *
 * 进行参数过滤,路由转发,module初始化等
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

namespace FeedBack\Inc;

class Core {

    private static $obj = NULL;

    public static function getInstance() {
        if(!self::$obj) {
            self::$obj = new self;
        }
        return self::$obj;
    }

    private function __construct() {}

    public function run() {
        $this->init();
        return new \FeedBack\Inc\Module($this->checkParam());
    }

    public function init() {
        if(function_exists('date_default_timezone_set')) {
            @date_default_timezone_set("Etc/GMT-8");
        }
        if(function_exists('ob_gzhandler') && \FeedBack\Conf\Config::$gzip && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }
        ob_implicit_flush(0);
        \FeedBack\Inc\View::outHeader();
    }

    public function checkParam() {
        $fields = array('type', 'appid', 'content', 'mid', 'uuid', 'contact', 'token', 'rand', 'page', 'limit');
        $param = array();
        $param['act'] = isset($_GET['act']) ? $_GET['act']: '';
        foreach($fields as $val) {
            $param[$val] = isset($_POST[$val]) ? $_POST[$val]: '';
        }
        return $param;
    }
}
