<?php

class Domain_Home {
	//首页
    public function getindex() {
        $rs = array();
        $model = new Model_Home();
        $slide = $model->getSlide(1);
		foreach($slide as $k=>$v){
			$v['image'] = get_upload_path($v['image']);
			$slide[$k]=$v;
		}

		$list = $model->getScanning(0,1); //改成要闻速览
        $configpub = getConfigPub();
        foreach($list as $k=>$v){
            $v['thumb'] = get_upload_path($v['thumb']);
            $v['url'] = $configpub['site'].'/wap/home/scanning/id/'.$v['id'];
            $v['date']=date('Y-m-d',$v['addtime']);
            $list[$k] = $v;
        }
		$rs = [
			'slide'=>$slide,
			'menu'=> [],
			'list'=>$list,
            'duwu_name' => '有声读物',
		];

        return $rs;
    }


    //学习手册
    public function learnindex()
    {
        $rs    = array();
        $model = new Model_Home();

        $window = $model->getwindow(1, 4);
        $config = getConfigPub();
        foreach ($window as $k => $v) {
            $v['thumb']   = get_upload_path($v['thumb']);
            $v['addtime'] = date('Y-m-d', $v['addtime']);
            //app要求命名
            $v['url_a'] = $config['site'] . '/wap/home/window/id/' . $v['id'];
            $window[$k] = $v;
        }

        //党史历史
        $danghistory = $model->getDanghistoryList();

        foreach ($danghistory as $k => $v) {
            $v['thumb']      = get_upload_path($v['thumb']);
            $v['addtime']    = date('Y-m-d', $v['addtime']);
            $danghistory[$k] = $v;
        }

        $rs = [
            'window'      => $window,
            'lession_title' => '党建参阅',
            'danghistory' => $danghistory,
            'listtype'   => '2',
        ];
        return $rs;
    }

    //党建参阅
    public function windowlist($p) {
        $rs = array();
        $model = new Model_Home();

        $window = $model->getwindow($p);
        $config = getConfigPub();
        foreach($window as $k=>$v){
            $v['thumb'] = get_upload_path($v['thumb']);
            $v['addtime'] = date('Y-m-d',$v['addtime']);
            //前端要求不能用url
            $v['url_a'] = $config['site'].'/wap/home/window/id/'.$v['id'];
            $window[$k] =$v;
        }
        $rs = [
            'window'=>$window,

        ];
        return $rs;
    }

    //党员
    public function getparter($branchid,$p){

        $model = new Model_Home();
        $list = $model->getparter($branchid,$p);
        foreach($list as $k=>$v){
            $list[$k] = $this->parterdata($v);
        }
        return $list;
    }

    //党员详情
    public function getpartershow($id){

        $model = new Model_Home();
        $list = $model->getpartershow($id);

        $list= $this->parterdata($list);

        return $list;
    }

    //党员信息处理
    protected function parterdata($data){
        $data['thumb']=get_upload_path($data['thumb']);
        $data['post']=getpost($data['partypost']);
        $data['branch']=getbranch($data['partybranch']);
        return $data;
    }


	//通知公告
    public function notice($p) {
        $rs = array();
        $model = new Model_Home();

		$lession = $model->notice($p);
		$config = getConfigPub();
		foreach($lession as $k=>$v){
			$v['addtime'] = date('Y-m-d',$v['addtime']);
			$v['url'] = $config['site'].'/wap/home/notice/id/'.$v['id'];
			$lession[$k] =$v;
		}
		$rs = [
			'list'=>$lession,
		];
        return $rs;
    }
    

	//要闻速览(首页文章)
	public function getScanning($p) {

		$model = new Model_Home();
		$list = $model->getScanning($p);

		$configpub = getConfigPub();
		foreach($list as $k=>$v){
			$v['thumb'] = get_upload_path($v['thumb']);
			$v['url'] = $configpub['site'].'/wap/home/scanning/id/'.$v['id'];
			$v['date']=date('Y-m-d',$v['addtime']);
			$list[$k] = $v;
		}
		return $list;
	}


    //根据党史id获取文章列表
    public function getKnowledgeById($id)
    {
        $model = new Model_Home();
        $info  = $model->getKnowledgeById($id);
        $config = getConfigPub();

        foreach ($info as $k => $v) {
            $v['addtime'] = date('Y-m-d', $v['addtime']);
            //app要求命名
            $v['url_a'] = $config['site'] . '/wap/home/knowledge/id/' . $v['id'];
            $info[$k] = $v;
        }
        return $info;
    }


    //党史知识更多
    public function danghistoryList()
    {
        $model = new Model_Home();
        $info  = $model->danghistoryList();
        foreach ($info as $k => $v) {
            $v['thumb'] = get_upload_path($v['thumb']);
            $v['addtime'] = date('Y-m-d', $v['addtime']);
            $info[$k] = $v;
        }

        return $info;
    }




}
