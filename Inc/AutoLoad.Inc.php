<?php

/*
 * Feedback [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * 自动加载AutoLoad类文件
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

namespace FeedBack\Inc {
    
    class Import {
    
        public static function load ($classname) {
            $file = ROOT .'/'. str_replace("\\","/",substr($classname,8)).'.Inc.php';
            if(!is_file($file)) {
                \FeedBack\Inc\Error::showException("{$file} not exists");
            } else {
                require $file;
            }
        }
    }
}

namespace {
    function __autoload ($classname) {
        \FeedBack\Inc\Import::load($classname);
    }
}
