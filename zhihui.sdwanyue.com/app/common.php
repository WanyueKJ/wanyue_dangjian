<?php

use think\Db;
use cmf\lib\Storage;

// 应用公共文件
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once dirname(__FILE__) . '/redis.php';


/**
 * 转化数据库保存图片的文件路径，为可以访问的url
 * @param string $file  文件路径，数据存储的文件相对路径
 * @param string $style 图片样式,支持各大云存储
 * @return string 图片链接
 */
function get_upload_path($file, $style = '')
{
	if (empty($file)) {
		return '';
	}

	if (strpos($file, "http") === 0) {
		return $file;
	}
	else if (strpos($file, "/") === 0) {
		return cmf_get_domain() . $file;
	}
	else {

		$storage = Storage::instance();
		return $storage->getImageUrl($file, $style);
	}
}
/* 判断token */
function checkToken($uid, $token)
{
	if ($uid < 1 || $token == '') {
		return 700;
	}
	$userinfo = Db::name('user_token')
		->field('token,expire_time')
		->where("user_id = {$uid} ")
		->find();


	if (!$userinfo || $userinfo['token'] != $token || $userinfo['expire_time'] < time()) {
		return 700;
	}

	return 0;

}



/* 密码加密 */
function setPass($pass){
    $authcode='rCt52pF2cnnKNB3Hkp';
    $pass="###".md5(md5($authcode.$pass));
    return $pass;
}
