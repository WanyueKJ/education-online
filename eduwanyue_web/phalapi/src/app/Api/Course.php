<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/01 09:22
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\Course as Domain_Course;

header("Access-Control-Allow-Origin: *");

/**
 * 课程
 */
class Course extends Api
{

    public function getRules()
    {
        return array(
            'getClassCourse' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'classid' => array('name' => 'classid', 'type' => 'int', 'desc' => '科目分类ID'),
                'p'       => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'getList' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'type'    => array('name' => 'type', 'type' => 'int', 'desc' => '类型，0精选内容1好课推荐2直播课堂'),
                'p'       => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'search' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'desc' => '关键词'),
                'p'       => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'getDetail' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
            ),

            'checkPass' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'pass'     => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
            ),

            'buy' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'payid'    => array('name' => 'payid', 'type' => 'int', 'desc' => '支付方式ID'),
            ),

            'getLessonList' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
            ),

            'getMyCourse' => array(
                'type'    => array('name' => 'type', 'type' => 'int', 'desc' => '类型0全部1未开始2学习中3已学完'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'desc' => '关键词'),
                'p'       => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'getTeacherCourse' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'touid'   => array('name' => 'touid', 'type' => 'int', 'desc' => '讲师ID'),
                'p'       => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'getMyBuy' => array(
                'sort' => array('name' => 'sort', 'type' => 'int', 'desc' => '0内容 2直播'),
                'p'    => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),

            'setGrade' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
            ),
            'setLesson' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
            ),
            'getNewsDetail' => array(
                'newsid' => array('name' => 'newsid', 'type' => 'int', 'desc' => '新闻id'),
            ),

        );
    }

    /**
     * 科目分类
     * @desc 用于获取科目分类
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].id
     * @return string info[].name 名称
     * @return string info[].thumb  图标
     * @return string msg 提示信息
     */
    public function getClass()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Course();
        $list   = $domain->getClass();

        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 科目分类下课程
     * @desc 用于获取某科目分类下课程
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].id 课程ID
     * @return string info[].uid 教师ID
     * @return string info[].user_nickname 教师昵称
     * @return string info[].avatar 教师头像
     * @return string info[].sort 类别，0内容1课程2直播
     * @return string info[].type 形式，1图文2视频3音频
     * @return string info[].name 名称
     * @return string info[].thumb 封面
     * @return string info[].paytype 获取形式，0免费1收费2密码
     * @return string info[].payval 价格位置显示内容，根据paytype区分颜色
     * @return string info[].lesson 课时位置显示内容
     * @return string info[].islive 是否在直播，0否1是
     * @return string msg 提示信息
     */
    public function getClassCourse()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $gradeid = \App\checkNull($this->gradeid);
        $classid = \App\checkNull($this->classid);
        $p       = \App\checkNull($this->p);
        if ($classid < 1) {
            return $rs;
        }

        $nowtime = time();
        $where = [
            'status>=?'     => 1,
            'shelvestime<?' => $nowtime,
            'classid'       => $classid,
            'gradeid'       => $gradeid,
            'sort != ?'     => 1
        ];

        $domain = new Domain_Course();
        $list   = $domain->getList($p, $where);
        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 课程列表
     * @desc 用于课程列表
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].id 昵称
     * @return string info[].user_nickname 昵称
     * @return string info[].avatar 头像
     * @return string info[].sex 性别
     * @return string info[].age 年龄
     * @return string msg 提示信息
     */
    public function getList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid     = \App\checkNull($this->uid);
        $type    = \App\checkNull($this->type);
        $gradeid = \App\checkNull($this->gradeid);
        $p       = \App\checkNull($this->p);

        if (!$type) {
            $type = 0;
        }

        $nowtime = time();

        $where = [
            'gradeid'       => $gradeid,
            'status>=?'     => 1,
            'shelvestime<?' => $nowtime,
        ];
        if ($type >= 2) {
            $where['sort>=?'] = 2;
        } else {
            $where['sort'] = $type;
        }

        $domain = new Domain_Course();
        $list   = $domain->getList($p, $where);

        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 搜索课程
     * @desc 用于首页搜索课程
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].id 昵称
     * @return string info[].user_nickname 昵称
     * @return string info[].avatar 头像
     * @return string info[].sex 性别
     * @return string info[].age 年龄
     * @return string msg 提示信息
     */
    public function search()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid     = \App\checkNull($this->uid);
        $gradeid = \App\checkNull($this->gradeid);
        $keyword = \App\checkNull($this->keyword);
        $p       = \App\checkNull($this->p);

        if ($keyword == '') {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('请输入关键词');
            return $rs;
        }

        $nowtime = time();

        $where = [
            'gradeid'       => $gradeid,
            'status>=?'     => 1,
            'shelvestime<?' => $nowtime,
            'uid!=?'        => $uid,
            'name like ?'   => '%' . $keyword . '%',
        ];

        $domain = new Domain_Course();
        $list   = $domain->getList($p, $where);

        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 课程详情
     * @desc 用于课程详情
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].paytype 获取形式，0免费1收费2密码
     * @return string info[].ifbuy 是否购买，0否1是
     * @return string info[].views 学习人数
     * @return string info[].des 简介
     * @return string info[].trialtype 试学方式，0无1链接2时间进度
     * @return string info[].trialval  trialtype=1是链接，trialtype=2是时间秒数
     * @return string info[].url 视频/语音链接
     * @return string info[].add_time 时间日期
     * @return string info[].star 课程评级星级
     * @return string info[].islive 是否直播0否1是
     * @return string info[].intr 听课指南
     * @return object info[].userinfo 讲师信息
     * @return string info[].ispack 是否有套餐0否1是
     * @return string info[].iscart 是否加入购物车0否1是
     * @return array  info[].tutor 辅导老师列表
     * @return string info[].tutor[] 老师信息
     * @return string msg 提示信息
     */
    public function getDetail()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid      = \App\checkNull($this->uid);
        $token    = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if ($courseid < 1) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $nowtime = time();
        $where   = [
            'status>?'      => 0,
            'shelvestime<?' => $nowtime,
            'id'            => $courseid
        ];

        $domain = new Domain_Course();
        $res    = $domain->getDetail($uid, $where);

        return $res;
    }

    /**
     * 确认密码
     * @desc 用于输入密码后进行确认
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string msg 提示信息
     */
    public function checkPass()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid      = \App\checkNull($this->uid);
        $token    = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        $pass     = \App\checkNull($this->pass);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if ($courseid < 1) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        if ($pass == '') {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('请输入密码');
            return $rs;
        }


        $domain = new Domain_Course();
        $res    = $domain->checkPass($uid, $pass, $courseid);

        return $res;
    }

    /**
     * 课时列表
     * @desc 用于获取课时列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[].name 名称
     * @return array  info[].list 课时列表
     * @return string info[].list[].name 名称
     * @return string info[].list[].type 形式，1图文2视频3音频4ppt直播5视频直播6音频直播7授课直播（白板）
     * @return string info[].list[].istrial 是否试学，0否1是
     * @return string info[].list[].url 音频链接/视频链接/ 当type=4 url!=''  为回访
     * @return string info[].list[].status 状态 0正常 1试学2已学完3正在直播4锁
     * @return string info[].list[].islive 是否在直播 0否1是2已结束
     * @return string info[].list[].isenter 是否可看 0否1是
     * @return string info[].list[].islast 是否上次学到 0否1是
     * @return string info[].list[].time_date 开播时间
     * @return string info[].list[].uid 讲师ID
     * @return string info[].list[].courseid 课程ID
     * @return string msg 提示信息
     */
    public function getLessonList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid      = \App\checkNull($this->uid);
        $token    = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);

        if ($uid < 1 || $courseid < 1) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Course();
        $list   = $domain->getLessonList($uid, $courseid);

        $rs['info'] = $list;

        return $rs;
    }


    /**
     * 已购买的课程
     * @desc 用于个人中心获取已购买的课程
     * @return int code 操作码，0表示成功
     * @return array info 同其他课程列表
     * @return string info[].payval
     * @return string msg 提示信息
     */
    public function getMyBuy()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $sort  = $this->sort;
        $p     = \App\checkNull($this->p);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Course();
        $list   = $domain->getMyBuy($uid, $sort, $p);

        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 某讲师课程
     * @desc 用于获取某讲师课程列表
     * @return int code 操作码，0表示成功
     * @return array info 同其他课程列表
     * @return string msg 提示信息
     */
    public function getTeacherCourse()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid     = \App\checkNull($this->uid);
        $token   = \App\checkNull($this->token);
        $gradeid = \App\checkNull($this->gradeid);
        $touid   = \App\checkNull($this->touid);
        $p       = \App\checkNull($this->p);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $nowtime = time();
        $where   = [
            'status>=?'     => 1,
            'shelvestime<?' => $nowtime,
            'uid'           => $touid,
        ];

        $domain = new Domain_Course();
        $list   = $domain->getList($p, $where);

        $rs['info'] = $list;

        return $rs;
    }

    /**
     * 获取默认学级分类
     * @desc 用于游客模式获取默认学级分类
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string info[0].id
     * @return string info[0].name 名称
     * @return string msg 提示信息
     */
    public function getGradeDef()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Course();
        $res    = $domain->getGradeDef();

        return $res;
    }

    /**
     * 学级分类
     * @desc 用于获取课程学级分类
     * @return int code 操作码，0表示成功
     * @return array  info 列表
     * @return string info[].id
     * @return string info[].name 名称
     * @return array  info[].list 二级分类
     * @return string info[].list[].id 二级分类ID
     * @return string info[].list[].name 二级分类名称
     * @return string msg 提示信息
     */
    public function getGrade()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Course();
        $list   = $domain->getGrade();

        $rs['info'] = $list;

        return $rs;
    }


    /**
     * 设置学级分类
     * @desc 用于设置学级分类
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
    public function setGrade()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid     = \App\checkNull($this->uid);
        $token   = \App\checkNull($this->token);
        $gradeid = \App\checkNull($this->gradeid);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Course();
        $res    = $domain->setGrade($uid, $gradeid);

        return $res;
    }

    /**
     * 我的课程
     * @desc 用于获取我的课程
     * @return int code 操作码，0表示成功
     * @return array info 同其他课程列表
     * @return string info[].payval
     * @return string msg 提示信息
     */
    public function getMyCourse() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $type=\App\checkNull($this->type);
        $keyword=\App\checkNull($this->keyword);
        $p=\App\checkNull($this->p);

        $checkToken=\App\checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }


        $domain = new Domain_Course();
        $list = $domain->getMyCourse($uid,$type,$keyword,$p);

        $rs['info']=$list;

        return $rs;
    }


    /**
     * 更新课时学习进度(UNIAPP端使用)
     * @desc 用户更新课时进度
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息
     */
    public function setLesson() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = \App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        $lessonid = \App\checkNull($this->lessonid);

        $checkToken=\App\checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Course();
        $res = $domain->setLesson($uid, $courseid, $lessonid);

        return $res;
    }

    /*
 * 获取新闻列表
 * @desc 用于获取新闻列表
 * @return int code 操作码 0 表示成功
 * @return array 新闻列表
 */
    public function getNews() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $domain = new Domain_Course();
        $list   = $domain->getNews();

        $rs['info'] = $list;

        return $rs;

    }


    /**
     * 获取新闻详情
     * @desc 用于获取新闻详情
     * @return int code 操作码 0 表示成功
     * @return array 新闻详情
     */
    public function getNewsDetail() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid      = \App\checkNull($this->uid);
        $token    = \App\checkNull($this->token);
        $newsid = \App\checkNull($this->newsid);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Course();
        $rs['info']    = $domain->getNewsDetail($newsid);

        return $rs;

    }



}
