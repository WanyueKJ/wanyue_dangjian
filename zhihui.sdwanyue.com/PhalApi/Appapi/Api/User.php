<?php
/**
 * 用户信息
 */
session_start();

class Api_User extends PhalApi_Api
{

    public function getRules()
    {
        return array(
            'iftoken' => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),
            'getBaseInfo' => array(
                'uid'         => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token'       => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
            ),
            'updateFields' => array(
                'uid'    => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token'  => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'fields' => array('name' => 'fields', 'type' => 'string', 'require' => true, 'desc' => '修改信息，json字符串'),
            ),
            'updatePass'    => array(
                'uid'     => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token'   => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'oldpass' => array('name' => 'oldpass', 'type' => 'string', 'require' => true, 'desc' => '旧密码'),
                'pass'    => array('name' => 'pass', 'type' => 'string', 'require' => true, 'desc' => '新密码'),
                'pass2'   => array('name' => 'pass2', 'type' => 'string', 'require' => true, 'desc' => '确认密码'),
            ),
            'history'       => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p'     => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页数'),
            ),
            'cleanhistory'  => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),
            'myupdate'      => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p'     => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页数'),
            ),
            'collect_news'  => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p'     => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页数'),
            ),
            'collect_think' => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p'     => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页数'),
            ),
            'msg'           => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'p'     => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页数'),
            ),
            'getParty'      => array(
                'uid'   => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'date'  => array('name' => 'date', 'type' => 'string', 'require' => true, 'desc' => '年份'),
            ),
            'payParty'      => array(
                'yearmonth_id' => array('name' => 'yearmonth_id', 'type' => 'string', 'require' => true, 'desc' => '年月份'),
            ),
        );
    }

    /**
     * 获取缴费明细
     * @desc 用于获取缴费明细
     * @return int code 操作码
     * @return array info
     * @return string msg 提示信息
     */
    public function getParty()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);
        $date  = checkNull($this->date);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        $domain     = new Domain_User();
        $result     = $domain->getParty($uid, $date);
        $rs['info'] = $result;

        return $rs;
    }


    /**
     * 判断token
     * @desc 用于判断token
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return string msg 提示信息
     */
    public function iftoken()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        return $rs;
    }

    /**
     * 获取用户信息
     * @desc 用于获取单个用户基本信息
     * @return int code 操作码，0表示成功， 1表示用户不存在
     * @return array info
     * @return array info[0] 用户信息
     * @return int info[0].userinfo 用户信息
     * @return int info[0].menu 用户菜单
     * @return string msg 提示信息
     */
    public function getBaseInfo()
    {
        $rs         = array('code' => 0, 'msg' => '', 'info' => array());
        $uid        = checkNull($this->uid);
        $token      = checkNull($this->token);
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain        = new Domain_User();
        $rs['info'][0] = $domain->getBaseInfo($uid);


        return $rs;
    }

    /**
     * 修改用户信息
     * @desc 用于修改用户信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string list[0].msg 修改成功提示信息
     * @return string msg 提示信息
     */
    public function updateFields()
    {
        $rs     = array('code' => 0, 'msg' => '修改成功', 'info' => array());
        $uid    = checkNull($this->uid);
        $token  = checkNull($this->token);
        $fields = $this->fields;

        $checkToken = checkToken($uid, $token);

        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }


        $fields = json_decode($fields, true);
        $allow  = ['user_nickname', 'avatar', 'partybranch', 'partypost'];
        $domain = new Domain_User();
        foreach ($fields as $k => $v) {
            if (in_array($k, $allow)) {
                $fields[$k] = checkNull($v);
            } else {
                unset($fields[$k]);
            }

        }

        if (array_key_exists('user_nickname', $fields)) {

            if ($fields['user_nickname'] == '') {
                $rs['code'] = 1002;
                $rs['msg']  = '昵称不能为空';
                return $rs;
            }

            $isexist = $domain->checkName($uid, $fields['user_nickname']);

            if (!$isexist) {
                $rs['code'] = 1002;
                $rs['msg']  = '昵称重复，请修改';
                return $rs;
            }

            // $fields['user_nickname']=filterField($fields['user_nickname']);
        }


        $info = $domain->userUpdate($uid, $fields);
        if ($info === false) {
            $rs['code'] = 1001;
            $rs['msg']  = '修改失败';
            return $rs;
        }
        /* 清除缓存 */
        delCache("userinfo_" . $this->uid);
        $rs['info'][0]['msg'] = '修改成功';
        return $rs;
    }

    /**
     * 修改密码
     * @desc 用于修改用户信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string list[0].msg 修改成功提示信息
     * @return string msg 提示信息
     */
    public function updatePass()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid     = checkNull($this->uid);
        $token   = checkNull($this->token);
        $oldpass = checkNull($this->oldpass);
        $pass    = checkNull($this->pass);
        $pass2   = checkNull($this->pass2);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        if ($pass != $pass2) {
            $rs['code'] = 1002;
            $rs['msg']  = '两次新密码不一致';
            return $rs;
        }

        $check = passcheck($pass);
        if ($check == 0) {
            $rs['code'] = 1004;
            $rs['msg']  = '密码为6-12位数字与字母组合';
            return $rs;
        }
        if ($check == 2) {
            $rs['code'] = 1005;
            $rs['msg']  = '密码不能纯数字或纯字母';
            return $rs;
        }

        $domain = new Domain_User();
        $info   = $domain->updatePass($uid, $oldpass, $pass);

        if ($info == 1003) {
            $rs['code'] = 1003;
            $rs['msg']  = '旧密码错误';
            return $rs;
        }
        if ($info === false) {
            $rs['code'] = 1001;
            $rs['msg']  = '修改失败';
            return $rs;
        }

        $rs['info'][0]['msg'] = '修改成功';
        return $rs;
    }

    /**
     * 历史记录
     * @desc 历史记录
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['list'] 思想交流列表
     * @return string msg 提示信息
     */
    public function history()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $p = checkNull($this->p);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        $domain = new Domain_User();
        $info   = $domain->history($uid, $p);

        $rs['info'][0] = $info;

        return $rs;
    }

    //

    /**
     * 清空历史记录
     * @desc 清空历史记录
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function cleanhistory()
    {
        $rs         = array('code' => 0, 'msg' => '已清空记录', 'info' => array());
        $uid        = checkNull($this->uid);
        $token      = checkNull($this->token);
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_User();
        $info   = $domain->cleanhistory($uid);
        if (!$info) {
            $rs['code'] = 1001;
            $rs['msg']  = '清空失败，请稍后重试！';
            return $rs;
        }

        return $rs;
    }

    /**
     * 我发表的
     * @desc 我发表的
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['list'] 思想交流列表
     * @return string msg 提示信息
     */
    public function myupdate()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $p = checkNull($this->p);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        $domain        = new Domain_User();
        $info          = $domain->myupdate($uid, $p);
        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 收藏-新闻
     * @desc 收藏-新闻
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['list'] 思想交流列表
     * @return string msg 提示信息
     */
    public function collect_news()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $p = checkNull($this->p);


        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        $domain = new Domain_User();
        $info   = $domain->collect_news($uid, $p);

        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 收藏-思想交流
     * @desc 收藏-思想交流
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['list'] 思想交流列表
     * @return string msg 提示信息
     */
    public function collect_think()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $p = checkNull($this->p);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }
        $domain = new Domain_User();
        $info   = $domain->collect_think($uid, $p);

        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 消息通知
     * @desc 消息通知
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['list'] 思想交流列表
     * @return string msg 提示信息
     */
    public function msg()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $p = checkNull($this->p);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_User();
        $info   = $domain->msg($uid, $p);

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     * 获取积分记录
     * @desc 获取积分记录
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function GetScoreHistory()
    {

        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_User();
        $info   = $domain->getScoreHistory($uid);

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     * 获取兑换记录
     * @desc 获取积分记录
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getDuihuanHistory()
    {

        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_User();
        $info   = $domain->getDuihuanHistory($uid);

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     * 支付党费
     * @desc 支付党费
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function payParty()
    {
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $year_month = $this->yearmonth_id;

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        if ($year_month < 1) {
            $rs['code'] = 1006;
            $rs['msg']  = '参数错误';
            return $rs;
        }

        $domain = new Domain_User();
        $res    = $domain->payParty($uid, $year_month);

        return $res;

    }


    /**
     * 获取用户权限
     * @desc 获取用户权限
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function getUserAuth()
    {
        $rs    = array('code' => 0, 'msg' => '', 'info' => array());
        $uid   = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_User();
        $res    = $domain->getUserAuth();

        $rs['info'] = $res;

        return $rs;


    }


}
