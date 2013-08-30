<?php

/*
 * Feedback [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * Module核心类
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

namespace FeedBack\Inc;

class Module {

    var $db;

    var $param;

    public function __construct($param) {
        $this->db = new \FeedBack\Inc\DbMysql(\FeedBack\Conf\Config::$db);
        $this->param = $param;
        $this->checkAuth();
        $this->method();
    }

    public function checkAuth() {
        if(empty($this->param['appid']) || empty($this->param['rand']) || empty($this->param['token'])) {
            \FeedBack\Inc\Error::showError('token check wrong', 1001);
        }
        $appInfo = $this->db->find('application', '*', array('appid' => intval($this->param['appid'])), '`appid` ASC', '1');
        if(empty($appInfo)) {
            \FeedBack\Inc\Error::showError('application not exists', 1002);
        }
        $arr = array('appid' => $this->param['appid'], 'secret' => $appInfo[0]['secret'], 'rand' => $this->param['rand']);
        asort($arr, SORT_STRING);
        $token = sha1(implode('', $arr));
        if($token !== $this->param['token']) {
            \FeedBack\Inc\Error::showError('token error', 1004);
        }
    }

    public function method() {
        switch($this->param['act']) {
            case 'sendFeedBack':
                $this->sendFeedBack();
                break;
            case 'getFeedBack':
                $this->getFeedBack();
                break;
            default:
                \FeedBack\Inc\Error::showError('Method Empty Or Wrong', 1003);
                break;
        }
    }

    public function sendFeedBack() {
        $data = array(
            'type' => intval($this->param['type']),
            'appid' => intval($this->param['appid']),
            'mid' => intval($this->param['mid']),
            'uuid' => $this->param['uuid'],
            'dateline' => time(),
            'contact' => $this->param['contact'],
            'content' => $this->param['content'],
        );
        if($this->db->insert($data, 'feedback')) {
            \FeedBack\Inc\View::output(true, 'Data Save Succeed');
        } else {
            \FeedBack\Inc\Error::showError('Data Save Error', 1002);
        }
    }

    public function getFeedBack() {
        $where = array('type' => $this->param['type']);
        $table = 'feedback';
        $field = '*';
        $order = '`id` ASC';
        $page = max(intval($this->param['page']), 1);
        $prpage = intval($this->param['limit']);
        $limit = (($page - 1) * $prpage) . ",{$prpage}";
        if(!$data = $this->db->find($table, $field, $where, $order, $limit)) {
            $data = '';
        }
        \FeedBack\Inc\View::output($data);
    }
}
