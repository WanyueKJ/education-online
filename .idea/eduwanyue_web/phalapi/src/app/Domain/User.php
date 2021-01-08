<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/06 15:22
// +----------------------------------------------------------------------
namespace App\Domain;

use App\Model\User as Model_User;

class User
{

    /* 用户基本信息 */
    public function getBaseInfo($uid)
    {

        $model = new Model_User();
        $info  = $model->getBaseInfo($uid);

        if ($info) {
            //$birthday=$info['birthday'];
            $info            = \App\handleUser($info);
            $info['follows'] = \App\getFollowNum($uid);
            $info['fans']    = \App\getFansNum($uid);
            //$info['birthday']=date('Y-m-d',$birthday);

            if ($info['school'] == '') {
                $info['school'] = '';
            }

            if ($info['experience'] == '') {
                $info['experience'] = '';
            }

            if ($info['feature'] == '') {
                $info['feature'] = '';
            }

            unset($info['birthday']);
        }

        return $info;
    }


    /**
     * 上传图片
     */
    public function uploadImg()
    {

    }


    /* 更新基本信息 */
    public function upUserInfo($uid, $fields = [])
    {

        $rs = array('code' => 0, 'msg' => \PhalApi\T('操作成功'), 'info' => array());

        $model = new Model_User();
        $data  = [];
        $info  = [];
        /* 头像 */
        if (isset($fields['avatar']) && $fields['avatar'] != '') {
            $avatar_q = $fields['avatar'];
            $uptype   = \PhalApi\DI()->config->get('app.uptype');

            if ($uptype == 1 && !strstr($avatar_q, '?')) {
                $avatar       = $avatar_q . '?imageView2/2/w/600/h/600'; //600 X 600
                $avatar_thumb = $avatar_q . '?imageView2/2/w/200/h/200'; // 200 X 200
            } else {
                $avatar       = $avatar_q;
                $avatar_thumb = $avatar_q;
            }


            $data['avatar']       = $avatar;
            $data['avatar_thumb'] = $avatar_thumb;

            $info['avatar']       = \App\get_upload_path($avatar);
            $info['avatar_thumb'] = \App\get_upload_path($avatar_thumb);

        }


        /* 昵称 */
        if (isset($fields['user_nickname']) && $fields['user_nickname'] != '') {
            $name  = $fields['user_nickname'];
            $count = mb_strlen($name);
            if ($count > 10) {
                $rs['code'] = 1002;
                $rs['msg']  = \PhalApi\T('昵称最多10个字');
                return $rs;
            }

            $isexist = $model->checkNickname($uid, $name);
            if ($isexist) {
                $rs['code'] = 1003;
                $rs['msg']  = \PhalApi\T('昵称已存在');
                return $rs;
            }

            $data['user_nickname'] = $name;
            $info['user_nickname'] = $name;
        }

        /* 生日 年龄 */
        if (isset($fields['birthday']) && $fields['birthday'] != '') {
            $birthday = strtotime($fields['birthday']);
            $age      = \App\getAge($birthday);

            $data['birthday'] = $birthday;

            $info['birthday'] = $birthday;
            $info['age']      = $age;
        }

        /* 性别 */
        if (isset($fields['sex']) && $fields['sex'] != '') {
            $sex = $fields['sex'];

            $isexist = $model->checkSex($uid);
            if (!$isexist) {
                $data['sex'] = $sex;
                $info['sex'] = $sex;
            }
        }

        /* 签名 */
        if (isset($fields['signature']) && $fields['signature'] != '') {
            $signature = $fields['signature'];

            $data['signature'] = $signature;
            $info['signature'] = $signature;
        }


        /* 学校 */
        if (isset($fields['school']) && $fields['school'] != '') {
            $school = $fields['school'];

            $data['school'] = $school;
            $info['school'] = $school;
        }

        /* 经历 */
        if (isset($fields['experience']) && $fields['experience'] != '') {
            $experience = $fields['experience'];

            $data['experience'] = $experience;
            $info['experience'] = $experience;
        }

        /* 特点 */
        if (isset($fields['feature']) && $fields['feature'] != '') {
            $feature = $fields['feature'];

            $data['feature'] = $feature;
            $info['feature'] = $feature;
        }

        if (!$data) {
            $rs['code'] = 1003;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $result = $model->upUserInfo($uid, $data);

        \App\delcache("userinfo_" . $uid);

        $rs['info'][0] = $info;
        return $rs;
    }


    /* 关注、取关 */
    public function setAttent($uid, $touid)
    {

        $model = new Model_User();

        $isattent = \App\isAttent($uid, $touid);
        if ($isattent) {
            /* 已关注 取消 */
            $model->delAttent($uid, $touid);
            $isattent = '0';
        } else {
            /* 未关注 关注 */
            $model->setAttent($uid, $touid);
            $isattent = '1';
        }

        return $isattent;
    }

    /* 关注列表 */
    public function getFollow($uid, $touid, $p)
    {

        $where = [
            'uid' => $touid
        ];

        $model = new Model_User();
        $list  = $model->getAttention($where, $p);
        foreach ($list as $k => $v) {
            $userinfo = \App\getUserInfo($v['touid']);
            unset($userinfo['birthday']);
            /* $isattent='0';
            if($uid==$touid){
                $isattent='1';
            }else{
                $isattent=\App\isAttent($uid,$v['touid']);
            }
            $userinfo['isattent']=$isattent; */
            $list[$k] = $userinfo;
        }

        return $list;
    }

    /* 所有关注、粉丝用户 */
    public function getAllAttention($where)
    {

        $model = new Model_User();
        $list  = $model->getAllAttention($where);

        return $list;
    }

    /* 粉丝列表 */
    public function getFans($uid, $touid, $p)
    {

        $where = [
            'touid' => $touid
        ];

        $model = new Model_User();
        $list  = $model->getAttention($where, $p);

        foreach ($list as $k => $v) {
            $userinfo = \App\getUserInfo($v['uid']);
            unset($userinfo['birthday']);
            $isattent = \App\isAttent($uid, $v['uid']);

            $userinfo['isattent'] = $isattent;
            $list[$k]             = $userinfo;
        }

        return $list;

    }

    /* 个人主页 */
    public function getHome($uid, $touid)
    {


        $info = \App\getUserInfo($touid);

        $isattent = \App\isAttent($uid, $touid);

        $info['isattent'] = $isattent;

        $info['fans'] = \App\getFansNum($touid);

        return $info;

    }

    /* 根据条件获取用户ID */
    public function getUsers($where)
    {

        $model = new Model_User();
        $list  = $model->getUsers($where);

        return $list;
    }

    /* 根据条件获取单个用户信息 */
    public function getUsersInfo($where)
    {

        $model = new Model_User();
        $info  = $model->getUsersInfo($where);

        return $info;
    }

    /* 检测关系 */
    public function checkAttent($uid, $touid)
    {

        $status = '0';

        $isattent1 = \App\isAttent($uid, $touid);
        if ($isattent1) {
            $status = '1';
        }
        $isattent2 = \App\isAttent($touid, $uid);

        if ($isattent2) {
            $status = '2';
        }

        if ($isattent1 && $isattent2) {
            $status = '3';
        }

        return $status;
    }
}
