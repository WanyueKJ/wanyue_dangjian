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

class ParterValidate extends Validate
{
    protected $rule = [
        'name' => 'require',
        'content' => 'require',
        'thumb' => 'require',

    ];
    protected $message = [
        'name.require' => '姓名不能为空',
        'content.require' => '内容不能为空',
        'thumb.require' => '图片不能为空',
    ];


}
