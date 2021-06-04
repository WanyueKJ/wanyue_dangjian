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

use app\admin\model\PartybranchModel;
use cmf\controller\AdminBaseController;
use think\Db;

class PartybranchController extends AdminBaseController
{


    public function index()
    {

		$list = DB::name('partybranch')->order("list_order asc")->select();
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
            $result         = DB::name('partybranch')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("Partybranch/index"));
        }
    }

    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = DB::name('partybranch')->where('id',$id)->find();
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
            $slidePostModel = DB::name('partybranch')->where('id',$data['id'])->update($data);
			if($slidePostModel){
				$this->success("保存成功！", url("Partybranch/index"));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
			if(DB::name('partybranch')->where('id',$id)->delete()){
				$this->success("删除成功！", url("Partybranch/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $PartybranchModel = new  PartybranchModel();
        parent::listOrders($PartybranchModel);
        $this->success("排序更新成功！");
    }	
}
