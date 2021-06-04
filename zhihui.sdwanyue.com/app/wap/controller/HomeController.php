<?php
/**
 * h5
 */
 //
namespace app\wap\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class HomeController extends HomebaseController {
	function product(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('product_page')->where('id',$id)->find();

		//增加观看次数
		DB::name('product_page')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);

		$config = Db::name('option')->field('option_value')->where(['option_name'=>'site_info'])->find();
		$config = json_decode($config['option_value'],true);
		$this->assign('title','产品介绍');

		return $this->fetch('shouce'); 
	}	
	function news(){

		$data = $this->request->param();
		$id = $data['id'] ?? 0;
		$uid = $data['uid'] ?? '0';
		$token = $data['token'] ?? '';
		
		$info = DB::name('news_page')->where('id',$id)->find();
		//增加观看次数
		DB::name('news_page')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$countping = DB::name('comments')->where([['nid','=',$id],['type','=',0],['status','=',1]])->count();
		$countzan = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',0],['zan','=',1]])->count();
		$pinginfo = DB::name('comments')->where([['nid','=',$id],['type','=',0],['status','=',1]])->order('addtime desc')->select()->toArray();
		foreach($pinginfo as $k =>$v){
			$v['userinfo'] = $this->getuserinfo($v['uid']);
			$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			$pinginfo[$k]=$v;
		}
		$zinfo = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',0],['uid','=',$uid]])->find();

        //哪些用户看过，这个用户点击过多少次
        $viewNews = db('user_viewnews')->where(['uid' => $uid, 'news_id' => $id])->find();
        if ($viewNews) {
            db('user_viewnews')->where(['uid' => $uid, 'news_id' => $id])->setInc('views', 1);
        } else {
            $user_insert = [
                'uid' => $uid,
                'news_id' => $id,
                'addtime' => time(),
                'views' => 1,
            ];

            db('user_viewnews')->insert($user_insert);
        }

		//增加浏览历史
		if($uid>0){
			$this->addhistory($uid,$id);
		}

		$this->assign('info',$info);
		$this->assign('zinfo',$zinfo);
		$this->assign('type',0);
		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$this->assign('pinginfo',$pinginfo);
		$this->assign('countping',$countping);
		$this->assign('countzan',$countzan);

		$config = Db::name('option')->field('option_value')->where(['option_name'=>'site_info'])->find();
		$config = json_decode($config['option_value'],true);
		$this->assign('small_switch',$config['small_switch']);

		return $this->fetch(); 
	}

    private function addhistory($uid,$id){
		$check = DB::name('history')->where([['uid','=',$uid],['newsid','=',$id]])->find();
		if($check){
			DB::name('history')->where([['uid','=',$uid],['newsid','=',$id]])->update(['addtime'=>time()]);
		}else{
			$data=[
				'uid'=>$uid,
				'newsid'=>$id,
				'addtime'=>time(),
			];
			DB::name('history')->insert($data);
		}
		return 1;
	}
	function think(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
  		// if(checkToken($uid,$token) ==700){
		// 	$this->assign('reason','您的登录信息已失效');
		// 	return $this->fetch('error');
		// 	exit;
		// } 		
		
		$info = DB::name('think')->where('id',$id)->find();
		//增加观看次数
		DB::name('think')->where('id',$id)->setInc('view',1);
		$userinfo =$this->getuserinfo($info['uid']);
		$thumbs = json_decode($info['thumbs'],true);
		$info['userinfo']=$userinfo;
		$countping = DB::name('comments')->where([['nid','=',$id],['type','=',1],['status','=',1]])->count();
		$countzan = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',1],['zan','=',1]])->count();
		$pinginfo = DB::name('comments')->where([['nid','=',$id],['type','=',1],['status','=',1]])->order('addtime desc')->select()->toArray();
		foreach($pinginfo as $k =>$v){
			$v['userinfo'] = $this->getuserinfo($v['uid']);
			$v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			$pinginfo[$k]=$v;
		}
		$zinfo = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',1],['uid','=',$uid]])->find();
		
		//增加浏览数量
		DB::name('think')->where('id',$id)->setInc('count');
		$this->assign('info',$info);
		$this->assign('thumbs',$thumbs);
		$this->assign('zinfo',$zinfo);
		$this->assign('type',1);
		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$this->assign('pinginfo',$pinginfo);
		$this->assign('countping',$countping);
		$this->assign('countzan',$countzan);

		$config = Db::name('option')->field('option_value')->where(['option_name'=>'site_info'])->find();
		$config = json_decode($config['option_value'],true);
		$this->assign('small_switch',$config['small_switch']);

		return $this->fetch(); 
	}	
	function shouce(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('shouce')->where('id',$id)->find();

		//增加观看次数
		DB::name('shouce')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);

		$config = Db::name('option')->field('option_value')->where(['option_name'=>'site_info'])->find();
		$config = json_decode($config['option_value'],true);
		if($config['small_switch']==0){
			$this->assign('title','党员手册');
		}else{
			$this->assign('title','手册');
		}


		return $this->fetch('shouce'); 
	}	

	function active(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('active')->where('id',$id)->find();

		//增加观看次数
		DB::name('active')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$this->assign('title','线上活动');

		return $this->fetch('shouce'); 
	}	
	function sanhuiyike(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('sanhuiyike')->where('id',$id)->find();
		//增加观看次数
		DB::name('sanhuiyike')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$this->assign('title','三会一课');

		return $this->fetch('shouce'); 
	}
	function window(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('window')->where('id',$id)->find();
		//增加观看次数
		DB::name('window')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$config = Db::name('option')->field('option_value')->where(['option_name'=>'site_info'])->find();
		$config = json_decode($config['option_value'],true);
		if($config['small_switch']==0){
			$this->assign('title','党建参阅');
		}else{
			$this->assign('title','学习之窗');
		}

		return $this->fetch('shouce'); 
	}


    //根据党史获取文章
    function knowledge(){
        $data = $this->request->param();
        $id = $data['id'];
        $uid = $data['uid'];
        $token = $data['token'];

        $info = DB::name('knowledge')->where('id',$id)->find();
        //增加观看次数
        DB::name('knowledge')->where('id',$id)->setInc('view',1);
        $info['content'] = htmlspecialchars_decode($info['content']);
        $this->assign('info',$info);

        $this->assign('uid',$uid);
        $this->assign('token',$token);
        $this->assign('title','党史知识');

        return $this->fetch('shouce');
    }

	function notice(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$token = $data['token'];
/*  		if(checkToken($uid,$token) ==700){
			$this->assign('reason','您的登录信息已失效');
			return $this->fetch('error');
			exit;
		}	 */	
		
		$info = DB::name('notice')->where('id',$id)->find();
		//增加观看次数
		DB::name('notice')->where('id',$id)->setInc('view',1);
		$info['content'] = htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);

		$this->assign('uid',$uid);
		$this->assign('token',$token);
		$this->assign('title','通知公告');

		return $this->fetch('shouce'); 
	}	
	private function getuserinfo($uid){
		$userinfo = DB::name('user')->field('user_nickname,avatar,partypost,partybranch')->where('id',$uid)->find();
		$userinfo['post'] = $this->getpost($userinfo['partypost']); 
		$userinfo['avatar'] = get_upload_path($userinfo['avatar']); 
		$userinfo['branch'] = $this->getbranch($userinfo['partybranch']);
		return 	$userinfo;	
	}
	private function getpost($id){
		$patrtypost =  DB::name('partypost')->where("id",$id)->find();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}	
	private function getbranch($id){
		$patrtypost = DB::name('partybranch')->where("id",$id)->find();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}

    //	评论
	function msgpost(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$type = $data['type'];
		$token = $data['token'];
		$content = $data['content'];
		if(checkToken($uid,$token) ==700){
			return 700;
			//$this->error('您的登录信息已失效');
		}
		$data = [
			'uid'=>$uid,
			'nid'=>$id,
			'type'=>$type,
			'content'=>$content,
			'addtime'=>time(),
			'status'=>0
		];
		$check=DB::name('comments')->insert($data);
		if($check){

			$this->success('提交成功，正在审核');
		}
		$this->error('操作失败，请稍后重试');
	}	
	function zan(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$type = $data['type'];
		$token = $data['token'];
 		if(checkToken($uid,$token) ==700){
			return 700;
			//$this->error('您的登录信息已失效');
		}
		
		$zinfo = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',$type],['uid','=',$uid]])->find();
		if(!$zinfo){
			$data = [
				'uid'=>$uid,
				'newsid'=>$id,
				'type'=>$type,
				'zan'=>1,
				'zantime'=>time(),
			];
			$post=DB::name('zan_collect')->insert($data);
			if($post){
				$this->success('点赞成功');
			}
			$this->error('操作失败，请稍后重试');			
		}
		$data = [
			'zan'=>1,
			'zantime'=>time(),
		];		
		$msg = '点赞成功';
		if($zinfo['zan']==1){
			$data['zan'] = 0;
			$msg = '取消成功';
		}		
		$post=DB::name('zan_collect')->where([['newsid','=',$id],['type','=',$type],['uid','=',$uid]])->update($data);
		if($post){
			$this->success($msg);
		}
		$this->error('操作失败，请稍后重试');	

	}	
	function collect(){       
		$data = $this->request->param();
		$id = $data['id'];
		$uid = $data['uid'];
		$type = $data['type'];
		$token = $data['token'];
		if(checkToken($uid,$token) ==700){
			return 700;
			//$this->error('您的登录信息已失效');
		}
		
		$zinfo = DB::name('zan_collect')->where([['newsid','=',$id],['type','=',$type],['uid','=',$uid]])->find();
		if(!$zinfo){
			$data = [
				'uid'=>$uid,
				'newsid'=>$id,
				'type'=>$type,
				'collect'=>1,
				'collecttime'=>time(),
			];
			$post=DB::name('zan_collect')->insert($data);
			if($post){
				$this->success('收藏成功');
			}
			$this->error('操作失败，请稍后重试');			
		}
		$data = [
			'collect'=>1,
			'collecttime'=>time(),
		];		
		$msg = '收藏成功';
		if($zinfo['collect']==1){
			$data['collect'] = 0;
			$msg = '取消成功';
		}		
		$post=DB::name('zan_collect')->where([['newsid','=',$id],['type','=',$type],['uid','=',$uid]])->update($data);
		if($post){
			$this->success($msg);
		}
		$this->error('操作失败，请稍后重试');	

	}
	function countping(){       
		$data = $this->request->param();
		$id = $data['id'];
		$type = $data['type'];
		$pinginfo = DB::name('comments')->where([['nid','=',$id],['type','=',$type]])->order('addtime desc')->select()->toArray();
		foreach($pinginfo as $k =>$v){
			$v['userinfo'] = $this->getuserinfo($v['uid']);
			$v['addtime'] = date('Y-m-d h:i:s',$v['addtime']);
			$pinginfo[$k]=$v;
		}
		$this->success($pinginfo);
	}	
	function countzan(){       
		$data = $this->request->param();
		$id = $data['id'];
		$type = $data['type'];
		$pinginfo =  DB::name('zan_collect')->field('uid,zantime')->where([['newsid','=',$id],['type','=',$type],['zan','=',1]])->select()->toArray();
		foreach($pinginfo as $k =>$v){
			$v['userinfo'] = $this->getuserinfo($v['uid']);
			$v['addtime'] = date('Y-m-d h:i:s',$v['zantime']);
			$pinginfo[$k]=$v;
		}
		$this->success($pinginfo);
	}		

	//要闻速览
	function scanning(){
        $data = $this->request->param();
        $id = $data['id'];
        $uid = $data['uid'] ?? '';
        $info = DB::name('scanning')->where('id',$id)->find();
        //增加观看次数
        DB::name('scanning')->where('id',$id)->setInc('view',1);
        $info['content'] = htmlspecialchars_decode($info['content']);

        //增加浏览历史
        if($uid>0){
            $this->addhistory($uid,$id);
        }

        $this->assign('info',$info);
        $this->assign('title','推荐文章');

        return $this->fetch('shouce');
    }


}
