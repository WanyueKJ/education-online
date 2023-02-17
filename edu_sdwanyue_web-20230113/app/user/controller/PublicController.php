<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/14 16:05
// +----------------------------------------------------------------------
namespace app\user\controller;

use app\user\model\UserModel;
use cmf\controller\HomeBaseController;

class PublicController extends HomeBaseController
{

    // 用户头像api
    public function avatar()
    {
        $id   = $this->request->param("id", 0, "intval");
        $user = UserModel::get($id);

        $avatar = '';
        if (!empty($user)) {
            $avatar = cmf_get_user_avatar_url($user['avatar']);
            if (strpos($avatar, "/") === 0) {
                $avatar = $this->request->domain() . $avatar;
            }
        }

        if (empty($avatar)) {
            $cdnSettings = cmf_get_option('cdn_settings');
            if (empty($cdnSettings['cdn_static_root'])) {
                $avatar = $this->request->domain() . "/static/images/headicon.png";
            } else {
                $cdnStaticRoot = rtrim($cdnSettings['cdn_static_root'], '/');
                $avatar        = $cdnStaticRoot . "/static/images/headicon.png";
            }

        }

        return redirect($avatar);
    }

}
