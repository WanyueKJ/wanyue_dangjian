<?php
/**
 * 上传
 */
class Api_Update extends PhalApi_Api {  

	public function getRules() {
		return array(

			'updateAvatar' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'file' => array('name' => 'file','type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 30, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
			),			
		);
	}
	
	/**
	 * 图片上传 (七牛)
	 * @desc 图片上传
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].url 上传地址
	 * @return string msg 提示信息
	 */
	public function updateAvatar() {
		$rs = array('code' => 0 , 'msg' => '上传成功', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

 		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		if (!isset($_FILES['file'])) {
			$rs['code'] = 1001;
			$rs['msg'] = T('miss upload file');
			return $rs;
		}
		if ($_FILES["file"]["error"] > 0) {
			$rs['code'] = 1002;
			$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
			DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
			return $rs;
		}
		$uptype=DI()->config->get('app.uptype');
		if($uptype==1){
			//七牛
			$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);

		}

        if ($uptype == 0) {
            $file = $_FILES["file"];
            //本地
            if ($file["error"] != 0) {
                $rs['code'] = 1004;
                $rs['msg'] = '上传失败，请稍候重试';
                return $rs;
            }

            // 成功
            $typeArr = explode("/", $file["type"]);
            if($typeArr[0]== "image"){

                $imgType = array("png","jpg","jpeg");
                if(in_array($typeArr[1], $imgType)){ // 图片格式是数组中的一个

                    $imgname1 = time() . rand(1,99) . "." . $typeArr[1];
                    $imgname2  = API_ROOT  . "../../public/upload/" . $imgname1;
                    $bol = move_uploaded_file($file["tmp_name"], $imgname2);
                    if($bol){
                        $url = get_upload_path($imgname1);
                    }
                };

            }

        }

		@unlink($_FILES['file']['tmp_name']);
        if(!$url){
            $rs['code'] = 1003;
			$rs['msg'] = '上传失败，请稍候重试';
			return $rs;
        }
		$rs['info'][0]['url'] = $url;
		return $rs;

	}
	
	
} 
