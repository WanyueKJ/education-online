<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/12 11:01
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\CourseModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * 课程购买记录
 * @package app\admin\controller
 */
class CoursebuyController extends AdminBaseController
{

    public function index()
    {
        $data = $this->request->param();
        $map  = [];

        $map[] = ['status', '=', 1];

        $courseid = $data['courseid'] ?? '0';
        $map[]    = ['courseid', '=', $courseid];

        $courseinfo = CourseModel::where(['id' => $courseid])->find();
        $this->assign('courseinfo', $courseinfo);

        $start_time = $data['start_time'] ?? '';
        $end_time   = $data['end_time'] ?? '';

        if ($start_time != "") {
            $map[] = ['paytime', '>=', strtotime($start_time)];
        }

        if ($end_time != "") {
            $map[] = ['paytime', '<=', strtotime($end_time) + 60 * 60 * 24];
        }

        $uid = $data['uid'] ?? '';
        if ($uid != '') {
            $map[] = ['uid', '=', $uid];
        }

        $nums  = Db::name('course_users')->where($map)->count();
        $total = Db::name('course_users')->where($map)->sum('money');
        if (!$total) {
            $total = 0;
        }

        $list = Db::name('course_users')->where($map)->order("id desc")->paginate(15, false, ['query' => input()]);
        $list->each(function ($v) {
            $v['userinfo'] = getUserInfo($v['uid']);
            $v['paytime']  = date('Y-m-d H:i:s', $v['paytime']);
            return $v;
        });

        $page = $list->render();
        $this->assign([
            'page'  => $page,
            'list'  => $list,
            'nums'  => $nums,
            'total' => $total
        ]);

        return $this->fetch();
    }

}
