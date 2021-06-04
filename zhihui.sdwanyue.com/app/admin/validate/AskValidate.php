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
namespace app\admin\validate;

use think\Validate;

class AskValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'count_option' => 'number|min:1',
        'count_max' => 'number|min:1',
        'count_min' =>  'number|min:0',
        'listid' => 'require',
    ];
    protected $message = [
        'title.require' => '题干不能为空',
        'count_option.number' => '选项数量为数字',
        'count_max.number' => '最多可选为数字',
        'count_min.number' => '最少可选为数字',
        'count_option.require'  => '选项数量请填入大于0的整数',
        'count_max.require'  => '最多可选请填入大于0的整数',
        'count_min.require' => '最少可选请填入不小于于0的整数',
        'listid.require' => '问卷id不能为空',
    ];


}
