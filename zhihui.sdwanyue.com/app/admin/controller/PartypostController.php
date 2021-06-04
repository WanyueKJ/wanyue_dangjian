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

use app\admin\model\PartypostModel;
use cmf\controller\AdminBaseController;
use think\Db;

class PartypostController extends AdminBaseController
{


    public function index()
    {

		$list = DB::name('partypost')->order("list_order asc")->select();
        $this->assign('list', $list);
        return $this->fetch();
    }


    public function add()
    {
        return $this->fetch();
    }


    public function addPost()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->param();
            $result         = $this->validate($data, 'Zidian');
            if ($result !== true) {
                $this->error($result);
            }			
            $result         = DB::name('partypost')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("Partypost/index"));
        }
    }

    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = DB::name('partypost')->where('id',$id)->find();
        $this->assign('result', $result);
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result         = $this->validate($data, 'Zidian');
            if ($result !== true) {
                $this->error($result);
            }
            $slidePostModel = DB::name('partypost')->where('id',$data['id'])->update($data);
			if($slidePostModel){
				$this->success("保存成功！", url("Partypost/index"));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
			if(DB::name('partypost')->where('id',$id)->delete()){
				$this->success("删除成功！", url("Partypost/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $PartypostModel = new  PartypostModel();
        parent::listOrders($PartypostModel);
        $this->success("排序更新成功！");
    }	
}
