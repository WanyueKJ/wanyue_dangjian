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

class LessionValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'thumb' => 'require',
        //'url' => 'require',

    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'thumb.require' => '图片不能为空',
        //'url.require' => '视频不能为空',
    ];

 
}
