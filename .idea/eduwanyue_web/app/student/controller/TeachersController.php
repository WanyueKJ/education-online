<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/12 11:11
// +----------------------------------------------------------------------
namespace app\student\controller;

use app\admin\model\UsersModel;
use cmf\controller\StudentBaseController;
use think\Db;

/**
 * 名师堂
 */
class TeachersController extends StudentBaseController
{

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        //查找科目
        $kmlist = getSignoryList();

        $classid = 0;
        if ($kmlist) {
            $classid = $kmlist[0]['id'];
        }

        //查看对应的老师
        $where       = 'signoryid = ' . $classid;
        $techerslist = $this->GetTeachers(1, $where);

        $isMore = 0;
        if (count($techerslist) >= 20) {
            $isMore = 1;
        }

        $this->assign([
            'classid'     => $classid,
            'kmlist'      => $kmlist,
            'techerslist' => $techerslist,
            'isMore'      => $isMore,
            'navid'       => 2
        ]);

        return $this->fetch();
    }


    /**
     * 切换科目老师
     */
    public function chooseTeachers()
    {
        $data = $this->request->param();

        if (isset($data['p'])) {
            $p = $data['p'];
        } else {
            $p = 1;
        }
        $classid = $data['id'] ?? 0;

        $where       = 'signoryid = ' . $classid;
        $techerslist = $this->GetTeachers($p, $where);

        $this->success('', '', $techerslist);
    }


    /*
     * 获取教师列表
     * @param $p
     * @param $where
     * @return array
     */
    protected function GetTeachers($p, $where)
    {
        if ($p < 1) {
            $p = 1;
        }

        $nums = 20;

        $start = ($p - 1) * $nums;
        $list  = UsersModel::where('type=1 and user_status!=0 ')
            ->where($where)
            ->order('courses desc,list_order asc')
            ->limit($start, $nums)
            ->select()
            ->toArray();

        $userinfo = session('student');
        foreach ($list as $k => $v) {
            $v = handleUser($v);

            if ($userinfo) {
                $v['isAttent'] = isAttent($userinfo['id'], $v['id']);
            } else {
                $v['isAttent'] = 0;
            }

            $list[$k] = $v;
        }

        return $list;
    }


    /**
     * 老师详情页面
     * @return mixed
     */
    public function detail()
    {
        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student');

        $uid   = $userinfo['id'] ?? 0;
        $token = $userinfo['token'] ?? '';
        $touid = $data['touid'] ?? 0;

        //老师信息
        $url = $this->siteUrl . '/api/?s=Teacher.GetHome&uid=' . $uid . '&token=' . $token . '&touid=' . $touid;

        $info         = curl_get($url);
        $infoDataInfo = $info['data']['info'][0] ?? [];
        $info         = $infoDataInfo;

        //老师课程
        $url      = $this->siteUrl . '/api/?s=Course.GetTeacherCourse&uid=' . $uid . '&token=' . $token . '&touid=' . $touid;
        $lesslist = curl_get($url);
        $lesslist = $lesslist['data']['info'];

        $isMore = 0;
        if (count($lesslist) >= 20) {
            $isMore = 1;
        }

        $this->assign([
            'info'     => $info,
            'lesslist' => $lesslist,
            'isMore'   => $isMore,
            'touid'    => $touid,
            'navid'    => -1
        ]);

        return $this->fetch();
    }

}


