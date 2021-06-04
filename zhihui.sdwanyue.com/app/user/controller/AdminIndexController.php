<?php


namespace app\user\controller;

use app\user\model\UserModel;
use cmf\controller\AdminBaseController;
use think\db\Query;
use think\Db;

/**
 * Class AdminIndexController
 * @package app\user\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'用户管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'用户管理'
 * )
 *
 * @adminMenuRoot(
 *     'name'   =>'用户组',
 *     'action' =>'default1',
 *     'parent' =>'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'',
 *     'remark' =>'用户组'
 * )
 */
class AdminIndexController extends AdminBaseController
{

    /**
     * 后台本站用户列表
     * @adminMenu(
     *     'name'   => '本站用户',
     *     'parent' => 'default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('user_admin_index_view');

        if (!empty($content)) {
            return $content;
        }

        $list = UserModel::where(function (Query $query) {
            $data = $this->request->param();
            if (!empty($data['uid'])) {
                $query->where('id', intval($data['uid']));
            }
            $query->where('user_type', 2);
            if (!empty($data['keyword'])) {
                $keyword = $data['keyword'];
                $query->where('user_login|user_nickname|user_email|mobile', 'like', "%$keyword%");
            }

        })->order("create_time DESC")
            ->paginate(10);

        $list->each(function ($item, $key) {
            $item->post   = $this->getpost($item->partypost);
            $item->branch = $this->getbranch($item->partybranch);
        });

        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        // 渲染模板输出
        return $this->fetch();
    }


    /**
     * 添加用户
     */
    public function add(){

        $partybranch_arr =db('partybranch')->select();
        $partypost_arr =db('partypost')->select();

        return $this->fetch('add', [
            'partybranch_arr' => $partybranch_arr,
            'partypost_arr' => $partypost_arr
        ]);

    }


    /**
     * 处理添加用户
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->param();

            $validate         = $this->validate($data, 'Users');
            if ($validate !== true) {
                $this->error($validate);
            }

            $mobile = $data['mobile'] ?? '';
            $isExists = DB::name('users')->where('mobile', $mobile)->find();
            if ($isExists) {
                $this->error('手机号重复, 请重新输入');
            }

            $data['create_time']=time();
            $data['avatar'] = '/default.jpg';
            $data['last_login_ip'] =  $_SERVER['REMOTE_ADDR'];
            $data['user_type'] =  2;
            $data['is_admin_import'] =  1;
            $data['user_pass'] = setPass('123456');
            $data['user_login'] = $data['mobile'] ?? '';

            $result         = DB::name('users')->insert($data);
            if (!$result) {
                $this->error($result);
            }
            $this->success("添加成功！", url("adminIndex/index"));
        }
    }


    /**
     * 本站用户拉黑
     * @adminMenu(
     *     'name'   => '本站用户拉黑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户拉黑',
     *     'param'  => ''
     * )
     */
    public function ban()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = UserModel::where(["id" => $id, "user_type" => 2])->update(['user_status' => 0]);
            if ($result) {
                $this->success("会员拉黑成功！", "adminIndex/index");
            } else {
                $this->error('会员拉黑失败,会员不存在,或者是管理员！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 本站用户启用
     * @adminMenu(
     *     'name'   => '本站用户启用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户启用',
     *     'param'  => ''
     * )
     */
    public function cancelBan()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            UserModel::where(["id" => $id, "user_type" => 2])->update(['user_status' => 1]);
            $this->success("会员启用成功！", '');
        } else {
            $this->error('数据传入失败！');
        }
    }

    private function getpost($id)
    {
        $patrtypost = DB::name('partypost')->where("id", $id)->find();
        return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
    }

    private function getbranch($id)
    {
        $patrtypost = DB::name('partybranch')->where("id", $id)->find();
        return $patrtypost['name'] ? $patrtypost['name'] : '未指定';
    }

    public function setParty()
    {
        $data        = $this->request->param();
        $uid         = $data['uid'];
        $party_money = $data['party_money'];
        $result      = UserModel::where(["id" => $uid, "user_type" => 2])->update(['party_money' => $party_money]);
        echo $result;
        exit;
    }


    //设置积分
    public function setScore() {
        $data        = $this->request->param();
        $uid         = $data['uid'];
        $party_money = $data['user_score'];
        $result      = UserModel::where(["id" => $uid, "user_type" => 2])->update(['score' => $party_money]);
        echo $result;
        exit;
    }


    // 导入excel页面
    public function importuser() {
        return $this->fetch('importuser');
    }


    //导入excel客户
    public function import()
    {

        $data      = $this->request->param();
        $file_path = $data['user_excel'] ?? '';
        if (!$file_path) {
            $this->error('上传失败');
        }

        require_once CMF_ROOT . 'sdk/PHPExcel/PHPExcel.php';
        $Phpexcel = new \PHPExcel();
        //默认上传目录
        //WEB_ROOT . 'upload' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
        // user/20210326/fb269b0edd0804a13a353f021bf833ca.xlsx
        $file_name = WEB_ROOT . 'upload' . DIRECTORY_SEPARATOR . $file_path;
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); //表格后缀格式

        $objReader = \PHPExcel_IOFactory::createReaderForFile($file_name);; //准备打开文件
        $objPHPExcel = $objReader->load($file_name);

        $sheet         = $objPHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow    = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数

        //党支部数据
        $partybranArr = db('partybranch')->column(['name', 'id']);
        //党内职务数据
        $partyPosArr = db('partypost')->column(['name', 'id']);

        /** 循环读取每个单元格的数据 */
        $user_insert = [];
        $now         = time();
        $addr        = $_SERVER['REMOTE_ADDR'];

        //所有手机号
        $mobileData = db('users')->column(['mobile', 'id']);

        //手机号、用户昵称、用户党支部和党内职务和党费
        for ($row = 1; $row <= $highestRow; $row++)    //行号从1开始
        {
            $user_insert[$row - 1] = [
                'create_time'     => $now,
                'avatar'          => '/default.jpg',
                'last_login_ip'   => $addr,
                'user_type'       => 2,
                'is_admin_import' => 1, //后台导入
                'user_pass'       => setPass('123456'), //初始密码123456
            ];

            for ($column = 'A'; $column <= $highestColumm; $column++)  //列数是以A列开始
            {
                $dataset[] = $sheet->getCell($column . $row)->getValue();
                $value     = $sheet->getCell($column . $row)->getValue();

                if ($column == 'A') {
                    if (isset($mobileData[$value])) {
                        $this->error('手机号: ' . $value . '重复, 请重新导入');
                    }
                    //手机号
                    $user_insert[$row - 1]['mobile']     = $value;
                    $user_insert[$row - 1]['user_login'] = $value;
                }

                if ($column == 'B') {
                    //昵称
                    $user_insert[$row - 1]['user_nickname'] = $value;
                }

                if ($column == 'C') {
                    //党支部
                    $user_insert[$row - 1]['partybranch'] = $partybranArr[$value] ?? '';
                }

                if ($column == 'D') {
                    //党内职务
                    $user_insert[$row - 1]['partypost'] = $partyPosArr[$value] ?? '';
                }

                if ($column == 'E') {
                    //党费
                    $user_insert[$row - 1]['party_money'] = $value;
                }
            }

        }

        $res = db('users')->insertAll($user_insert);
        if ($res) {
            $this->success('导入成功', "adminIndex/index");
        }

        $this->error('导入失败');

    }


    /**
     * 重置密码
     */
    public function resetpass() {
        $data = $this->request->param();
        $uid = $data['id'] ?? 0;
        if (!$uid) {
            $this->error('参数错误');
        }
        $old_pass = db('users')->where('id', $uid)->value('user_pass');
        if((string)$old_pass === setPass('123456')) {
            $this->success('重置密码成功');
        }
        $res = db('users')->where('id', $uid)->update(['user_pass' => setPass('123456')]); //默认123456
        if ($res) {
            $this->success('重置密码成功');
        }
        $this->error('重置密码失败');

    }




}
