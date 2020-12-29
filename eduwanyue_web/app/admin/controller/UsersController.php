<?php


// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/12 11:01
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\UsersModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

/*
 * 用户管理
 * Class UsersController
 * @package app\admin\controller
 */

class UsersController extends AdminBaseController
{

    /*
     * 用户列表
     * @return mixed
     */
    public function index()
    {

        $data = $this->request->param();
        $map  = [];

        $type = $data['type'] ?? '';
        if ($type != '') {
            $map[] = ['type', '=', $type];
        }

        $start_time = $data['start_time'] ?? '';
        $end_time   = $data['end_time'] ?? '';

        if ($start_time != "") {
            $map[] = ['create_time', '>=', strtotime($start_time)];
        }

        if ($end_time != "") {
            $map[] = ['create_time', '<=', strtotime($end_time) + 60 * 60 * 24];
        }

        $isban = $data['isban'] ?? '';
        if ($isban != '') {
            if ($isban == 1) {
                $map[] = ['user_status', '=', 0];
            } else {
                $map[] = ['user_status', '<>', 0];
            }

        }

        $keyword = $data['keyword'] ?? '';
        if ($keyword != '') {
            $map[] = ['user_login|user_nickname|mobile', 'like', '%' . $keyword . '%'];
        }

        $uid = $data['uid'] ?? '';
        if ($uid != '') {
            $map[] = ['id', '=', $uid];
        }

        $nums = UsersModel::where($map)->count();
        $list = UsersModel::where($map)->order("id desc")->paginate(20, false, ['query' => input()]);

        $list->each(function ($v, $k) {

            $v['user_login'] = m_s($v['user_login']);
            $v['mobile']     = m_s($v['mobile']);
            $v['avatar']     = get_upload_path($v['avatar']);
            $v['signory']    = getSignory($v['signoryid']);

            return $v;
        });

        $list->appends($data);
        // 获取分页显示
        $page = $list->render();

        $this->assign([
            'list' => $list,
            'page' => $page,
            'nums' => $nums,
            'type' => $type
        ]);

        // 渲染模板输出
        return $this->fetch('index');
    }

    /*
     * 教师列表
     * @return mixed
     */
    function teacher()
    {
        return $this->index();
    }

    /*
     * 删除
     */
    function del()
    {
        $id    = $this->request->param('id', 0, 'intval');
        $Users = UsersModel::get($id);
        $rs    = $Users->delete();
        if ($rs === false) {
            $this->error("删除失败！");
        }

        DB::name('users_token')->where(["user_id" => $id])->delete();
        delcache("userinfo_" . $id, "token_" . $id);

        $this->success("删除成功！");

    }

    /**
     * 排序
     */
    public function listOrder()
    {
        $model = DB::name('users');
        parent::listOrders($model);
        $this->success("排序更新成功！");
    }

    /**
     * 本站用户拉黑
     */
    public function ban()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = UsersModel::where(["id" => $id])->setField('user_status', 0);
            if ($result) {

                DB::name('users_token')->where(["user_id" => $id])->delete();
                delcache("userinfo_" . $id, "token_" . $id);

                $this->success("会员拉黑成功！");
            } else {
                $this->error('会员拉黑失败,会员不存在');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 本站用户启用
     */
    public function cancelBan()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            UsersModel::where(["id" => $id])->setField('user_status', 1);
            $this->success("会员启用成功！");
        } else {
            $this->error('数据传入失败！');
        }
    }

    /*
     * 用户添加
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /*
     * 用户添加提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {

            $data = $this->request->param();

            $user_login = $data['user_login'];

            if ($user_login == "") {
                $this->error("请填写手机号");
            }

            if (!checkMobile($user_login)) {
                $this->error("请填写正确手机号");
            }

            $isexist = UsersModel::where(['user_login|mobile' => $user_login])->value('id');
            if ($isexist) {
                $this->error("该账号已存在，请更换");
            }

            $user_pass = $data['user_pass'];
            if ($user_pass == "") {
                $this->error("请填写密码");
            }

            if (!checkPass($user_pass)) {
                $this->error("密码为6-20位字母数字组合");
            }

            $data['user_pass'] = cmf_password($user_pass);

            $user_nickname = $data['user_nickname'];
            if ($user_nickname == "") {
                $this->error("请填写昵称");
            }

            $isexist = UsersModel::where([['user_nickname', '=', $user_nickname]])->find();
            if ($isexist) {
                $this->error("该昵称已存在，请更换");
            }

            $avatar       = $data['avatar'];
            $avatar_thumb = $data['avatar_thumb'];
            if (($avatar == "" || $avatar_thumb == '') && ($avatar != "" || $avatar_thumb != '')) {
                $this->error("请同时上传头像 和 头像小图  或 都不上传");
            }

            if ($avatar == '' && $avatar_thumb == '') {
                $data['avatar']       = '/default.png';
                $data['avatar_thumb'] = '/default_thumb.png';
            }

            $data['create_time'] = time();
            $data['mobile']      = $user_login;

            $id = UsersModel::create($data, true);
            if (!$id) {
                $this->error("添加失败！");
            }

            $this->success("添加成功！");

        }
    }

    /*
     * 用户编辑
     * @return mixed
     */
    function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        $data = UsersModel::where("id={$id}")->find();
        if (!$data) {
            $this->error("信息错误");
        }

        $data['user_login'] = m_s($data['user_login']);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /*
     * 用户编辑提交
     */
    function editPost()
    {
        if ($this->request->isPost()) {

            $data = $this->request->param();

            $id        = $data['id'];
            $user_pass = $data['user_pass'];
            if ($user_pass != "") {
                if (!checkPass($user_pass)) {
                    $this->error("密码为6-20位字母数字组合");
                }

                $data['user_pass'] = cmf_password($user_pass);
            } else {
                unset($data['user_pass']);
            }

            $user_nickname = $data['user_nickname'];
            if ($user_nickname == "") {
                $this->error("请填写昵称");
            }

            $isexist = UsersModel::where([['user_nickname', '=', $user_nickname], ['id', '<>', $id]])->find();
            if ($isexist) {
                $this->error("该昵称已存在，请更换");
            }

            $mobile  = $data['mobile'];
            $isexist = UsersModel::where([['user_login|mobile', '=', $mobile], ['id', '<>', $id]])->find();
            if ($isexist) {
                $this->error("该手机号已存在，请更换");
            }

            $avatar       = $data['avatar'];
            $avatar_thumb = $data['avatar_thumb'];
            if (($avatar == "" || $avatar_thumb == '') && ($avatar != "" || $avatar_thumb != '')) {
                $this->error("请同时上传头像 和 头像小图  或 都不上传");
            }

            if ($avatar == '' && $avatar_thumb == '') {
                $data['avatar']       = '/default.png';
                $data['avatar_thumb'] = '/default_thumb.png';
            }

            $rs = DB::name('users')->update($data);
            if ($rs === false) {
                $this->error("修改失败！");
            }
            delcache("userinfo_" . $data['id']);
            $this->success("修改成功！");
        }
    }

    /*
     *取消讲师
     */
    public function cancelTeacher()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = UsersModel::where(["id" => $id])->update(['type' => 0, 'identity' => '', 'signoryid' => 0]);
            if ($result === false) {
                $this->error('操作失败！');
            }
            delcache("userinfo_" . $id);
            $this->success("操作成功！");
        } else {
            $this->error('数据传入失败！');
        }
    }


    /*
     * 设置为讲师
     */
    function setSignory()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($id) {
            $result = UsersModel::where(['id' => $id])->update(['type' => 1]);
            if ($result === false) {
                $this->error('操作失败');
            }
            delcache("userinfo_" . $id);
            $this->success('操作成功');
        } else {
            $this->error('数据传入失败！');
        }
    }


}
