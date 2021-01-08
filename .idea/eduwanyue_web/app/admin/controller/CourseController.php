<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/15 09:08
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\CourseModel;
use app\admin\model\UsersModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * 课程
 * @package app\admin\controller
 */
class CourseController extends AdminBaseController
{
    /**
     * 获取课程状态
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getStatus($k = '')
    {
        $status = [
            '-2' => '管理员下架',
            '-1' => '暂时下架',
            '0'  => '审核中',
            '1'  => '上架中',
            '2'  => '定时上架',
        ];

        if ($k === '') {
            return $status;
        }
        return isset($status[$k]) ? $status[$k] : '';
    }

    /**
     * 类别
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getSort($k = '')
    {
        $sort = [
            '0' => '内容',
            '1' => '课程',
            '2' => '直播',
        ];
        if ($k === '') {
            return $sort;
        }
        return isset($sort[$k]) ? $sort[$k] : '';
    }

    /**
     * 内容形式
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getTypes($k = '')
    {
        $type = [
            '1' => '图文',
        ];
        if ($k === '') {
            return $type;
        }
        return isset($type[$k]) ? $type[$k] : '';
    }

    /**
     * 直播形式
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getLiveTypes($k = '')
    {
        $type = [
            '1' => 'PPT',
            '2' => '视频',
            '3' => '音频',
        ];
        if ($k === '') {
            return $type;
        }
        return isset($type[$k]) ? $type[$k] : '';
    }

    /**
     * 获取方式
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getPayTypes($k = '')
    {
        $paytype = [
            '0' => '免费',
            '1' => '收费',
            '2' => '密码',
        ];
        if ($k === '') {
            return $paytype;
        }
        return isset($paytype[$k]) ? $paytype[$k] : '';
    }

    /**
     * 试学
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getTrialTypes($k = '')
    {
        $trialtype = [
            '0' => '否',
            '2' => '进度',
        ];
        if ($k === '') {
            return $trialtype;
        }
        return isset($trialtype[$k]) ? $trialtype[$k] : '';
    }

    /**
     * 课程模式
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getModes($k = '')
    {
        $mode = [
            '0' => '自由',
            '1' => '解锁',
        ];
        if ($k === '') {
            return $mode;
        }
        return isset($mode[$k]) ? $mode[$k] : '';
    }

    /**
     * 学级分类
     * @return array
     */
    protected function getGrade()
    {
        $list  = Db::name('course_grade')
            ->order("pid asc,list_order asc")
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

    /**
     * 学级分类2 排序
     * @return array
     */
    protected function getGradeTwo()
    {
        $list  = Db::name('course_grade')
            ->order("pid asc,list_order asc")
            ->column('*', 'id');
        $list2 = [];

        foreach ($list as $k => $v) {
            if ($v['pid'] == 0) {
                foreach ($list as $k1 => $v1) {
                    if ($v1['pid'] == $v['id']) {
                        $name       = $v['name'] . ' - ' . $v1['name'];
                        $v1['name'] = $name;

                        $list2[] = $v1;

                        unset($list[$k1]);
                    }
                }
                unset($list[$k]);
            }
        }
        return $list2;
    }

    /*
     * 课程列表
     * @return mixed
     */
    public function index()
    {
        $data = $this->request->param();
        $map  = [];

        $sort  = $data['sort'] ?? '1';
        $map[] = ['sort', '=', $sort];

        $start_time = $data['start_time'] ?? '';
        $end_time   = $data['end_time'] ?? '';

        if ($start_time != "") {
            $map[] = ['addtime', '>=', strtotime($start_time)];
        }

        if ($end_time != "") {
            $map[] = ['addtime', '<=', strtotime($end_time) + 60 * 60 * 24];
        }

        $status = $data['status'] ?? '';
        if ($status != '') {
            $map[] = ['status', '=', $status];
        }

        $gradeid = $data['gradeid'] ?? '';
        if ($gradeid != '') {
            $map[] = ['gradeid', '=', $gradeid];
        }

        $paytype = $data['paytype'] ?? '';
        if ($paytype != '') {
            $map[] = ['paytype', '=', $paytype];
        }

        $type = $data['type'] ?? '';
        if ($type != '') {
            $map[] = ['type', '=', $type];
        }

        $keyword = $data['keyword'] ?? '';
        if ($keyword != '') {
            $map[] = ['name', 'like', '%' . $keyword . '%'];
        }

        $uid = $data['uid'] ?? '';
        if ($uid != '') {
            $map[] = ['uid', '=', $uid];
        }

        $list = CourseModel::where($map)->order("id desc")->paginate(20, false, ['query' => input()]);

        $list->each(function ($v, $k) {

            $v['userinfo'] = getUserInfo($v['uid']);
            $v['thumb']    = get_upload_path($v['thumb']);
            $nowtime       = time();
            if ($v['status'] > 0) {
                if ($v['shelvestime'] < $nowtime) {
                    $v['status'] = 1;
                }
            }

            return $v;
        });

        $list->appends($data);
        // 获取分页显示
        $page = $list->render();

        if ($sort == 2) {
            $this->assign('types', $this->getLiveTypes());
        } else {
            $this->assign('types', $this->getTypes());
        }

        $this->assign([
            'list'       => $list,
            'page'       => $page,
            'sort'       => $sort,
            'status'     => $this->getStatus(),
            'grade'      => $this->getGrade(),
            'paytypes'   => $this->getPayTypes(),
            'trialtypes' => $this->getTrialTypes(),
            'livetype'   => $this->getLiveTypes()

        ]);

        // 渲染模板输出
        return $this->fetch('index');
    }

    /*
     * 内容
     * @return mixed
     */
    function contents()
    {
        return $this->index();
    }

    /**
     * 视频大班课
     * @return mixed
     */
    function live()
    {
        return $this->index();
    }

    /**
     * 语音大班课
     * @return mixed
     */
    function live_video()
    {
        return $this->index();
    }

    /*
     * 更新status
     */
    function setstatus()
    {
        $id     = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');

        $nowtime = time();
        $data    = ['status' => $status];
        if ($status == 1) {
            $info = CourseModel::where(['id' => $id])->find();
            if ($info) {
                if ($info['shelvestime'] > $nowtime) {
                    $data['shelvestime'] = $nowtime;
                }
            }
        }

        $rs = CourseModel::where(['id' => $id])->update(['status' => $status]);
        if (!$rs) {
            $this->error("操作失败！");
        }

        $this->success("操作成功！");

    }

    /*
     * 获取用户详情
     * @param int $uid
     * @return array
     */
    protected function getUser($uid = 0)
    {
        $map   = [];
        $map[] = ['type', '=', 1];
        if ($uid != 0) {
            $map[] = ['id', '=', $uid];
        }

        $list = UsersModel::field('id,user_nickname')->where($map)->select()->toArray();

        return $list;
    }

    /**
     * 获取用户列表
     */
    public function getUserList()
    {
        $data = $this->request->param();
        $uid  = $data['uid'] ?? '0';
        $list = $this->getUser($uid);
        $this->success('', '', $list);
    }

    /* 科目分类 */
    protected function getClass(){
        $list = Db::name('course_class')
            ->order("list_order asc")
            ->column('*','id');
        return $list;
    }

    /**
     * 课程添加
     * @return mixed
     */
    public function add()
    {
        $data = $this->request->param();

        $sort = $data['sort'] ?? '1';
        $this->assign('sort', $sort);

        if ($sort == 2) {
            $this->assign('types', $this->getLiveTypes());
        } else {
            $this->assign('types', $this->getTypes());
        }

        $this->assign([
            'grade'      => $this->getGradeTwo(),
            'paytypes'   => $this->getPayTypes(),
            'trialtypes' => $this->getTrialTypes(),
            'modes'      => $this->getModes()
        ]);

        return $this->fetch();
    }


    /*
     * 课程添加提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            $sort = $data['sort'];
            $uid  = $data['uid'];
            if ($uid == '') {
                $this->error('请填写主讲老师ID');
            }
            $isexist = UsersModel::field('type')->where('id', '=', $uid)->find();
            if (!$isexist) {
                $this->error('该主讲老师不存在');
            }
            if ($isexist['type'] != 1) {
                $this->error('该ID还不是老师');
            }

            if ($sort >= 1) {
                $tutoruid = $data['tutoruid'];
                if ($tutoruid > 0) {
                    if ($tutoruid == $uid) {
                        $this->error('主讲老师和辅导老师不能是同一个人');
                    }

                    $isexist = UsersModel::field('type')->where('id', '=', $tutoruid)->find();
                    if (!$isexist) {
                        $this->error('该辅导老师不存在');
                    }
                    if ($isexist['type'] != 1) {
                        $this->error('该ID还不是老师');
                    }
                } else {
                    $data['tutoruid'] = 0;
                }
            }

            $name = $data['name'];
            if ($name == '') {
                $this->error('请填写名称');
            }

            $thumb = $data['thumb'];
            if ($thumb == '') {
                $this->error('请上传封面');
            }

            $paytype = $data['paytype'];
            $payval  = $data['payval'];
            if ($paytype == 0) {
                unset($data['payval']);
                unset($data['trialtype']);
            }
            if ($paytype == 1) {
                if ($payval == '') {
                    $this->error('请输入价格');
                }
                if ($payval < 0.01) {
                    $this->error('请输入正确的价格');
                }
            }

            if ($paytype == 2) {
                if ($payval == '') {
                    $this->error('请输入密码');
                }

            }

            if ($sort == 3) {
                $data['type'] = 5;
            } else if ($sort != 1) {
                $trialtype = $data['trialtype'] ?? 0;
                /* 内容类 */
                $type = $data['type'];
                if ($type == 1) {
                    /* 图文 */
                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_1 = $data['trialval_1'];
                        if ($trialval_1 == '') {
                            $this->error('请填写进度比例');
                        }
                        if ($trialval_1 <= 0 || $trialval_1 >= 100) {
                            $this->error('请填写正确的比例');
                        }
                        $data['trialval'] = $trialval_1;

                    }
                }

                if ($type == 2) {
                    /* 视频 */
                    $type_video = $data['type_video'];
                    if ($type_video == '') {
                        $this->error('请上传视频');
                    }
                    $data['url'] = $type_video;

                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_2 = $data['trialval_2'];
                        if ($trialval_2 == '') {
                            $this->error('请填写进度时长');
                        }
                        if ($trialval_2 <= 0) {
                            $this->error('请填写正确的时长');
                        }
                        $data['trialval'] = $trialval_2;

                    }

                }

                if ($type == 3) {
                    /* 音频 */
                    $type_audio = $data['type_audio'];
                    if ($type_audio == '') {
                        $this->error('请上传音频');
                    }
                    $data['url'] = $type_audio;

                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_2 = $data['trialval_2'];
                        if ($trialval_2 == '') {
                            $this->error('请填写进度时长');
                        }
                        if ($trialval_2 <= 0) {
                            $this->error('请填写正确的时长');
                        }
                        $data['trialval'] = $trialval_2;

                    }

                }

            }

            $nowtime     = time();
            $status      = $data['status'];
            $shelvestime = $data['shelvestime'];
            unset($data['shelvestime']);
            if ($status == 2) {
                if ($shelvestime == '') {
                    $this->error('请填写上架时间');
                }

                $data['shelvestime'] = strtotime($shelvestime);
            } else {
                $data['shelvestime'] = $nowtime;
            }

            if ($sort == 2 || $sort == 3) {
                $starttime = $data['starttime'];
                if ($starttime == '') {
                    $this->error('请填写上课时间');
                }
                $data['starttime'] = strtotime($starttime);

                $endtime = $data['endtime'];
                if ($endtime == '') {
                    $this->error('请填写下课时间');
                }
                $data['endtime'] = strtotime($endtime);

                if ($data['starttime'] >= $data['endtime']) {
                    $this->error('下课时间不能早于上课时间');
                }
            }

            $info = isset($data['info']) ? $data['info'] : '';
            if ($info == '') {
                $this->error('请编辑介绍');
            }

            if ($sort == 0) {
                $content = isset($data['content']) ? $data['content'] : '';
                if ($content == '') {
                    $this->error('请编辑内容');
                }
            }
            /* 去除无用字段 */
            unset($data['type_video']);
            unset($data['type_audio']);
            unset($data['trialval_1']);
            unset($data['trialval_2']);

            $data['addtime'] = $nowtime;

            $id = CourseModel::create($data, true);
            if (!$id) {
                $this->error("添加失败！");
            }

            $this->success("添加成功！", url("course/index", ['sort' => $sort]));
        }
    }

    /*
     * 课程编辑
     * @return mixed
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        $data = CourseModel::where("id={$id}")->find();
        if (!$data) {
            $this->error("信息错误");
        }

        $data['userinfo']=getUserInfo($data['uid']);
        if($data['tutoruid']){
            $data['tutorinfo']=getUserInfo($data['tutoruid']);
        }

        $this->assign('data', $data);

        $sort = $data['sort'];

        if ($sort == 2) {
            $this->assign('types', $this->getLiveTypes());
        } else {
            $this->assign('types', $this->getTypes());
        }

        $this->assign('classs', $this->getClass());

        $this->assign([
            'sort'       => $sort,
            'grade'      => $this->getGradeTwo(),
            'paytypes'   => $this->getPayTypes(),
            'trialtypes' => $this->getTrialTypes(),
            'modes'      => $this->getModes()
        ]);

        return $this->fetch();
    }

    /*
     * 课程编辑提交
     */
    public function editPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            $sort = $data['sort'];
            $uid  = $data['uid'];
            if ($uid == '') {
                $this->error('请填写主讲老师ID');
            }
            $isexist = UsersModel::field('type')->where('id', '=', $uid)->find();
            if (!$isexist) {
                $this->error('该主讲老师不存在');
            }
            if ($isexist['type'] != 1) {
                $this->error('该ID还不是老师');
            }

            if ($sort >= 1) {
                $tutoruid = $data['tutoruid'];
                if ($tutoruid > 0) {
                    if ($tutoruid == $uid) {
                        $this->error('主讲老师和辅导老师不能是同一个人');
                    }

                    $isexist = UsersModel::field('type')->where('id', '=', $tutoruid)->find();
                    if (!$isexist) {
                        $this->error('该辅导老师不存在');
                    }
                    if ($isexist['type'] != 1) {
                        $this->error('该ID还不是老师');
                    }
                } else {
                    $data['tutoruid'] = 0;
                }
            }

            $name = $data['name'];
            if ($name == '') {
                $this->error('请填写名称');
            }

            $thumb = $data['thumb'];
            if ($thumb == '') {
                $this->error('请上传封面');
            }

            $paytype = $data['paytype'];
            $payval  = $data['payval'];
            if ($paytype == 0) {
                unset($data['payval']);
                unset($data['trialtype']);
            }
            if ($paytype == 1) {
                if ($payval == '') {
                    $this->error('请输入价格');
                }
                if ($payval < 0.01) {
                    $this->error('请输入正确的价格');
                }

            }

            if ($paytype == 2) {
                if ($payval == '') {
                    $this->error('请输入密码');
                }

            }

            if ($sort == 3) {
                $data['type'] = 5;
            } else if ($sort != 1) {
                $trialtype = isset($data['trialtype']) ? $data['trialtype'] : 0;
                /* 内容类 */
                $type = $data['type'];
                if ($type == 1) {
                    /* 图文 */
                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_1 = $data['trialval_1'];
                        if ($trialval_1 == '') {
                            $this->error('请填写进度比例');
                        }
                        if ($trialval_1 <= 0 || $trialval_1 >= 100) {
                            $this->error('请填写正确的比例');
                        }
                        $data['trialval'] = $trialval_1;

                    }
                }

                if ($type == 2) {
                    /* 视频 */
                    $type_video = $data['type_video'];
                    if ($type_video == '') {
                        $this->error('请上传视频');
                    }
                    $data['url'] = $type_video;

                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_2 = $data['trialval_2'];
                        if ($trialval_2 == '') {
                            $this->error('请填写进度时长');
                        }
                        if ($trialval_2 <= 0) {
                            $this->error('请填写正确的时长');
                        }
                        $data['trialval'] = $trialval_2;

                    }

                }

                if ($type == 3) {
                    /* 音频 */
                    $type_audio = $data['type_audio'];
                    if ($type_audio == '') {
                        $this->error('请上传音频');
                    }
                    $data['url'] = $type_audio;

                    if ($sort == 0 && $trialtype == 2) {
                        $trialval_2 = $data['trialval_2'];
                        if ($trialval_2 == '') {
                            $this->error('请填写进度时长');
                        }
                        if ($trialval_2 <= 0) {
                            $this->error('请填写正确的时长');
                        }
                        $data['trialval'] = $trialval_2;

                    }

                }

            }

            $nowtime     = time();
            $status      = $data['status'];
            $shelvestime = $data['shelvestime'];
            unset($data['shelvestime']);
            if ($status == 2) {
                if ($shelvestime == '') {
                    $this->error('请填写上架时间');
                }

                $data['shelvestime'] = strtotime($shelvestime);
            }

            if ($sort == 2 || $sort == 3) {
                $starttime = $data['starttime'];
                if ($starttime == '') {
                    $this->error('请填写上课时间');
                }
                $data['starttime'] = strtotime($starttime);

                $endtime = $data['endtime'];
                if ($endtime == '') {
                    $this->error('请填写下课时间');
                }
                $data['endtime'] = strtotime($endtime);

                if ($data['starttime'] >= $data['endtime']) {
                    $this->error('下课时间不能早于上课时间');
                }
            }


            $info = $data['info'] ?? '';
            if ($info == '') {
                $this->error('请编辑介绍');
            }

            if ($sort == 0) {
                $content = $data['content'] ?? '';
                if ($content == '') {
                    $this->error('请编辑内容');
                }
            }

            /* 去除无用字段 */
            unset($data['type_video']);
            unset($data['type_audio']);
            unset($data['trialval_1']);
            unset($data['trialval_2']);

            $data['uptime'] = $nowtime;

            $rs = DB::name('course')->update($data);

            if ($rs === false) {
                $this->error("保存失败！");
            }

            $this->success("保存成功！");
        }
    }

    /**
     * 排序
     */
    public function listOrder()
    {
        $model = DB::name('course');
        parent::listOrders($model);
        $this->success("排序更新成功！");
    }

    /*
     * 课程删除
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $rs = CourseModel::where('id', $id)->delete();
        if (!$rs) {
            $this->error("删除失败！");
        }

        $this->success("删除成功！");
    }


}