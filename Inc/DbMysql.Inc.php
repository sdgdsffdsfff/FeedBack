<?php

/*
 * FeedBack [A journey always starts with the first step]
 *
 * @copyright Copyright (C) 2013 wine.cn All rights reserved.
 * @license http://www.wine.cn/licenses.txt
 */

//----------------------------------------------------------------

/**
 * MySQL数据库操作类
 *
 * @author   <mengfk@eswine.com>
 * @since    1.0
 */

namespace FeedBack\Inc;

class DbMysql {

    var $dblink;

    var $config;

    public function __construct($config) {
        $this->config = $config;
        $this->connect();
    }

    private function connect() {
        if(!$this->dblink || !mysql_ping($this->dblink)) {
            $this->dblink = mysql_connect($this->config['host'].':'.$this->config['port'], $this->config['user'], $this->config['pass']);
            if(!$this->dblink) {
                \FeedBack\Inc\Error::showError('Could not connect: ' . mysql_error(), 2001);
            }
            if(!empty($this->config['dbname'])) {
                if(!mysql_select_db($this->config['dbname'], $this->dblink)) {
                    \FeedBack\Inc\Error::showError('Mysql Database Select Error: ' . mysql_error(), 2002);
                }
            }
            $version = mysql_get_server_info($this->dblink);
            if($version >= '4.1' && isset($this->config['charset'])) {
                mysql_query("SET NAMES '". $this->config['charset'] ."'", $this->dblink);
            }
            if($version >'5.0.1'){
                mysql_query("SET sql_mode=''",$this->dblink);
            }
        }
    }

    public function query($sql) {
        $result = array();
        if($query = mysql_query($sql, $this->dblink)) {
            if(mysql_num_rows($query) >0) {
                while($row = mysql_fetch_assoc($query)){
                    $result[] = $row;
                }
            }
            mysql_free_result($query);
        } else {
            \FeedBack\Inc\Error::showError("Mysql Query Error:".mysql_error($this->dblink), 2003);
        }   
        return $result;
    }

    public function execute($sql) {
        $result = mysql_query($sql, $this->dblink);
        if($result == false) {
            \FeedBack\Inc\Error::showError("Mysql Execute Error:".mysql_error($this->dblink), 2004);
        } else {
            $numrows = mysql_affected_rows($this->dblink);
            $last_insid = mysql_insert_id($this->dblink);
            return $last_insid ? $last_insid : $numrows;
        }
    }

    public function queryFirst($sql) {
        $result = $this->query($sql);
        return isset($result[0]) ? $result[0]: array();
    }

    public function insert($data, $table) {
        $field = $value = array();
        foreach($data as $k => $v) {
            $field[] = '`'.$k.'`';
            $value[] = "'".mysql_real_escape_string($v, $this->dblink)."'";
        }
        $sql = "INSERT INTO `{$table}`(".implode(",", $field).") VALUES(".implode(',', $value).")";
        return $this->execute($sql);
    }

    public function find($table, $field, $where, $order, $limit) {
        $condition = $dot = '';
        if(is_array($where)) {
            foreach($where as $k => $v) {
                $condition .= $dot . "`{$k}` = '". mysql_real_escape_string($v, $this->dblink) ."'";
                $dot = ' AND ';
            }
        } else {
            $condition = $where;
        }
        $sql = "SELECT {$field} FROM `{$table}` WHERE {$condition} ORDER BY {$order} LIMIT {$limit}";
        return $this->query($sql);
    }
}
