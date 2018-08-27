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
function pwdEncrypt($password)
{
    if (!$password) {
        return false;
    }

    return md5(md5($password) . "password");
}

/**
 * 信息页提示信息跳转
 * @param int $type
 * @param null $msg
 * @param null $url
 */
function showMsg($type, $msg = null, $url = null)
{
    $msg = urlencode($msg);
    $toUrl = "Location:msg.php?type={$type}";
    $toUrl .= $msg ? "&msg={$msg}" : "";
    $toUrl .= $url ? "&url={$url}" : "";

    header($toUrl);
    exit;
}

/**
 * 图片上传
 * @param $file
 * @return string
 */
function imgUpload($file)
{
    $now = $_SERVER['REQUEST_TIME'];

    // 检查上传文件是否合法
    if (!is_uploaded_file($file['tmp_name'])) {
        showMsg(0, "请上传合法的文件");
    }

    // 上传路径
    $uploadPath = "./static/file/";
    // 访问URL
    $uploadUrl = "/static/file/";
    // 上传文件夹
    $fileDir = date("Y/md/", $now);

    // 检查上传目录是否存在
    if (!is_dir($uploadPath . $fileDir)) {
        mkdir($uploadPath . $fileDir, 0755, true);
    }

    // 获取上传文件的扩展名
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // 上传图像重命名（用于去重）
    // uniqid()函数在微秒级生成一个随机数
    $img = uniqid() . mt_rand(1000, 9999) . "." . $ext;

    // 文件路径
    $imgPath = $uploadPath . $fileDir . $img;
    // 文件URL路径
    $imgUrl = "http://localhost:63342/mall" . $uploadUrl . $fileDir . $img;

    // 文件上传验证
    $type = $file['type'];
    if (!in_array($type, array("image/png", "image/jpeg", "image/gif"))) {
        showMsg(0, "缩略图文件不合法，请重新上传");
    }
    // 文件上传
    if (!move_uploaded_file($file['tmp_name'], $imgPath)) {
        showMsg("服务器繁忙，请稍候再试");
    }
    return $imgUrl;
}