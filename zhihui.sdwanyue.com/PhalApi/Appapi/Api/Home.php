<?php
/**
 * 首页
 */
class Api_Home extends PhalApi_Api {  

	public function getRules() {
		return array(
			'getindex' => array(

			),
			'getparter' => array(
				'branchid' => array('name' => 'branchid', 'type' => 'int', 'require' => true, 'default'=>'0' ,'desc' => '所属社区id，为0是全部党员'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),	
			'getpartershow' => array(
				'id' => array('name' => 'id', 'type' => 'int', 'default'=>'0' , 'require' => true,'desc' => '党员id'),
			),
			'windowlist' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			'notice' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
            'getScanning' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
            'getKnowledgeById' => array(
                'hid' => array('name' => 'hid', 'type' => 'int', 'require' => true, 'default' => '1', 'desc' => '党史id')
            ),
		);
	}
	
    /**
     * 配置信息
     * @desc 用于获取配置信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息
     * @return array info[0].partylist 当群关系列表
     * @return string msg 提示信息
     */
    public function getConfig() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$partylist = getpartylist(); //开源不要党支部
        $rs['info'][0]['partylist']=$partylist;
        $configpub = getConfigPub();
        $rs['info'][0]['small_switch']=$configpub['small_switch'];

        return $rs;
    }	

    /**
     * 首页信息
     * @desc 首页信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0]['slide'] 轮播
     * @return array info[0]['menu'] 轮播
     * @return array info[0]['list'] 新闻列表（10条，如需翻页请求getlist接口）
     * @return string msg 提示信息
     */
    public function getindex() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $domain = new Domain_Home();
		$info = $domain->getindex();
		$rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 新闻页
     * @desc 新闻页
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info 新闻分类
     * @return array info[].name 新闻分类名
     * @return string info[0].menu[].list 当前分类下新闻列表
     * @return string msg 提示信息
     */
    public function getnewsmenu() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $domain = new Domain_Home();
		$info = $domain->getnewsmenu();

		$rs['info'] = $info;
        
        return $rs;
    }	

    /**
     * 党员风采
     * @desc 党员风采
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0]['list'] 新闻列表（10条）
     * @return string msg 提示信息
     */
    public function getparter() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $branchid = checkNull($this->branchid);
        $p = checkNull($this->p);
        $domain = new Domain_Home();
		$info = $domain->getparter($branchid,$p);
		
		$rs['info'][0] = $info;
		$rs['info'][1] = '所有党支部'; //所有党支部

        return $rs;
    }

    /**
     * 党员风采详情
     * @desc 党员风采详情
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['data'] 详细内容
     * @return string msg 提示信息
     */
    public function getpartershow() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $id = checkNull($this->id);
        $domain = new Domain_Home();
        $info = $domain->getpartershow($id);

        $rs['info'][0]['data'] = $info;

        return $rs;
    }


    /**
     * 学习首页
     * @desc 学习首页
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['window'] 党建之窗
     * @return array info[0]['lession'] 党课
     * @return string msg 提示信息
     */
    public function learnindex() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Home();
        $info = $domain->learnindex();

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     *
     * @desc 党建知识更多
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function danghistoryList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Home();
        $info   = $domain->danghistoryList();

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     * 党建参阅(更多)
     * @desc 党建参阅(更多)
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['window'] 党建之窗
     * @return string msg 提示信息
     */
    public function windowlist() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p = checkNull($this->p);

        $domain = new Domain_Home();
		$info = $domain->windowlist($p);

		$rs['info'][0] = $info;

        return $rs;
    }


	/**
     * 通知公告
     * @desc 通知公告
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0]['list'] 思通知公告列表
     * @return string msg 提示信息
     */
    public function notice() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $p = checkNull($this->p);

        $domain = new Domain_Home();
		$info = $domain->notice($p);
		
		$rs['info'][0] = $info;

        return $rs;
    }	


    /**
     * @desc 要闻速览
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
    public function getScanning() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $p = checkNull($this->p);
        $domain = new Domain_Home();
		$info = $domain->getScanning($p);
		
		$rs['info'] = $info;

        return $rs;
    }


    /**
     * 根据党史id获取文章列表
     * @desc 产品分页
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getKnowledgeById(){
        $rs = array('code' => 0, 'msg' => '获取成功', 'info' => array());

        $id     = checkNull($this->hid);
        $domain = new Domain_Home();
        $info   = $domain->getKnowledgeById($id);

        $rs['info'][0] = $info;

        return $rs;
    }



	
} 
