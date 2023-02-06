<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/13 09:01
// +----------------------------------------------------------------------
namespace app\user\controller;

use app\user\model\UserModel;
use cmf\controller\HomeBaseController;

class IndexController extends HomeBaseController
{

    /**
     * 前台用户首页(公开)
     */
    public function index()
    {
        $id        = $this->request->param("id", 0, "intval");
        $userModel = new UserModel();
        $user      = $userModel->where('id', $id)->find();
        if (empty($user)) {
            $this->error("查无此人！");
        }
        $this->assign($user->toArray());
        $this->assign('user',$user);
        return $this->fetch(":index");
    }

    /**
     * 前台ajax 判断用户登录状态接口
     */
    function isLogin()
    {
        if (cmf_is_user_login()) {
            $this->success("用户已登录",null,['user'=>cmf_get_current_user()]);
        } else {
            $this->error("此用户未登录!");
        }
    }

    /**
     * 退出登录
    */
    public function logout()
    {
        session("user", null);//只有前台用户退出
        return redirect($this->request->root() . "/");
    }

}
