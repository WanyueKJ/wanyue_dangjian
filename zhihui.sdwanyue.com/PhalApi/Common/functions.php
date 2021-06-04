<?php



    /* 校验密码 */
    function checkPass($pass){
        /* 必须包含字母、数字 */
        $preg='/^(?=.*[A-Za-z])(?=.*[0-9])[a-zA-Z0-9~!@&%#_]{6,20}$/';
        $isok=preg_match($preg,$pass);
        if($isok){
            return 1;
        }
        return 0;
    }

	/* 密码检查 */
	function passcheck($user_pass) {
		$num = preg_match("/^[a-zA-Z]+$/",$user_pass);
		$word = preg_match("/^[0-9]+$/",$user_pass);
		$check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
		if($num || $word ){
			return 2;
		}else if(!$check){
			return 0;
		}		
		return 1;
	}
	/* 检验手机号 */
	function checkMobile($mobile){
		$ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}else{
			return 0;
		}
	}
	/* 随机数 */
	function random($length = 6 , $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}

	

	/* 发送验证码 */
    /* 腾讯云短信验证码 */
	function sendCode($mobile,$code){
		$rs = array('code' => 0, 'msg' => '发送成功', 'info' => array());

		$config = getConfigPub();
        
        if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;
        }

		require_once API_ROOT.'/../sdk/tencentcloud/TCloudAutoLoader.php';

		$tx_api_secretid= $config['tx_api_secretid'];
		$tx_api_secretkey= $config['tx_api_secretkey'];
		$tx_sms_sdkappid= $config['tx_sms_sdkappid'];
		$tx_sms_sign= $config['tx_sms_sign'];
		$tx_sms_tempid= $config['tx_sms_tempid'];

		$mobile='+86'.$mobile;
		$phonenums=[$mobile];

		$params=[$code];

		try {
			/* 必要步骤：
			* 实例化一个认证对象，入参需要传入腾讯云账户密钥对secretId，secretKey。
			* 这里采用的是从环境变量读取的方式，需要在环境变量中先设置这两个值。
			* 你也可以直接在代码中写死密钥对，但是小心不要将代码复制、上传或者分享给他人，
			* 以免泄露密钥对危及你的财产安全。
			* CAM密匙查询: https://console.cloud.tencent.com/cam/capi*/

			$cred = new \TencentCloud\Common\Credential($tx_api_secretid, $tx_api_secretkey);


			// 实例化一个http选项，可选的，没有特殊需求可以跳过
			// $httpProfile = new \TencentCloud\Common\Profile\HttpProfile();
			// $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
			// $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
			// $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)

			// 实例化一个client选项，可选的，没有特殊需求可以跳过
			// $clientProfile = new \TencentCloud\Common\Profile\ClientProfile();
			// $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
			// $clientProfile->setHttpProfile($httpProfile);

			// 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
			// $client = new SmsClient($cred, "ap-shanghai", $clientProfile);
			$client = new \TencentCloud\Sms\V20190711\SmsClient($cred, "ap-shanghai");

			// 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
			$req = new \TencentCloud\Sms\V20190711\Models\SendSmsRequest();

			/* 填充请求参数,这里request对象的成员变量即对应接口的入参
			* 你可以通过官网接口文档或跳转到request对象的定义处查看请求参数的定义
			* 基本类型的设置:
			* 帮助链接：
			* 短信控制台: https://console.cloud.tencent.com/sms/smslist
			* sms helper: https://cloud.tencent.com/document/product/382/3773 */
			
			/* 短信应用ID: 短信SdkAppid在 [短信控制台] 添加应用后生成的实际SdkAppid，示例如1400006666 */
			$req->SmsSdkAppid = $tx_sms_sdkappid;
			/* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
			$req->Sign = $tx_sms_sign;
			/* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
			$req->ExtendCode = "0";
			/* 下发手机号码，采用 e.164 标准，+[国家或地区码][手机号]
			* 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
			$req->PhoneNumberSet = $phonenums;
			/* 国际/港澳台短信 senderid: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
			$req->SenderId = "";
			/* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
			$req->SessionContext = "";
			/* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
			$req->TemplateID = $tx_sms_tempid;
			/* 模板参数: 若无模板参数，则设置为空*/
			$req->TemplateParamSet = $params;

			// 通过client对象调用DescribeInstances方法发起请求。注意请求方法名与请求对象是对应的
			// 返回的resp是一个DescribeInstancesResponse类的实例，与请求对象对应
			$resp = $client->SendSms($req);
			
			// 输出json格式的字符串回包
			$res=$resp->toJsonString();

			//{"SendStatusSet":[{"SerialNo":"2019:6180501101329406318","PhoneNumber":"+8613053838131","Fee":1,"SessionContext":"","Code":"Ok","Message":"send success"}],"RequestId":"69a550c3-74e9-4be7-b5bb-5856b7c36daa"}

			$res_a=json_decode($res,true);

			$nums=0;
			$nums_e=0;
			foreach($res_a['SendStatusSet'] as $k=>$v){
				if($v['Code']!='Ok'){
					$nums_e++;
				}
				$nums++;
			}
			// print_r($res);

			// file_put_contents(API_ROOT.'/../data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 res:'.json_encode($res)."\r\n",FILE_APPEND);

			if($nums==$nums_e){
				$rs['code']=1002;
				//$rs['msg']=$gets['SubmitResult']['msg'];
				$rs['msg']="发送失败";
				return $rs;
			}

			// 也可以取出单个值。
			// 你可以通过官网接口文档或跳转到response对象的定义处查看返回字段的定义
			// print_r($resp->TotalCount);
		}
		catch(\TencentCloud\Common\Exception\TencentCloudSDKException $e) {
			//echo $e;
			//file_put_contents(API_ROOT.'/../data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 e:'.json_encode($e)."\r\n",FILE_APPEND);

			$rs['code']=1002;
			//$rs['msg']=$gets['SubmitResult']['msg'];
			$rs['msg']="发送失败";
			return $rs;
		}

        $content=$code;
		return $rs;
	}
    

    
    
	/* 密码加密 */
	function setPass($pass){
		$authcode='rCt52pF2cnnKNB3Hkp';
		$pass="###".md5(md5($authcode.$pass));
		return $pass;
	}	
    /* 去除NULL 判断空处理 主要针对字符串类型*/
	function checkNull($checkstr){
        $checkstr=trim($checkstr);
		$checkstr=urldecode($checkstr);
        if(get_magic_quotes_gpc()==0){
			$checkstr=addslashes($checkstr);
		}
		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		return $str;	
	}
	
	/* 公共配置 */
	function getConfigPub() {
		$key='getConfigPub_dj';
//		$config=getcaches($key);
//		$config=false;
//		if(!$config){
			$config= DI()->notorm->option
					->select('option_value')
					->where("option_name='site_info'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
//			setcaches($key,$config);
//		}

		return 	$config;
	}		
	
	
	
	/**
	 * 返回带协议的域名
	 */
	function get_host(){
		$config=getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	function get_upload_path($file){
        if($file==''){
            return $file;
        }
		if(strpos($file,"http")===0){
			return html_entity_decode($file);
		}else if(strpos($file,"/")===0){
			$filepath= get_host().$file;
			return html_entity_decode($filepath);
		}else{
			$space_host= DI()->config->get('app.Qiniu.space_host');
			
			$filepath=$space_host."/".$file;
			return html_entity_decode($filepath);
		}
	}

	/* 判断token */
	function checkToken($uid,$token) {

        $userinfo=DI()->notorm->users_token
						->select('token,expire_time')
						->where('user_id = ?', $uid)
						->fetchOne();	

		if($userinfo['token']!=$token || $userinfo['expire_time']<time()){
			return 700;				
		}else{
			return 	0;				
		} 		
	}	
	
	/* 用户基本信息 */
	function getUserInfo($uid,$type=0) {
		$info=getcaches("userinfo_".$uid);
		if(!$info){
			$info=DI()->notorm->users
					->select('id,user_nickname,avatar,sex,signature,birthday,user_status,partybranch,partypost,party_money,score')
					->where('id=? and user_type="2"',$uid)
					->fetchOne();	
			if($info){
				$info['avatar']=get_upload_path($info['avatar']);
			}else if($type==1){
                return 	$info;
                
            }else{
                $info['id']=$uid;
                $info['user_nicename']='用户不存在';
                $info['avatar']=get_upload_path('/default.jpg');

                $info['sex']='0';
                $info['signature']='';


                $info['birthday']='';
                $info['partybranch']='';
                $info['partypost']='';
  
            }
            if($info){
                setcaches("userinfo_".$uid,$info,10);
            }
			
		}

		return 	$info;		
	}
	function count_zan($id,$type){
		return DI()->notorm->zan_collect->where('newsid = ? and type = ? and zan = 1',$id,$type)->count();
	}




    /* 检测用户是否存在 */
    function checkUser($where){
        if($where==''){
            return 0;
        }

        $isexist=DI()->notorm->users->where($where)->fetchOne();
        
        if($isexist){
            return 1;
        }
        
        return 0;
    }

    /* 校验签名 */
    function checkSign($data,$sign){
        $key=DI()->config->get('app.sign_key');
        $str='';
        ksort($data);
        foreach($data as $k=>$v){
            $str.=$k.'='.$v.'&';
        }
        $str.=$key;
        $newsign=md5($str);
        
        if($sign==$newsign){
            return 1;
        }
        return 0;
    }

    

	function checkcode($mobile,$mac){
		$key = 'getcode_'.$mac;
		$info = getcache($key);
		if($info !=''){
			if($info['mobile'] == $mobile && $info['time'] > time() -120 ){//两分钟内重新发送
				return false;
			}
		}
		return true;
	}
	function setcode($data,$mac){
		$key = 'getcode_'.$mac;
		$infojson = setcaches($key,$data,300);
		return true;
	}
	function checkcoderight($mac,$mobile,$code){
		$key = 'getcode_'.$mac;
		$info = getcache($key);

		if($info ==''){
			return 0;
		}elseif($info['mobile'] !=$mobile){
			return 1;
		}elseif($info['code'] !=$code){
			return 2;
		}
		return 3;		
	}	
	function delcode($mac){
		$key = 'getcode_'.$mac;
		delcache($key);
	}
	
	
	function checkforgetcode($mobile,$mac){
		$key = 'getforgetcode_'.$mac;
		$info = getcache($key);
		if($info !=''){
			if($info['mobile'] == $mobile && $info['time'] > time() -120 ){//两分钟内重新发送
				return false;
			}
		}
		return true;
	}
	function setforgetcode($data,$mac){
		$key = 'getforgetcode_'.$mac;
		$infojson = setcaches($key,$data,300);
		return true;
	}
	function checkforgetcoderight($mac,$mobile,$code){
		$key = 'getforgetcode_'.$mac;
		$info = getcache($key);
		if($info ==''){
			return 0;
		}elseif($info['mobile'] !=$mobile){
			return 1;
		}elseif($info['code'] !=$code){
			return 2;
		}
		return 3;		
	}	
	function delforgetcode($mac){
		$key = 'getforgetcode_'.$mac;
		delcache($key);
	}	
	function getpartylist(){
		$patrtypost = DI()->notorm->partypost->order('list_order asc')->fetchAll();
		$partybranch =DI()->notorm->partybranch->order('list_order asc')->fetchAll();
		$postkey=[];
		$postvalue=[];
		$branchkey=[''];
		$branchvalue=['所有党支部'];

		foreach($patrtypost as $k => $v){
			$postkey[]=$v['id'];
			$postvalue[]=$v['name'];
		}
		foreach($partybranch as $k => $v){
			$branchkey[]=$v['id'];
			$branchvalue[]=$v['name'];
		}	
		return $data = [
			'partypost'=>[
				'key'=>$postkey,
				'value'=>$postvalue,
			],
			'partybranch'=>[
				'key'=>$branchkey,
				'value'=>$branchvalue,
			],			
		];
	}
	function getpost($id){
		$patrtypost = DI()->notorm->partypost->where("id =?",$id)->fetchOne();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}	
	function getbranch($id){
		$patrtypost = DI()->notorm->partybranch->where("id =?",$id)->fetchOne();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}	
	function getanswerarr($id){
		$list= DI()->notorm->ask->where("listid =?",$id)->fetchAll();
		$data=[];
		foreach($list as $k =>$v){
			$data[$v['id']]=$v;
		}
		return $data;
	}

    //	时间戳转为时分秒
    function time2string($second){
        $second = $second%(3600*24);
        $hour = floor($second/3600);
        $second = $second%3600;
        $minute = floor($second/60);
        $second = $second%60;
        // 不用管怎么实现的，能用就ok
        return $hour.':'.$minute.':'.$second;
    }




