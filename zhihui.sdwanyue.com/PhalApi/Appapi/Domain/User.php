<?php

class Domain_User
{

    public function getParty($uid, $date)
    {
        $rs = array();

        $model = new Model_User();

        $userinfo = getUserInfo($uid);
        $list     = $model->getPartyList($uid, $date);
        if (!$list) {
            return array();
        }
        foreach ($list as $k => $v) {
            $info = $model->getPartyRulesDetail($v['ruleid']);
            if (!$info) {
                $info['date'] = '已删除';
            }
            $v['date']        = date('Y年m月', $info['date']);
            $v['addtime']     = date('Y-m-d H:i:s', $v['addtime']);
            $v['party_money'] = $userinfo['party_money'];

            $list[$k] = $v;
        }
        return $list;
    }

    public function getSlide()
    {
        $model = new Model_Home();
        $slide = $model->getSlide(3);
        return $slide;
    }

    public function getBaseInfo($userId)
    {
        $rs                 = array();
        $userinfo           = getUserInfo($userId);
        $userinfo['avatar'] = get_upload_path($userinfo['avatar']);
        $userinfo['branch'] = [];
        $userinfo['post']   = [];
        $host               = get_host();
        $menu               = [
            ['id' => '1', 'name' => '我发表的', 'image' => $host . '/static/user/1.png', 'api' => 'user.myupdate', 'url' => ''],
            ['id' => '2', 'name' => '我的收藏', 'image' => $host . '/static/user/2.png', 'api' => 'user.collect', 'url' => ''],
            ['id' => '3', 'name' => '浏览历史', 'image' => $host . '/static/user/3.png', 'api' => 'user.history', 'url' => ''],
            ['id' => '4', 'name' => '消息通知', 'image' => $host . '/static/user/4.png', 'api' => 'user.msg', 'url' => ''],
        ];
        $rs                 = [
            'userinfo' => $userinfo,
            'menu'     => $menu,
        ];

        return $rs;
    }

    public function checkName($uid, $name)
    {
        $rs = array();

        $model = new Model_User();
        $rs    = $model->checkName($uid, $name);

        return $rs;
    }

    public function userUpdate($uid, $fields)
    {
        $rs = array();

        $model = new Model_User();
        $rs    = $model->userUpdate($uid, $fields);

        return $rs;
    }

    public function updatePass($uid, $oldpass, $pass)
    {
        $rs = array();

        $model = new Model_User();
        $rs    = $model->updatePass($uid, $oldpass, $pass);

        return $rs;
    }

    //历史
    public function history($uid, $p)
    {


        $rs    = array();
        $model = new Model_User();

        $list = $this->gethistory($uid, $p);

        $rs = [
            'list' => $list,
        ];
        return $rs;
    }

    //历史
    public function cleanhistory($uid)
    {

        $model = new Model_User();
        $rs    = $model->cleanhistory($uid);

        return $rs;
    }

    private function gethistory($uid, $p)
    {


        $rs    = array();
        $model = new Model_User();

        $list = $model->history($uid, $p);
        foreach ($list as $k => $v) {
            $vs = $model->getscanning($v['newsid']);
            if (!$vs) {
                $model->delhistory($v['id']);
                unset($list[$k]);
                continue;
            } else {
                $vs       = $this->scanningdata($vs);
                $list[$k] = $vs;
            }
        }

        $list = array_values($list);

        return $list;
    }

    protected function scanningdata($data)
    {
        $config        = getConfigPub();
        $data['thumb'] = get_upload_path($data['thumb']);
        $data['url']   = $config['site'] . '/wap/home/scanning/id/' . $data['id'];
        $data['date']  = date('Y-m-d', $data['addtime']);
        return $data;
    }


    protected function newsdata($data)
    {
        $config        = getConfigPub();
        $data['thumb'] = get_upload_path($data['thumb']);
        $data['url']   = $config['site'] . '/wap/home/news/id/' . $data['id'];
        $data['date']  = date('Y-m-d', $data['addtime']);
        return $data;
    }

    //我发表的
    public function myupdate($uid, $p)
    {
        $rs    = array();
        $model = new Model_User();

        $config  = getConfigPub();
        $lession = $model->myupdate($uid, $p);
        foreach ($lession as $k => $v) {
            $v['addtime'] = date('Y-m-d', $v['addtime']);
            $userinfo     = getUserInfo($v['uid']);

            $branch             = getbranch($userinfo['partybranch']);
            $post               = getpost($userinfo['partypost']);
            $v['user_nickname'] = $userinfo['user_nickname'];
            $v['avatar']        = get_upload_path($userinfo['avatar']);
            $v['url']           = $config['site'] . '/wap/home/think/id/' . $v['id'];
            $v['branch']        = $branch;
            $v['post']          = $post;
            $arr                = [];
            if ($v['thumbs'] != '') {
                $thumbs = json_decode($v['thumbs'], 1);

                foreach ($thumbs as $a => $b) {
                    $arr[] = get_upload_path($b);
                }
            }
            $v['thumbs'] = $arr;
            $v['zan']    = count_zan($v['id'], 1);
            $lession[$k] = $v;
        }
        $rs = [

            'list' => $lession,
        ];
        return $rs;
    }

    //收藏
    public function collect_news($uid, $p)
    {


        $rs    = array();
        $model = new Model_User();

        $list = $this->getcollect_news($uid, $p);

        $rs = [

            'list' => $list,
        ];
        return $rs;
    }

    private function getcollect_news($uid, $p)
    {

        $rs    = array();
        $model = new Model_User();

        $list = $model->collect_news($uid, $p);
        foreach ($list as $k => $v) {
            $v = $model->getnews($v['newsid']);

            if (!$v) {
                $model->delcollect($v['newsid'], 0);
                unset($list[$k]);
                // return $this->getcollect_news($uid,$p);
                // break;
            } else {
                $v        = $this->newsdata($v);
                $list[$k] = $v;
            }
        }
        $list = array_values($list);
        return $list;
    }

    public function collect_think($uid, $p)
    {


        $rs    = array();
        $model = new Model_User();
        $list = $this->getcollect_think($uid, $p);

        $rs = [
            'list' => $list,
        ];
        return $rs;
    }

    private function getcollect_think($uid, $p)
    {

        $rs    = array();
        $model = new Model_User();

        $list = $model->collect_think($uid, $p);
        foreach ($list as $k => $v) {
            $v = $model->getthink($v['newsid']);
            if (!$v) {
                $model->delcollect($v['newsid'], 1);
                unset($list[$k]);
                // return $this->getcollect_think($uid,$p);
                // break;
            } else {
                $v        = $this->thinkdata($v);
                $list[$k] = $v;
            }
        }
        $list = array_values($list);
        return $list;
    }

    protected function thinkdata($data)
    {
        $data['addtime']       = date('Y-m-d', $data['addtime']);
        $userinfo              = getUserInfo($data['uid']);
        $config                = getConfigPub();
        $data['url']           = $config['site'] . '/wap/home/think/id/' . $data['id'];
        $branch                = getbranch($userinfo['partybranch']);
        $post                  = getpost($userinfo['partypost']);
        $data['user_nickname'] = $userinfo['user_nickname'];
        $data['avatar']        = get_upload_path($userinfo['avatar']);
        $data['branch']        = $branch;
        $data['post']          = $post;
        $arr                   = [];
        if ($data['thumbs'] != '') {
            $thumbs = json_decode($data['thumbs'], 1);

            foreach ($thumbs as $a => $b) {
                $arr[] = get_upload_path($b);
            }
        }
        $data['thumbs'] = $arr;
        $data['zan']    = count_zan($data['id'], 1);
        return $data;
    }

    //我发表的
    public function msg($uid, $p)
    {
        $rs    = array();
        $model = new Model_User();

        $lession = $model->msg($uid, $p);
        foreach ($lession as $k => $v) {
            $v['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
            $lession[$k]  = $v;
        }
        $rs = [
            'list' => $lession,
        ];
        return $rs;
    }

    //获取积分记录
    public function getScoreHistory($uid)
    {
        $model = new Model_User();
        $info  = $model->getScoreHistory($uid);

        foreach ($info as $k => $v) {
            $info[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }
        return $info;
    }

    public function getDuihuanHistory($uid)
    {
        $model = new Model_User();
        $info  = $model->getDuihuanHistory($uid);

        foreach ($info as $k => $v) {
            $info[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }

        return $info;

    }


    //缴党费
    public function payParty($uid, $year_month)
    {
        $rs    = array('code' => 0, 'msg' => '缴费成功', 'info' => array());
        $model = new Model_User();

        $data = [
            'uid'          => $uid,
            'yearmonth_id' => $year_month,
        ];

        $res = $model->payParty($data);
        if (!$res) {
            $rs['msg']  = '缴费失败';
            $rs['code'] = 1004;
            return $rs;
        }

        return $rs;
    }

    public function getUserAuth()
    {
        $model = new Model_User();
        $res   = $model->getUserAuth();
        return $res;
    }


}
