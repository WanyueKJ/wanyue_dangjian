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
namespace app\user\validate;

use think\Validate;

class UsersValidate extends Validate
{
    protected $rule
        = [
            'user_nickname' => 'require',
            'mobile'        => 'require|mobile',
            'partybranch'   => 'require|number',
            'partypost'     => 'require|number'
        ];
    protected $message
        = [
            'user_nickname.require' => '用户昵称不能为空',
            'mobile.require'        => '手机号不能为空',
            'mobile.mobile'         => '手机号格式错误',
            'partybranch.require'   => '党支部必选',
            'partypost.require'     => '职位必选'
        ];

//    protected $scene
//        = [
//            'add'  => ['post_title'],
//            'edit' => ['post_title'],
//        ];
}
