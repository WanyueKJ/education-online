<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/09 10:11
// +----------------------------------------------------------------------
namespace app\Student\controller;

use app\admin\model\CourseModel;
use cmf\controller\StudentBaseController;
use think\Db;

/**
 * 直播
 */
class LiveingController extends StudentBaseController
{

    public function index()
    {

        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
        }

        $data = $this->request->param();

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';

        $this->checkEnterLive($courseid, $lessonid);

        //检测直播
        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $nowtime   = time();
        $times     = 0;
        $uuid      = '';
        $roomtoken = '';
        $file_uuid = '';
        $livemode  = '0';

        if ($lessonid > 0) {
            $liveinfo = Db::name("course_lesson")->where(['id' => $lessonid, 'courseid' => $courseid])->find();

            if (!$liveinfo) {
                $this->error('信息错误');
            }

            $type = $liveinfo['type'] - 3;

            $courseinfo = CourseModel::where(['id' => $courseid])->find();

            $tutoruid = $courseinfo['tutoruid'];

            $thumb  = get_upload_path($courseinfo['thumb']);
            $islive = $liveinfo['islive'];

            if ($islive == 0 && $liveinfo['starttime'] > $nowtime) {
                $times = $liveinfo['starttime'] - $nowtime;
            }

            $uuid      = $liveinfo['uuid'];
            $roomtoken = $liveinfo['roomtoken'];
            $file_uuid = $liveinfo['file_uuid'];
            $isshup    = $liveinfo['isshup'];
            $chatopen  = $liveinfo['chatopen'];
            $pptindex  = $liveinfo['pptindex'];

            if ($type == 4 && $islive == 2) {
                $this->error('授课已结束');
            }

        } else {
            $liveinfo = CourseModel::where(['id' => $courseid])->find();

            if (!$liveinfo) {
                $this->error('信息错误');
            }

            if ($liveinfo['sort'] < 2) {
                $this->error('当前非直播课程');
            }

            $islive = $liveinfo['islive'];

            $tutoruid = $liveinfo['tutoruid'];

            $type  = $liveinfo['type'];
            $thumb = get_upload_path($liveinfo['thumb']);

            if ($liveinfo['starttime'] > $nowtime) {
                $times = $liveinfo['starttime'] - $nowtime;
            }

            $livemode = $liveinfo['livemode'];
            $pptindex = $liveinfo['pptindex'];
            $isshup   = $liveinfo['isshup'];
            $chatopen = $liveinfo['chatopen'];

        }

        $liveuid = $liveinfo['uid'];

        /* 用户身份 */
        $user_type = '0';
        if ($uid == $liveuid) {
            $user_type = '1';
        }

        if ($uid == $tutoruid) {
            $user_type = '2';
        }

        if ($user_type == 1) {
            $livemode = '0';
            $pptindex = '0';
        }

        $teacherinfo = getUserInfo($liveuid);

        $configpri = getConfigPri();
        $stream    = $liveuid . '_' . $courseid . '_' . $lessonid;

        $name = $liveinfo['name'];
        $pull = get_upload_path($liveinfo['url']);

        /* 用户数量 */
        $nums = $this->getUserNums($stream);

        $ppts = [];
        if ($type == 1 || $type == 5) {
            $ppts = Db::name("course_ppt")->where(['courseid' => $courseid, 'lessonid' => $lessonid])->order('id asc')->select()->toArray();
            foreach ($ppts as $k => $v) {
                $v['thumb'] = get_upload_path($v['thumb']);
                $ppts[$k]   = $v;
            }
        }

        $info = [
            'id'            => $liveinfo['id'],
            'liveuid'       => $liveuid,
            'chatserver'    => $configpri['chatserver'],
            'sound_appid'   => $configpri['sound_appid'],
            'pull'          => $pull,
            'stream'        => $stream,
            'livetype'      => $type,
            'courseid'      => $courseid,
            'lessonid'      => $lessonid,
            'name'          => $name,
            'thumb'         => $thumb,
            'nums'          => $nums,
            'ppts'          => $ppts,
            'pptsj'         => json_encode($ppts),
            'islive'        => $islive,
            'times'         => $times,
            'uuid'          => $uuid,
            'roomtoken'     => $roomtoken,
            'file_uuid'     => $file_uuid,
            'isshup'        => $isshup,
            'user_type'     => $user_type,
            'livemode'      => $livemode,
            'pptindex'      => $pptindex,
            'shutup_room'   => $liveinfo['isshup'],
            'chatopen'      => $chatopen,
        ];

        $this->setLesson($uid, $courseid, $lessonid = 0);

        $this->assign('info', $info);
        $this->assign('infoj', json_encode($info));

        $this->assign('teacherinfoj', json_encode($teacherinfo));


        $select_list = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
        $pan_list    = ['错', '对'];
        $type_list   = ['判断题', '单选题', '定项多选题', '简答题', '填空题', '不定项多选题'];

        $this->assign('type_listj', json_encode($type_list));
        $this->assign('select_listj', json_encode($select_list));
        $this->assign('pan_listj', json_encode($pan_list));

        if ($type == 4) {
            return $this->fetch('white');
        }

        return $this->fetch();
    }

    /*
     * 直播回放
     * @return mixed
     */
    public function liveback()
    {

        $uid = session('student.id');

        $data = $this->request->param();

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';

        $this->checkEnterLive($courseid, $lessonid);

        //检测直播
        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $liveinfo = Db::name("course_lesson")->where(['id' => $lessonid, 'courseid' => $courseid])->find();

        if (!$liveinfo) {
            $this->error('信息错误');
        }

        $courseinfo = CourseModel::where(['id' => $courseid])->find();

        $thumb = get_upload_path($courseinfo['thumb']);

        $url = get_upload_path($liveinfo['url']);

        $uuid      = $liveinfo['uuid'];
        $roomtoken = $liveinfo['roomtoken'];
        $info      = [
            'name'          => $liveinfo['name'],
            'uuid'          => $uuid,
            'roomtoken'     => $roomtoken,
            'url'           => $url,
            'thumb'         => $thumb,
        ];

        $this->setLesson($uid, $courseid, $lessonid = 0);

        $this->assign('info', $info);
        $this->assign('infoj', json_encode($info));

        return $this->fetch();
    }

    /* 用户进入 写缓存
	 *	50本房间主播 60超管 40管理员 30观众 10为游客(判断当前用户身份)
     */
    public function setNodeInfo()
    {

        /* 当前用户信息 */
        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
        }
        $token = session('student.token');
        if (!$token) {
            $token = $_SESSION['student']['token'] ?? '';
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $data = $this->request->param();

        $user_type = $data['user_type'] ?? '0';

        $info          = getUserInfo($uid);
        $info['token'] = $token;

        $info['usertype']  = $user_type;
        $info['user_type'] = $user_type;
        $info['sign']      = '0';

        setcaches($token, $info);

        $data = [
            'uid'   => $uid,
            'token' => $token,
        ];

        $this->success('', '', $data);
    }

    public function setChat()
    {
        $data = $this->request->param();

        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $liveuid   = sql_check($data['liveuid']) ?? '0';
        $courseid  = sql_check($data['courseid']) ?? '0';
        $lessonid  = sql_check($data['lessonid']) ?? '0';
        $type      = sql_check($data['type']) ?? '0';
        $content   = sql_check($data['content']) ?? '';
        $url       = sql_check($data['url']) ?? '';
        $length    = sql_check($data['length']) ?? '0';
        $toid      = sql_check($data['toid']) ?? '0';
        $status    = sql_check($data['status']) ?? '0';
        $user_type = sql_check($data['user_type']) ?? '0';

        if ($liveuid < 0 || $courseid < 0) {
            $this->error('信息错误');
        }

        if ($type == 0 && $content == '') {
            $this->error('请输入内容');
        }

        if ($type == 1 && ($url == '' || $length < 1)) {
            $this->error('请录制语音');
        }

        if ($status == 2 && $toid < 1) {
            $this->error('请选择回复内容');
        }

        if ($content != strip_tags($content)) {
            $this->error('内容包含非法字符');
        }

        $data_insert = [
            'uid'       => $uid,
            'liveuid'   => $liveuid,
            'courseid'  => $courseid,
            'lessonid'  => $lessonid,
            'type'      => $type,
            'content'   => $content,
            'url'       => $url,
            'length'    => $length,
            'toid'      => $toid,
            'status'    => $status,
            'user_type' => $user_type,
            'addtime'   => time(),
        ];

        $id = DB::name('live_chat')->insertGetId($data_insert);
        if (!$id) {
            $this->error("添加失败！");
        }

        $info = [
            'chatid'    => $id,
            'type'      => $type,
            'content'   => $content,
            'url'       => get_upload_path($url),
            'length'    => $length,
            'status'    => $status,
            'user_type' => $user_type,
            'toid'      => $toid,
        ];

        $this->success("添加成功！", '', $info);
    }

    public function getChat()
    {
        $data = $this->request->param();

        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';
        $type     = sql_check($data['type']) ?? '0';
        $lastid   = sql_check($data['lastid']) ?? '0';
        $liveuid  = sql_check($data['liveuid']) ?? '0';

        $where = [
            ['courseid', '=', $courseid],
            ['lessonid', '=', $lessonid],
        ];

        if ($type == 1) {
            $where[] = ['uid', '=', $liveuid];
        }
        if ($type == 2) {
            $where[] = ['status', '<>', 0];
        }

        if ($lastid > 0) {
            $where[] = ['id', '<', $lastid];
        }
        $list = Db::name('live_chat')->where($where)->order("id desc")->limit(20)->select()->toArray();
        foreach ($list as $k => $v) {
            $userinfo           = getUserInfo($v['uid']);
            $v['user_nickname'] = $userinfo['user_nickname'];
            $v['avatar']        = $userinfo['avatar'];
            if ($v['type'] == 1) {
                $v['url'] = get_upload_path($v['url']);
            }

            $v['add_time'] = date('Y-m-d H:i:s', $v['addtime']);

            $toinfo = [];
            if ($v['toid'] > 0) {
                $toinfo = Db::name('live_chat')->where(['id' => $v['toid']])->find();
                if ($toinfo) {
                    $touserinfo              = getUserInfo($toinfo['uid']);
                    $toinfo['user_nickname'] = $touserinfo['user_nickname'];
                    $toinfo['avatar']        = $touserinfo['avatar'];
                    if ($toinfo['type'] == 1) {
                        $toinfo['url'] = get_upload_path($toinfo['url']);
                    }
                }
            }

            $v['toinfo'] = $toinfo;

            $list[$k] = $v;
        }

        $list = array_reverse($list);

        $this->success('', '', $list);
    }

    /**
     * 添加语音
     */
    public function addAudio()
    {
        $rs   = ['code' => 0, 'data' => [], 'msg' => '', 'url' => ''];
        $data = $this->request->param();

        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid   = sql_check($data['courseid']) ?? '0';
        $lessonid   = sql_check($data['lessonid']) ?? '0';
        $audio_time = sql_check($data['audio_time']) ?? '0';

        $file = $_FILES['file'];

        if (!$file) {
            $this->error('请先录制语音');
        }
        $_FILES['file']['name'] = $_FILES['file']['name'] . '.mp3';

        $res = upload($file, 'audio');
        if ($res['code'] != 0) {
            $this->error($res['msg']);
        }
        $url = $res['url'];

        $length = floor($audio_time * 100) * 0.01;

        $data_insert = [
            'uid'      => $uid,
            'liveuid'  => $uid,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
            'type'     => 1,
            'content'  => '',
            'url'      => $url,
            'length'   => $length,
            'status'   => 0,
            'addtime'  => time(),
        ];

        $id = DB::name('live_chat')->insertGetId($data_insert);
        if (!$id) {
            $this->error("添加失败！");
        }

        $info = [
            'chatid'  => $id,
            'type'    => '1',
            'content' => '',
            'url'     => get_upload_path($url),
            'length'  => $length,
            'status'  => 0,
        ];

        $this->success("发送成功！", '', $info);
    }

    /* 获取套题 */
    public function getExam()
    {
        $data = $this->request->param();

        $uid   = session('student.id');
        $token = session('student.token');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = checkNull($data['courseid']) ?? '0';
        $lessonid = checkNull($data['lessonid']) ?? '0';
        $liveuid  = checkNull($data['liveuid']) ?? '0';
        if ($courseid < 1 || $lessonid < 1) {
            $this->error('信息错误');
        }


        $url  = $this->siteUrl . '/api/?s=Exam.GetExam&uid=' . $uid . '&token=' . $token . '&liveuid=' . $liveuid . '&courseid=' . $courseid . '&lessonid=' . $lessonid;
        $list = curl_get($url);
        $this->success('', '', $list['data']);
    }



    /* 添加ppt图片 */
    public function addPPT()
    {
        $rs   = ['code' => 0, 'data' => [], 'msg' => '', 'url' => ''];
        $data = $this->request->param();

        $uid = session('student.id');

        if ($uid < 1) {
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            echo json_encode($rs);
            exit;
        }

        $file = $_FILES['file'];
        if (!$file) {
            $rs['msg'] = '请选择图片';
            echo json_encode($rs);
            exit;
        }

        $courseid = checkNull($data['courseid']) ?? '0';
        $lessonid = checkNull($data['lessonid']) ?? '0';

        if ($courseid < 1) {
            $rs['msg'] = '信息错误';
            echo json_encode($rs);
            exit;
        }

        if ($lessonid > 0) {
            $isexist = Db::name('course_lesson')->where(['id' => $lessonid, 'uid' => $uid, 'courseid' => $courseid])->find();
            if (!$isexist) {
                $rs['msg'] = '无权操作';
                echo json_encode($rs);
                exit;
            }
        } else {
            $isexist = Db::name('course')->where(['id' => $courseid, 'uid' => $uid])->find();
            if (!$isexist) {
                $rs['msg'] = '无权操作';
                echo json_encode($rs);
                exit;
            }
        }

        $res = upload();
        if ($res['code'] != 0) {
            $rs['msg'] = $res['msg'];
            echo json_encode($rs);
            exit;
        }
        $thumb  = $res['url'];
        $insert = [
            'uid'      => $uid,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
            'thumb'    => $thumb,
            'addtime'  => time(),
        ];

        $id = Db::name('course_ppt')->insertGetId($insert);
        if (!$id) {
            $rs['msg'] = '添加失败，请重试';
            echo json_encode($rs);
            exit;
        }

        $res = [
            'id'    => $id,
            'thumb' => get_upload_path($thumb),
        ];

        $rs['code'] = 1;
        $rs['data'] = $res;
        echo json_encode($rs);
        exit;
    }


    /*
     * 获取课件
     */
    public function getWare()
    {
        $data = $this->request->param();

        $uid = session('student.id');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';
        $liveuid  = sql_check($data['liveuid']) ?? '0';

        if ($courseid < 1 || $lessonid < 1) {
            $this->error('信息错误');
        }

        $where = [
            'uid'      => $liveuid,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
        ];

        $list = Db::name('course_ware')->where($where)->order("id desc")->select()->toArray();

        foreach ($list as $k => $v) {
            $v['url'] = get_upload_path($v['url']);

            $list[$k] = $v;
        }

        $this->success('', '', $list);
    }


    /*
     * 获取用户数量
     * @param $stream
     * @return int
     */
    protected function getUserNums($stream)
    {

        $nums = zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        return $nums;
    }

    /**
     * 获取用户列表数量
     */
    public function getUserListNum()
    {
        $data = $this->request->param();

        $stream = $data['stream'] ?? '';

        if ($stream == '') {
            $this->error('信息错误');
        }

        $nums = zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        $rs = [
            'nums' => $nums,
        ];

        $this->success('操作成功', '', $rs);
    }

    /**
     * 获取用户列表
     */
    public function getUserLists()
    {
        $data = $this->request->param();
        $uid  = session('student.id');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';
        $stream   = sql_check($data['stream']) ?? '';


        if ($courseid < 1 || $lessonid < 1 || $stream == '') {
            $this->error('信息错误');
        }

        $list      = [];
        $uids_have = [];

        /* 申请 */
        $apply_list = zRange('linkmic_apply_' . $stream, 0, -1, true);
        foreach ($apply_list as $k => $v) {
            $userinfo = getUserInfo($k);
            $type     = '2';
            $iswrite  = '0';

            $userinfo['type']    = $type;
            $userinfo['iswrite'] = $iswrite;


            $where              = [
                'uid'      => $k,
                'liveuid'  => $uid,
                'courseid' => $courseid,
                'lessonid' => $lessonid,
            ];
            $isshut             = $this->isShutup($where);
            $userinfo['isshut'] = $isshut;

            $list[]      = $userinfo;
            $uids_have[] = $k;
        }

        /* 用户列表 */
        $uidlist = zRevRange('user_' . $stream, 0, -1, true);

        foreach ($uidlist as $k => $v) {
            if (in_array($k, $uids_have)) {
                continue;
            }
            $userinfo = getUserInfo($k);
            $type     = '0';

            if ($v > 0) {
                $type = '1';
            }

            $iswrite = '0';
            if ($type == 1) {
                if (hGet('write_' . $stream, $k)) {
                    $iswrite = '1';
                }
            }

            $userinfo['type']    = $type;
            $userinfo['iswrite'] = $iswrite;

            $where2             = [
                'uid'      => $k,
                'liveuid'  => $uid,
                'courseid' => $courseid,
                'lessonid' => $lessonid,
            ];
            $isshut             = $this->isShutup($where2);
            $userinfo['isshut'] = $isshut;

            $list[] = $userinfo;
        }

        $nums = zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        $rs = [
            'list' => $list,
            'nums' => $nums,
        ];

        $this->success('操作成功', '', $rs);
    }

    /*
     * 是否被禁言
     * @param $where
     * @return int
     */
    protected function isShutup($where)
    {
        $isshut = Db::name('live_shutup')->where($where)->find();

        if ($isshut) {
            if ($isshut['type'] == 1) {
                return 1;
            } else {
                if ($isshut['endtime'] > time()) {
                    return 1;
                }
            }
        }

        return 0;
    }


    /*
     * 更新课程模式
     */
    public function upMode()
    {

        $data = $this->request->param();
        $uid = session('student.id');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = sql_check($data['courseid']) ?? '0';
        $livemode = sql_check($data['livemode']) ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $isexist = Db::name('course')->where(['uid' => $uid, 'id' => $courseid])->find();
        if (!$isexist) {
            $this->error('无权操作');
        }
        $update = ['livemode' => $livemode];
        $isok   = Db::name('course')->where(['uid' => $uid, 'id' => $courseid])->update($update);
        if ($isok) {
            $this->success('操作成功');
        }

        $this->error('操作失败');
    }


    /*
     * 更新课程PPT页码
     */
    public function upPPTindex()
    {

        $data = $this->request->param();

        $uid = session('student.id');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid    = sql_check($data['courseid']) ?? '0';
        $lessonid    = sql_check($data['lessonid']) ?? '0';
        $activeIndex = sql_check($data['activeIndex']) ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $isexist = Db::name('course')->where(['uid' => $uid, 'id' => $courseid])->find();
        if (!$isexist) {
            $this->error('无权操作');
        }

        if ($lessonid > 0) {
            $update = ['pptindex' => $activeIndex];
            $isok   = Db::name('course_lesson')->where(['courseid' => $courseid, 'id' => $lessonid])->update($update);
        } else {

            $update = ['pptindex' => $activeIndex];
            $isok   = Db::name('course')->where(['uid' => $uid, 'id' => $courseid])->update($update);
        }


        $this->success('操作成功');
    }

    /*
     * 声网开始录制
     */
    public function createRecord()
    {

        $data = $this->request->param();
        $uid  = session('student.id');

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';

        if ($courseid < 1 || $lessonid < 1) {
            $this->error('信息错误');
        }

        $nowtime = time();

        $where = [
            'uid'      => $uid,
            'courseid' => $courseid,
            'id'       => $lessonid,
        ];

        $info = Db::name('course_lesson')->where($where)->find();
        if (!$info) {
            $this->error('无权操作');
        }

        if ($info['islive'] != 1) {
            $this->error('还未开始上课');
        }

        if ($info['resourceid'] != '' && $info['sid'] != '') {
            $this->error('已录制');
        }

        $stream = $uid . '_' . $courseid . '_' . $lessonid;

        $rs_create = agoraCreateRe($stream, $uid);

        if ($rs_create['code'] != 0) {
            $this->error($rs_create['msg']);
        }

        $resourceid = $rs_create['data']['resourceId'];

        /* 开始录制 */
        $rs_start = agoraStartRe($stream, $uid, $resourceid);
        if ($rs_start['code'] != 0) {
            $this->error($rs_start['msg']);
        }

        $sid = $rs_start['data']['sid'];

        $data_up = [
            'resourceid' => $resourceid,
            'sid'        => $sid,
        ];

        $isok = Db::name('course_lesson')->where($where)->update($data_up);
        if ($isok === false) {
            $this->error('操作失败，请重试');
        }

        $this->success('操作成功');
    }


    /*
     * 更新进度
     * @param $uid
     * @param $courseid
     * @param int $lessonid
     * @return bool
     */
    function setLesson($uid, $courseid, $lessonid = 0)
    {
        $nowtime = time();
        $isview  = Db::name('course_views')->where(['uid' => $uid, 'courseid' => $courseid, 'lessonid' => $lessonid])->find();
        if ($isview) {
            Db::name('course_views')->where(["id" => $isview['id']])->update(['addtime' => $nowtime]);
            return !1;
        }

        $course = Db::name('course')->field('sort,type,paytype,lessons,uid')->where(["id" => $courseid])->find();
        if (!$course) {
            return !1;
        }

        $sort = $course['sort'];

        $data = [
            'uid'      => $uid,
            'sort'     => $sort,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
            'addtime'  => $nowtime
        ];
        Db::name('course_views')->insert($data);

        $nums = Db::name('course_views')->where(['uid' => $uid, 'courseid' => $courseid])->count();
        if ($nums < 2) {
            /* 同一课程下的课时 记一次课程学习数 */
            Db::name('course')->where(["id" => $courseid])->setInc('views', 1);
        }

        $isexist = Db::name('course_users')->where(['uid' => $uid, 'courseid' => $courseid])->find();
        if (!$isexist) {

            $status  = 0;
            $paytype = $course['paytype'];
            if ($paytype == 0) {
                $status = 1;
            }
            $data2 = [
                'uid'      => $uid,
                'sort'     => $course['sort'],
                'paytype'  => $paytype,
                'courseid' => $courseid,
                'liveuid'  => $course['uid'],
                'status'   => $status,
                'addtime'  => $nowtime,
                'paytime'  => $nowtime,
            ];
            Db::name('course_users')->insert($data2);

            $isexist = Db::name('course_users')->where(['uid' => $uid, 'courseid' => $courseid])->find();
        }

        if ($lessonid > 0) {
            Db::name('course_users')->where(['id' => $isexist['id']])->setInc('lessons', 1);

            $lessons = Db::name('course_users')->field('lessons')->where(['id' => $isexist['id']])->find();
            if ($lessons['lessons'] >= $course['lessons']) {
                /* 看完 */
                Db::name('course_users')->where(['id' => $isexist['id']])->update(['step' => 2]);
            } else {
                Db::name('course_users')->where(['id' => $isexist['id']])->update(['step' => 1]);
            }
        } else {
            Db::name('course_users')->where(['id' => $isexist['id']])->update(['step' => 2]);
        }
    }

    /*
     * 判断有没有被禁言
     */
    public function isUserShutup()
    {

        $data     = $this->request->param();
        $uid      = session('student.id');
        $courseid = sql_check($data['courseid']) ?? '0';
        $lessonid = sql_check($data['lessonid']) ?? '0';
        $liveuid  = sql_check($data['liveuid']) ?? '0';

        $where = [
            'uid'      => $uid,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
            'liveuid'  => $liveuid
        ];

        $isshut = Db::name('live_shutup')->where($where)->find();

        if ($isshut) {
            $this->success('你已被禁言', '', 1);
        }

        $this->success('', '', 0);

    }


    /*
     * 生成签名
     */
    public function getSign()
    {

        $data  = $this->request->param();

        $key = '400d069a791d51ada8af3e6c2979bcd7';
        $str = '';
        ksort($data);
        foreach ($data as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str     .= $key;
        $newsign = md5($str);

        $this->success('', '', $newsign);

    }


}


