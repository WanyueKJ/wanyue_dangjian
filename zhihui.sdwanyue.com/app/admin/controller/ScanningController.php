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

use app\admin\model\ScanningModel as Model;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;
class ScanningController extends AdminBaseController
{
    public function index()
    {
        $list = Model::paginate(10, false, ['query' => input()]);
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

            $data  = $this->request->param();


            if($data['title'] == ''){
                $this->error('请填写标题');
            }

            if($data['thumb'] == ''){
                $this->error('请上传封面');
            }

            if($data['content'] == ''){
                $this->error('请填写内容');
            }

            $data['addtime']=time();
            $result = Model::insert($data);

            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("scanning/index"));
        }
    }

    public function edit()
    {

        $data = $this->request->param();
        $id = $data['id'];
        $result = Model::find($id);
        $this->assign('result', $result);
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();

            if($data['title'] == ''){
                $this->error('请填写标题');
            }
    
            if($data['thumb'] == ''){
                $this->error('请上传封面');
            }
    
            if($data['content'] == ''){
                $this->error('请填写内容');
            }
    
            $data['addtime']=time();
            $result = Model::update($data,['id' => $data['id']]);

            //$slidePostModel = DB::name('active')->where('id',$data['id'])->update($data);
			
			if($result){
				$this->success("保存成功！", url("scanning/index"));
			}
             $this->error('信息错误');
        }
    }


    public function delete()
    {
        if ($this->request->isPost()) {

            
            $id   = $this->request->param('id', 0, 'intval');
            $result = Model::where(['id'=>$id])->delete();
			if($result){
				$this->success("删除成功！", url("scanning/index"));
			}
			
            $this->error('信息错误');
        }
    }

    public function listOrder()
    {
        //$NewspageModel = new  ActiveModel();
        parent::listOrders(new Model());
        $this->success("排序更新成功！");
    }	
}
