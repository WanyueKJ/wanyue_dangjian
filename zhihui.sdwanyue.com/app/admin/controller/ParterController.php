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

use app\admin\model\ParterModel;
use cmf\controller\AdminBaseController;
use think\Db;

class ParterController extends AdminBaseController
{

	private function getpartybranch(){
		return DB::name('partybranch')->order("list_order asc")->select();
	}
	private function getpartypost(){
		return DB::name('partypost')->order("list_order asc")->select();
	}	
    public function index()
    {
        $map    = [];
		$data   = $this->request->param();
        $partybranch =isset($data['partybranch']) && $data['partybranch'] ? $data['partybranch'] : '';
        if ($partybranch != '') {
            $map[] = ['partybranch', '=', $partybranch];
        }
        $partypost =isset($data['partypost']) && $data['partypost'] ? $data['partypost'] : '';
        if ($partypost != '') {
            $map[] = ['partypost', '=', $partypost];
        }	
		$list = DB::name('parter')->where($map)->order("list_order asc")->paginate(10, false, ['query' => input()]);
        $list->each(function ($v, $k) {
			$v['post'] = DB::name('partypost')->where('id',$v['partypost'])->value('name');
			$v['branch'] = DB::name('partybranch')->where('id',$v['partybranch'])->value('name');
            return $v;
        });		
		$page = $list->render();
		$this->assign("page", $page);
        $this->assign('list', $list);
        $this->assign('partybranch', $this->getpartybranch());
        $this->assign('partypost', $this->getpartypost());


        return $this->fetch();
    }


    public function add()
    {
        $this->assign('partybranch', $this->getpartybranch());
        $this->assign('partypost', $this->getpartypost());
        return $this->fetch();
    }


    public function addPost()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->param();
            $validate         = $this->validate($data, 'Parter');
            if ($validate !== true) {
                $this->error($validate);
            }
            $result         = DB::name('parter')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("parter/index"));
        }
    }

    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = DB::name('parter')->where('id',$id)->find();
        $this->assign('result', $result);
        $this->assign('partybranch', $this->getpartybranch());
        $this->assign('partypost', $this->getpartypost());
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $validate         = $this->validate($data, 'Parter');
            if ($validate !== true) {
                $this->error($validate);
            }
            $slidePostModel = DB::name('parter')->where('id',$data['id'])->update($data);
			
			if($slidePostModel){
				$this->success("保存成功！", url("parter/index"));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
			if(DB::name('parter')->where('id',$id)->delete()){
				$this->success("删除成功！", url("parter/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $parterpageModel = new  ParterModel();
        parent::listOrders($parterpageModel);
        $this->success("排序更新成功！");
    }	
}
