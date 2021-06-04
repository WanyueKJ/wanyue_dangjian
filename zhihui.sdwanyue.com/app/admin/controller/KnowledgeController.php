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

use app\admin\model\KnowledgeModel;
use app\admin\model\LessionModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * 党史历史文章
 * @package app\admin\controller
 */
class KnowledgeController extends AdminBaseController
{

    public function index()
    {
        $map    = [];
        $data   = $this->request->param();
        $hid = $data['hid'] ?? '';
        if ($hid != '') {
            $map[] = ['hid', '=', $hid];
        }
        $list = DB::name('knowledge')->where($map)->order("list_order asc")->paginate(10, false, ['query' => input()]);

        $page = $list->render();
        $this->assign("page", $page);
        $this->assign('list', $list);
        $this->assign('hid', $hid);

        return $this->fetch();
    }


    public function add()
    {
        $hid = input('hid') ?? '';
        $this->assign('hid', $hid);
        return $this->fetch();
    }


    public function addPost()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->param();
            $validate         = $this->validate($data, 'Knowledge');
            if ($validate !== true) {
                $this->error($validate);
            }
            $data['addtime']=time();
            $result         = DB::name('knowledge')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("knowledge/index", array('hid'=>$data['hid'])));
        }
    }

    public function edit()
    {
        $id             = $this->request->param('id');
        $hid = input('hid') ?? '';
        $result          = DB::name('knowledge')->where('id',$id)->find();
        $this->assign('result', $result);
        $this->assign('hid', $hid);

        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $validate         = $this->validate($data, 'Knowledge');
            if ($validate !== true) {
                $this->error($validate);
            }
            $hid = $data['hid'] ?? '';
            $slidePostModel = DB::name('knowledge')->where('id',$data['id'])->update($data);

            if($slidePostModel){
                $this->success("保存成功！", url("knowledge/index", array('hid'=>$hid)));
            }
            $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id             = $this->request->param('id', 0, 'intval');
            if(DB::name('knowledge')->where('id',$id)->delete()){
                $this->success("删除成功！", url("knowledge/index"));
            }

            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $lessionpageModel = new KnowledgeModel();
        parent::listOrders($lessionpageModel);
        $this->success("排序更新成功！");
    }
}
