<?php

/*
 * Feedback [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * 错误处理类
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

namespace FeedBack\Inc;

class Error {

    /**
     * ErrorCode:
     *     1001 - appid or rand or token 为空
     *     1002 - 应用不存在
     *     1003 - 请求的方法不存在
     *     1004 - Token验证失败
     *     2001 - 无法连接MySQL数据库
     *     2002 - 数据库选择失败
     *     2003 - SQL查询失败
     *     2004 - SQL写入失败
     */
    static function showError($msg, $errcode = 1999) {
        $error = array(
            'message' => $msg,
            'errcode' => $errcode,
            'data' => '',
        );
        die(json_encode($error));
    }

    static function showException($message) {
        die("Error: {$message}");
    }
}
