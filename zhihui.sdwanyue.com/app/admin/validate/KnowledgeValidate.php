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

class KnowledgeValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'content' => 'require',

    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'content.require' => '简介不能为空',
    ];


}
