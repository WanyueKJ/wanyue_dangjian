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

use app\admin\model\DanghistoryModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * 党史历史
 * @package app\admin\controller
 */
class DanghistoryController extends AdminBaseController
{

    public function index()
    {
        $map    = [];
        $data   = $this->request->param();
        $listid = $data['listid'] ?? '';

        if ($listid != '') {
            $map[] = ['listid', '=', $listid];
        }
        $list = DB::name('danghistory')->where($map)->order("list_order asc")->paginate(10, false, ['query' => input()]);
        $list->each(function ($v, $k) {
            return $v;
        });
        $page = $list->render();
        $this->assign("page", $page);
        $this->assign('list', $list);
        $this->assign('listid', $listid);

        return $this->fetch();
    }


    public function add()
    {
        return $this->fetch();
    }


    public function addPost()
    {
        if ($this->request->isPost()) {

            $data  = $this->request->param();
            $title = $data['title'] ?? '';
            $thumb = $data['thumb'] ?? '';

            if ($title == '' || $thumb == '') {
                $this->error('请填写完整信息');
            }

            $data['addtime'] = time();

            $result = DB::name('danghistory')->insert($data);

            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！");
        }
    }


    public function edit()
    {

        $id     = $this->request->param('id');
        $result = DB::name('danghistory')->where('id', $id)->find();
        $this->assign('result', $result);

        return $this->fetch();
    }


    public function editPost()
    {
        if ($this->request->isPost()) {
            $data  = $this->request->param();
            $title = $data['title'] ?? '';
            $thumb = $data['thumb'] ?? '';

            if ($title == '' || $thumb == '') {
                $this->error('请填写完整信息');
            }

            $result = DB::name('danghistory')->where(['id' => $data['id']])->update($data);

            $this->success("编辑成功！");
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (DB::name('danghistory')->where('id', $id)->delete()) {
                //删除此党史下所有文章
                db('knowledge')->where('hid', $id)->delete();

                $this->success("删除成功！");
            }

            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        $noticepageModel = new DanghistoryModel();
        parent::listOrders($noticepageModel);
        $this->success("排序更新成功！");
    }
}
