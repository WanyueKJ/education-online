<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/09 08:22
// +----------------------------------------------------------------------
namespace cmf\controller;

use app\admin\model\CourseModel;
use app\admin\model\UsersModel;
use think\Db;

class StudentBaseController extends HomeBaseController
{

    protected $siteUrl;

    public function initialize()
    {
        parent::initialize();
        $siteInfo      = cmf_get_site_info();
        $this->siteUrl = $siteInfo['site_url'];
        $this->checkUserLogin();
        $this->checkChooseGrade();
    }

    public function checkUserLogin()
    {
        $userId = cmf_get_current_user_id();

        $userId = session('student.id') ?? 0;

        $userinfo = Db::name('users')->where(['id' => $userId])->find();
        if (!$userinfo) {
            session('student', null);

            $isLog    = 0;
            $userinfo = array();

        } else {
            if ($userinfo['user_status'] == 0) {
                session('student', null);

                $this->error("该账号已被禁用", "/student");
                return !1;
            }

            $gradeinfo = Db::name('course_grade')->where(['id' => $userinfo['gradeid']])->find();

            if ($gradeinfo) {
                $gradename = $gradeinfo['name'];
            } else {
                $gradename = '';
            }

            $userinfo['gradename'] = $gradename;

            $isLog = 1;

            $userinfo_s = session('student') ?? [];

            $userinfo   = array_merge($userinfo_s, $userinfo);
            $userinfo   = handleUser($userinfo);

            unset($userinfo['create_time']);
            unset($userinfo['last_login_time']);
            unset($userinfo['user_status']);
            unset($userinfo['user_pass']);
            unset($userinfo['last_login_ip']);
            unset($userinfo['openid']);
            unset($userinfo['source']);
        }

        $this->assign('keywords','');
        $this->assign('isLog',$isLog);
        $this->assign('userinfo',$userinfo);
        $this->assign('userinfoj',json_encode($userinfo));

    }

    //检测有没有选择年级
    public function checkChooseGrade()
    {

        $ip = get_client_ip();

        $ipinfo = Db::name('ip_garde')->where(['ip' => $ip])->find();

        $userinfo = session('student');
        if (!$userinfo) {
            $userinfo = $_SESSION['student'] ?? [];
        }

        if ($ipinfo) {
            $isIp    = 1;
            $gradeid = $ipinfo['gardeid'];
        } else {
            $isIp    = 0;
            $gradeid = 0;
        }

        //查询学级
        $list = Db::name('course_grade')->order('list_order asc')->select()->toArray();

        foreach ($list as $k => $v) {
            unset($v['list_order']);
            $list[$k] = $v;
        }

        $list = $this->handelGrade($list);

        $this->assign([
            'list'    => $list,
            'isIp'    => $isIp,
            'gradeid' => $gradeid
        ]);

    }


    public function handelGrade($list = [], $pid = 0)
    {
        $rs = [];

        foreach ($list as $k => $v) {
            if ($v['pid'] == $pid) {
                unset($list[$k]);
                $v['list'] = $this->handelGrade($list, $v['id']);
                $rs[]      = $v;
            }
        }

        return $rs;
    }

    //我的页面检测是否登录
    protected function checkMyLogin()
    {

        $userinfo = session('student') ?? '';
        if (!$userinfo) {
            $this->redirect(cmf_url("/?isBackLog=" . time()));
        }

        $isToken = checkToken($userinfo['id'], $userinfo['token']);
        if ($isToken != 0) {
            $this->redirect(cmf_url("/?isBackLog=" . time()));
        }
    }


    //上课页面检测是否登录和购买课程
    protected function checkEnterLive($courseid, $lessonid)
    {

        //检测登录
        $userinfo = session('student') ?? '';
        if (!$userinfo) {
            $this->redirect(cmf_url("/?isBackLog=" . time()));
        }

        $isToken = checkToken($userinfo['id'], $userinfo['token']);
        if ($isToken != 0) {
            $this->redirect(cmf_url("/?isBackLog=" . time()));
        }

        $courseinfo = CourseModel::field('uid,paytype,mode')->where(['id' => $courseid])->find();
        if (!$courseinfo) {
            $this->error('信息错误');
        }

        if ($userinfo['id'] == $courseinfo['uid']) {
            $this->error('你不能进自己的直播间');
        }
        $where  = [
            'uid'      => $userinfo['id'],
            'liveuid'  => $courseinfo['uid'],
            'courseid' => $courseid,
            'lessonid' => $lessonid,
        ];
        $iskick = Db::name('live_kick')->where($where)->find();
        if ($iskick) {
            $this->error('你已被踢出');
        }

        $courseviews = Db::name('course_views')->field('id')->where(['uid' => $userinfo['id'], 'courseid' => $courseid])->select()->toArray();

        if ($courseinfo['paytype'] != 0 && $lessonid == 0) {
            $payinfo = Db::name('course_users')
                ->field('id')
                ->where(['courseid' => $courseid, 'uid' => $userinfo['id'], 'status' => 1])
                ->find();
            if (!$payinfo) {
                $this->error('非法访问');
            }
        }
        //检测是否购买课程
        if ($lessonid != 0) {

            $lessinfo = Db::name("course_lesson")->where(['id' => $lessonid, 'courseid' => $courseid])->find();
            if (!$lessinfo) {
                $this->error('信息错误');
            }

            $lesslist = Db::name("course_lesson")->where(['courseid' => $courseid])->order('list_order asc')->select()->toArray();
            $rs       = $this->handelLesson($lesslist, 0);
            $num      = 0;
            $nums     = 0;
            $isenter  = 1;
            $islive   = 1;
            foreach ($rs as $k => $v) {
                foreach ($v['list'] as $k1 => $v1) {
                    if ($courseinfo['paytype'] != 0 && $v1['id'] == $lessonid) {
                        $payinfo = Db::name('course_users')
                            ->field('id')
                            ->where(['courseid' => $courseid, 'uid' => $userinfo['id'], 'status' => 1])
                            ->find();
                        if (!$payinfo && $v1['istrial'] == 0) {
                            $this->error('非法访问');
                        }
                    }

                    if ($courseinfo['mode'] == 1 && $num > count($courseviews) && $v1['id'] == $lessonid) {
                        /* 待解锁 不能进 */
                        $isenter = 0;
                    }

                    if ($v1['type'] >= 4 && $v1['islive'] == 0 && $v1['id'] == $lessonid) {
                        /* 未直播  不能进 */
                        $islive = 0;
                    }

                    $num++;
                }
                $list[$k] = $v;
            }

            if ($isenter == 0) {
                $this->error('待解锁还不能观看');
            }

            if ($islive == 0) {
                $this->error('暂时无法进入');
            }

        }
    }

    /* 处理课时数组 */
    protected function handelLesson($list = [], $pid = 0)
    {
        $rs = [];
        foreach ($list as $k => $v) {
            if ($v['pid'] == $pid) {
                unset($list[$k]);
                $v['list'] = $this->handelLesson($list, $v['id']);
                $rs[]      = $v;
            }
        }

        return $rs;
    }
}