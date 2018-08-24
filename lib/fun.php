<?php
/**
 * 数据库连接初始化
 * @param $host
 * @param $username
 * @param $password
 * @param $database
 * @return bool|object mysqli
 */
function mysqlInit($host, $username, $password, $database, $charset = "utf8")
{
    // 面向对象连接数据库
    $conn = new mysqli("$host", "$username", "$password");
    if ($conn->connect_error) {
        return false;
    }
    // echo "连接成功";
    // 选择数据库
    $conn->select_db("$database");
    // 设置字符集
    $conn->set_charset("$charset");
    // 返回的是一个对象
    return $conn;
}

/**
 * 用户密码加密
 * @param $password
 * @return bool|string
 */
function pwdEncryption($password)
{
    if (!$password) {
        return false;
    }

    return md5(md5($password)."password");
}