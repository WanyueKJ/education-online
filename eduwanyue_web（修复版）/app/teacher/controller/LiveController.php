<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/21 11:22
// +----------------------------------------------------------------------
namespace app\teacher\controller;

use app\admin\model\CourseModel;
use cmf\controller\TeacherBaseController;
use think\Db;

/**
 * 我的直播
 */
class LiveController extends TeacherBaseController
{

    /* 直播形式 */
    protected function getLiveTypes($k = '')
    {
        $type = [
            '1' => 'PPT',
            '2' => '视频',
            '3' => '音频',
            '5' => '直播',
        ];
        if ($k === '') {
            return $type;
        }
        return isset($type[$k]) ? $type[$k] : '';
    }

    /* 直播状态 */
    protected function getStatus($k = '')
    {
        $type = [
            '0' => '未直播',
            '1' => '直播中',
            '2' => '已结束',
        ];
        if ($k === '') {
            return $type;
        }
        return isset($type[$k]) ? $type[$k] : '';
    }

    /* 直播类型 */
    protected function getSort($k = '')
    {
        $type = [
            '2' => '语音直播',
            '3' => '视频直播',
        ];
        if ($k === '') {
            return $type;
        }
        return isset($type[$k]) ? $type[$k] : '';
    }

    /* 科目分类 */
    protected function getClass()
    {
        $list = Db::name('course_class')
            ->order("list_order asc")
            ->column('*', 'id');
        return $list;
    }

    /* 学级分类 */
    protected function getGrade()
    {
        $list  = Db::name('course_grade')
            ->order("list_order asc")
            ->column('*', 'id');
        $list2 = [];
        foreach ($list as $k => $v) {
            if ($v['pid'] != 0) {
                $name      = $list[$v['pid']]['name'] . ' - ' . $v['name'];
                $v['name'] = $name;

                $list2[$k] = $v;
            }
        }
        return $list2;
    }

    public function index()
    {
        $cur = 'live';
        $this->assign('cur', $cur);

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }
        $this->uid = $uid;

        $data = $this->request->param();
        $map  = [];

        $map[] = ['sort', '>=', 2];
        $map[] = ['uid|tutoruid', '=', $uid];

        $starttime = isset($data['starttime']) ? $data['starttime'] : '';

        if ($starttime != "") {
            $map[] = ['starttime', '>=', strtotime($starttime)];
            $map[] = ['starttime', '<=', strtotime($starttime) + 60 * 60 * 24];
        }

        $islive = isset($data['islive']) ? $data['islive'] : '';
        if ($islive != '') {
            $map[] = ['islive', '=', $islive];
        }

        $type = isset($data['type']) ? $data['type'] : '';
        if ($type != '') {
            $map[] = ['type', '=', $type];
        }


        $keyword = isset($data['keyword']) ? $data['keyword'] : '';
        if ($keyword != '') {
            $map[] = ['name', 'like', '%' . $keyword . '%'];
        }


        $list = CourseModel::where($map)->order("id desc")->paginate(20);

        $list->each(function ($v, $k) {

            $v['thumb']     = get_upload_path($v['thumb']);
            $v['live_time'] = date('Y-m-d H:i:s', $v['starttime']);

            $paytype = $v['paytype'];
            $pay_val = '免费';

            if ($paytype == 1) {
                $pay_val = '￥ ' . $v['payval'];
            }

            if ($paytype == 2) {
                $pay_val = '密码';
            }
            $v['pay_val'] = $pay_val;

            $v['type_s'] = $this->getLiveTypes($v['type']);
            $v['live_s'] = $this->getStatus($v['islive']);
            $v['sort_s'] = $this->getSort($v['sort']);

            $user_type = '';
            if ($v['uid'] == $this->uid) {
                $user_type = '主讲老师';
            }

            if ($v['tutoruid'] == $this->uid) {
                $user_type = '辅导老师';
            }
            $v['user_type'] = $user_type;

            return $v;
        });

        $list->appends($data);
        // 获取分页显示
        $page = $list->render();

        $this->assign([
            'list'   => $list,
            'page'   => $page,
            'type'   => $this->getLiveTypes(),
            'islive' => $this->getStatus(),
            'classs' => $this->getClass(),
            'grade'  => $this->getGrade()
        ]);

        return $this->fetch();
    }
}


