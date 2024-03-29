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

use app\admin\model\WindowModel;
use cmf\controller\AdminBaseController;
use think\Db;

class WindowController extends AdminBaseController
{


	
    public function index()
    {
        $map    = [];
		$data   = $this->request->param();
        $listid = isset($data['listid']) && $data['listid'] ? $data['listid'] : '';
        if ($listid != '') {
            $map[] = ['listid', '=', $listid];
        }
		$list = DB::name('window')->where($map)->order("list_order asc")->paginate(10, false, ['query' => input()]);	
		$page = $list->render();
		$this->assign("page", $page);
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
            $validate         = $this->validate($data, 'Window');
            if ($validate !== true) {
                $this->error($validate);
            }				
			$data['addtime']=time();
            $result         = DB::name('window')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("window/index"));
        }
    }

    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = DB::name('window')->where('id',$id)->find();
        $this->assign('result', $result);

        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $validate         = $this->validate($data, 'Window');
            if ($validate !== true) {
                $this->error($validate);
            }			
			$data['addtime']=time();
            $slidePostModel = DB::name('window')->where('id',$data['id'])->update($data);
			
			if($slidePostModel){
				$this->success("保存成功！", url("window/index"));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
			if(DB::name('window')->where('id',$id)->delete()){
				$this->success("删除成功！", url("window/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $windowpageModel = new  WindowModel();
        parent::listOrders($windowpageModel);
        $this->success("排序更新成功！");
    }	
}
