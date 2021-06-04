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

use app\admin\model\CommentsModel as CommentsModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class CommentsController extends AdminBaseController
{
    public function index()
    {
        $map    = [];
		$data   = $this->request->param();
        $list = CommentsModel::where(function (Query $query) {
            $data = $this->request->param();
            $query->where('type', $data['type']);
            if (!empty($data['uid'])) {
                $query->where('uid', intval($data['uid']));
            }
            if (!empty($data['keyword'])) {
                $keyword = $data['keyword'];
                $query->where('content', 'like', "%$keyword%");
            }

        })->order("addtime DESC")
        ->paginate(10);

        $list->each(function ($v, $k) {
            $userinfo = Db::name('user')->where('id', '=', $v['uid'])->find();
            if(!$userinfo){
                $userinfo['user_nickname'] = '用户已被删除';
            }
			$v['userinfo']=$userinfo;
            return $v;
        });		
        $page = $list->render();
		$this->assign("page", $page);
		$this->assign("type", $data['type']);
        $this->assign('list', $list);
        return $this->fetch();
    }


    public function edit()
    {
        $id             = $this->request->param('id');
        $result          = CommentsModel::find($id);
        $this->assign('result', $result);
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $slidePostModel = CommentsModel::where('id',$data['id'])->update($data);
			
			if($slidePostModel){

                $info = CommentsModel::where('id',$data['id'])->find();
                $msg['uid'] = $info['uid'];
                $msg['addtime'] = time();
                if($data['status']=='2'){
                    $msg['conotent'] ='你的评论“'.$info['content'].'”审核未通过~';
                }
                if($data['status']=='1'){
                    $msg['conotent'] ='你的评论“'.$info['content'].'”审核通过~';
                }
                if($data['status']=='0'){
                    $msg['conotent'] ='你的评论“'.$info['content'].'”审核未通过~';
                }	
                DB::name('msg')->insert($msg);
				$this->success("编辑成功！", url("Comments/index?type=".$data['type']));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {
            $id  = $this->request->param('id', 0, 'intval');
            $info = CommentsModel::where('id',$id)->find();
            

			if(CommentsModel::where('id',$id)->delete()){
				$this->success("删除成功！", url("Comments/index?type=".$info['type']));
			}
			
            $this->error('信息错误');
        }
    }
}
