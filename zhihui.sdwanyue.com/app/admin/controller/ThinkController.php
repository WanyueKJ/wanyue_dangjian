<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\ThinkModel;
use cmf\controller\AdminBaseController;
use think\Db;

class ThinkController extends AdminBaseController
{

	private function getstatus(){
		return ['0'=>'未审核','1'=>'已审核'];
	}
	private function getpost($id){
		$patrtypost =  DB::name('partypost')->where("id",$id)->find();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}	
	private function getbranch($id){
		$patrtypost = DB::name('partybranch')->where("id",$id)->find();
		return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
	}	
	private function getuser($uid){
		$patrtypost = DB::name('user')->field('partybranch,partypost,user_nickname')->where("id",$uid)->find();
	
		return $patrtypost;
	}		
    public function index()
    {
        $map    = [];
		$data   = $this->request->param();
        $status = isset($data['status']) && $data['status']!=='' ? $data['status'] : '';
        if ($status != '') {
            $map[] = ['status', '=', $status];
        }
		$list = DB::name('think')->where($map)->order("list_order asc")->paginate(10, false, ['query' => input()]);
        $list->each(function ($v, $k) {
				
			$userinfo = $this->getuser($v['uid']);
			
			$v['post'] = $this->getpost($userinfo['partypost']);
			$v['branch']= $this->getbranch($userinfo['partybranch']);
			$v['user_nickname'] = $userinfo['user_nickname'];
			$v['thumbarr'] = json_decode($v['thumbs'],true);
            return $v;
        });		
		$page = $list->render();
		$this->assign("page", $page);
        $this->assign('list', $list);
        $this->assign('status', $this->getstatus());
        return $this->fetch();
    }



    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = DB::name('think')->where('id',$id)->find();
		$result['thumbarr'] = json_decode($result['thumbs'],true);
		$userinfo = $this->getuser($result['uid']);
		$result['user_nickname'] = $userinfo['user_nickname'];
		
        $this->assign('result', $result);
		$this->assign('status', $this->getstatus());
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
			$data =  $this->request->param();
            $status=$data['status'];

			$check=  DB::name('think')->where('id',$data['id'])->find();

			if($check['status'] == $status){
				$this->error('审核状态未改变');
			}
			if(!$check){
				$this->error('文章已被删除');
			}
			$msg=[
				'uid'=>$check['uid'],
				'conotent'=>'',
				'addtime'=>time()
			];			
			if($status=='3'){
				$msg['conotent'] ='你发表的《'.$check['title'].'》审核未通过，已被删除~';
				DB::name('think')->where('id',$data['id'])->delete();
				DB::name('msg')->insert($msg);
				$this->success("保存成功！", url("think/index"));
			}
			if($status=='1'){
				$msg['conotent'] ='你发表的《'.$check['title'].'》审核通过~';
				DB::name('think')->where('id',$data['id'])->update(['status'=>1]);
				DB::name('msg')->insert($msg);
				$this->success("保存成功！", url("think/index"));
			}
			if($status=='0'){
				$msg['conotent'] ='你发表的《'.$check['title'].'》标记为未通过~';
				DB::name('think')->where('id',$data['id'])->update(['status'=>0]);
				DB::name('msg')->insert($msg);
				$this->success("保存成功！", url("think/index"));
			}			

             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
			$check=  DB::name('think')->where('id',$id)->find();
			if(!$check){
				$this->error('文章已被删除');
			}			
			if(DB::name('think')->where('id',$id)->delete()){
				
				$msg=[
					'uid'=>$check['uid'],
					'conotent'=>'你发表的《'.$check['title'].'》已被删除~',
					'addtime'=>time()
				];	
				DB::name('msg')->insert($msg);
				$this->success("删除成功！", url("think/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $NewspageModel = new  ThinkModel();
        parent::listOrders($NewspageModel);
        $this->success("排序更新成功！");
    }	
}
