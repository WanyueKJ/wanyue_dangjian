<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use app\admin\model\AdminMenuModel;
use app\admin\model\AuthAccessModel;
use app\user\model\ThirdPartyUserModel;
use cmf\controller\AdminBaseController;
use tree\Tree;

class AdminOauthController extends AdminBaseController
{

    /**
     * 后台第三方用户列表
     * @adminMenu(
     *     'name'   => '第三方用户',
     *     'parent' => 'user/AdminIndex/default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '第三方用户',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('user_admin_oauth_index_view');

        if (!empty($content)) {
            return $content;
        }

        $lists = ThirdPartyUserModel::field('a.*,u.user_nickname,u.sex,u.avatar')
            ->alias('a')
            ->join('user u', 'a.user_id = u.id')
            ->where("status", 1)
            ->order("create_time DESC")
            ->paginate(10);

        // 获取分页显示
        $page = $lists->render();
        $this->assign('lists', $lists);
        $this->assign('page', $page);
        // 渲染模板输出
        return $this->fetch();
    }


    /**
     * 权限管理(前端注册用户)
     */
    public function frontouth()
    {

        $authData = db('front_auth')->column('*', "id");//获取前台权限表数据
        $this->assign('auth_data', $authData);

        return $this->fetch('frontouth');
    }


    /**
     * 权限管理提交
     */
    public function frontouthPost() {

        $authIds = input('post.menuId/a');
        if (!$authIds) {
            db('front_auth')->where('id', '>', 0)->update(['status' => 1]);
        }
        db('front_auth')->where('id', 'IN', $authIds)->update(['status' => 0]);
        db('front_auth')->where('id', 'NOT IN', $authIds)->update(['status' => 1]);

        $this->success('设置成功');
    }


    /**
     * 检查指定菜单是否有权限
     * @param array $menu menu表中数组
     * @param       $privData
     * @return bool
     */
    private function _isChecked($menu, $privData)
    {
        $app    = $menu['app'];
        $model  = $menu['controller'];
        $action = $menu['action'];
        $name   = strtolower("$app/$model/$action");
        if ($privData) {
            if (in_array($name, $privData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    /**
     * 获取菜单深度
     * @param       $id
     * @param array $array
     * @param int $i
     * @return int
     */
    protected function _getLevel($id, $array = [], $i = 0)
    {
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $array[$id]['parent_id'] == $id) {
            return $i;
        } else {
            $i++;
            return $this->_getLevel($array[$id]['parent_id'], $array, $i);
        }
    }


    /**
     * 后台删除第三方用户绑定
     * @adminMenu(
     *     'name'   => '删除第三方用户绑定',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除第三方用户绑定',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        if ($this->request->isPost()) {
            $id = input('param.id', 0, 'intval');
            if (empty($id)) {
                $this->error('非法数据！');
            }

            ThirdPartyUserModel::where("id", $id)->delete();
            $this->success("删除成功！", url('AdminOauth/index'));
        }
    }


}
