<?php

class Model_Login extends PhalApi_Model_NotORM
{

    protected $fields = 'id,user_nickname,avatar,sex,signature,birthday,user_status,last_login_time,mobile,party_money,score,is_admin_import,user_pass';


    public function getinfo($user_login)
    {
        $info = DI()->notorm->users
            ->select($this->fields)
            ->where('user_login=? and user_type="2"', $user_login)
            ->fetchOne();

        return $info;
    }

    public function getUserInfo($where)
    {
        $info = DI()->notorm->users
            ->select($this->fields)
            ->where($where)
            ->fetchOne();

        return $info;
    }

    public function checkinfo($user_login)
    {
        $info = DI()->notorm->users
            ->select($this->fields)
            ->where('user_login=? ', $user_login)
            ->fetchOne();

        return $info;
    }

    public function insertuser($data)
    {
        $rs = DI()->notorm->users->insert($data);

        return $rs;
    }


    //更新token
    public function changetoken($where, $data)
    {
        return DI()->notorm->users_token
            ->insert_update($where, $data, $data);
    }

    //更新登录时间
    public function changelogintime($uid, $data)
    {
        return DI()->notorm->users
            ->where('id=?', $uid)
            ->update($data);
    }

    //修改密码
    public function updatepass($uid, $user_pass)
    {
        return DI()->notorm->users
            ->where('id=?', $uid)
            ->update(array('user_pass' => $user_pass));
    }


}
