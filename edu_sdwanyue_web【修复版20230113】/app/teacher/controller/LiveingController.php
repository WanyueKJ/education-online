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
 * 直播
 */
class LiveingController extends TeacherBaseController
{

    public function index()
    {

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        $data = $this->request->param();

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

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
            if (!$courseinfo) {
                $this->error('信息错误');
            }

            if ($courseinfo['uid'] != $uid && $courseinfo['tutoruid'] != $uid) {
                $this->error('信息错误');
            }

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

            if ($liveinfo['uid'] != $uid && $liveinfo['tutoruid'] != $uid) {
                $this->error('信息错误');
            }
            $islive = $liveinfo['islive'];
            if ($islive == 2) {
                $this->error('直播已结束');
            }

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

        $start_length = 0;
        if ($islive == 1) {
            $start_length = $nowtime - $liveinfo['starttime'];
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
            'start_length'  => $start_length,
            'uuid'          => $uuid,
            'roomtoken'     => $roomtoken,
            'file_uuid'     => $file_uuid,
            'isshup'        => $isshup,
            'chatopen'      => $chatopen,
            'user_type'     => $user_type,
            'livemode'      => $livemode,
            'pptindex'      => $pptindex,
        ];


        $this->assign('info', $info);
        $this->assign('infoj', json_encode($info));

        $this->assign('teacherinfoj', json_encode($teacherinfo));

        if ($type == 4) {
            return $this->fetch('white');
        }

        return $this->fetch();
    }

    /* 开始直播 */
    public function startlive()
    {
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        if ($lessonid > 0) {
            $isexist = Db::name('course_lesson')->where(['uid' => $uid, 'courseid' => $courseid, 'id' => $lessonid])->find();
        } else {
            $isexist = CourseModel::where(['uid' => $uid, 'id' => $courseid])->find();
        }

        if (!$isexist) {
            $this->error('无权操作');
        }

        if ($isexist['islive'] == 2) {
            $this->error('已上完课了');
        }

        $data_live = [
            'islive'    => 1,
            'starttime' => time(),
        ];

        if ($lessonid > 0) {
            $res = Db::name('course_lesson')->where(['uid' => $uid, 'courseid' => $courseid, 'id' => $lessonid])->update($data_live);
        } else {
            $res = CourseModel::where(['uid' => $uid, 'id' => $courseid])->update($data_live);
        }
        if ($res === false) {
            $this->error('操作失败，请重试');
        }

        $this->success('操作成功');
    }

    /* 结束直播 */
    public function endlive()
    {
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        if ($lessonid > 0) {
            $isexist = Db::name('course_lesson')->where(['uid' => $uid, 'courseid' => $courseid, 'id' => $lessonid])->find();
        } else {
            $isexist = CourseModel::where(['uid' => $uid, 'id' => $courseid])->find();
        }

        if (!$isexist) {
            $this->error('无权操作');
        }

        $nowtime = time();

        if ($isexist['islive'] != 1 && $nowtime < $isexist['starttime']) {
            $this->error('当前未在直播中');
        }

        $data_live = [
            'islive'  => 2,
            'endtime' => $nowtime,
        ];

        if ($lessonid > 0) {
            /* 结束录制 */
            $stream = $uid . '_' . $courseid . '_' . $lessonid;
            if ($isexist['resourceid'] && $isexist['sid']) {
                $rs_stop = agoraStopRe($stream, $uid, $isexist['resourceid'], $isexist['sid']);
                if ($rs_stop['code'] == 0) {
                    // $url=$rs_stop['data']['serverResponse']['fileList'][0]['filename'];
                }

                $url = "record/{$isexist['sid']}_{$stream}.m3u8";

                $data_live['url'] = $url;
            }

        }

        if ($lessonid > 0) {
            $res = Db::name('course_lesson')->where(['uid' => $uid, 'courseid' => $courseid, 'id' => $lessonid])->update($data_live);
        } else {
            $res = CourseModel::where(['uid' => $uid, 'id' => $courseid])->update($data_live);
        }
        if ($res === false) {
            $this->error('操作失败，请重试');
        }

        $this->success('操作成功');
    }

    /* 用户进入 写缓存
		50本房间主播 60超管 40管理员 30观众 10为游客(判断当前用户身份) 
    */
    public function setNodeInfo()
    {

        /* 当前用户信息 */
        $uid   = session('teacher.id');
        $token = session('teacher.token');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if (!$token) {
            $token = $_SESSION['teacher']['token'] ?? '';
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

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $liveuid   = $data['liveuid'] ?? '0';
        $courseid  = $data['courseid'] ?? '0';
        $lessonid  = $data['lessonid'] ?? '0';
        $type      = $data['type'] ?? '0';
        $content   = $data['content'] ?? '';
        $url       = $data['url'] ?? '';
        $length    = $data['length'] ?? '0';
        $toid      = $data['toid'] ?? '0';
        $status    = $data['status'] ?? '0';
        $user_type = $data['user_type'] ?? '0';

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

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $type     = $data['type'] ?? '0';
        $lastid   = $data['lastid'] ?? '0';

        $where = [
            ['courseid', '=', $courseid],
            ['lessonid', '=', $lessonid],
        ];

        if ($type == 1) {
            $where[] = ['uid', '=', $uid];
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

    /* 语音 */
    public function addAudio()
    {

        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid   = $data['courseid'] ?? '0';
        $lessonid   = $data['lessonid'] ?? '0';
        $audio_time = $data['audio_time'] ?? '0';

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


    /* 添加ppt图片 */
    public function addPPT()
    {
        $rs   = ['code' => 0, 'data' => [], 'msg' => '', 'url' => ''];
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            // $this->error('您的登陆状态失效，请重新登陆！');
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

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

        if ($courseid < 1) {
            // $this->error('信息错误');
            $rs['msg'] = '信息错误';
            echo json_encode($rs);
            exit;
        }

        if ($lessonid > 0) {
            $isexist = Db::name('course_lesson')->where(['id' => $lessonid, 'uid' => $uid, 'courseid' => $courseid])->find();
            if (!$isexist) {
                // $this->error('无权操作');
                $rs['msg'] = '无权操作';
                echo json_encode($rs);
                exit;
            }
        } else {
            $isexist = CourseModel::where(['id' => $courseid, 'uid' => $uid])->find();
            if (!$isexist) {
                // $this->error('无权操作');
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
            // $this->error('添加失败，请重试');
            $rs['msg'] = '添加失败，请重试';
            echo json_encode($rs);
            exit;
        }

        $res = [
            'id'    => $id,
            'thumb' => get_upload_path($thumb),
        ];

        // $this->success('操作成功','',$res);

        $rs['code'] = 1;
        $rs['data'] = $res;
        echo json_encode($rs);
        exit;
    }

    /* 删除ppt图片 */
    public function delPPT()
    {
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $pptid    = $data['pptid'] ?? '0';

        if ($pptid < 1) {
            $this->error('信息错误');
        }

        $where = [
            'uid' => $uid,
            'id'  => $pptid,
        ];

        $isok = Db::name('course_ppt')->where($where)->delete();
        if ($isok === false) {
            $this->error('操作失败，请重试');
        }

        $this->success('操作成功');
    }


    /* 获取课件 */
    public function getWare()
    {
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

        if ($courseid < 1 || $lessonid < 1) {
            $this->error('信息错误');
        }

        $where = [
            'uid'      => $uid,
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

    /* 添加课件 */
    public function addWare()
    {

        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $file = $_FILES['file'] ?? '';
        if (!$file) {
            $this->error('请选择课件');
        }

        if ($file['size'] == 0) {
            $this->error('不能上传空文件');
        }
        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        if ($lessonid > 0) {
            $isexist = Db::name('course_lesson')->where(['id' => $lessonid, 'uid' => $uid, 'courseid' => $courseid])->find();
        } else {
            $isexist = CourseModel::where(['id' => $courseid, 'uid' => $uid])->find();
        }

        if (!$isexist) {
            $this->error('无权操作');
        }

        $res = upload($file, 'file');
        if ($res['code'] != 0) {
            $this->error($res['msg']);
        }
        $name   = $file['name'];
        $url    = $res['url'];
        $size   = handelSize($file['size']);
        $insert = [
            'uid'      => $uid,
            'courseid' => $courseid,
            'lessonid' => $lessonid,
            'name'     => $name,
            'url'      => $url,
            'size'     => $size,
            'addtime'  => time(),
        ];

        $id = Db::name('course_ware')->insertGetId($insert);
        if (!$id) {
            $this->error('添加失败，请重试');
        }

        $res = [
            'id'   => $id,
            'name' => $name,
            'size' => $size,
            'url'  => get_upload_path($url),
        ];

        $this->success('操作成功', '', $res);

    }

    /* 删除课件 */
    public function delWare()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $id       = $data['id'] ?? '0';

        if ($id < 1) {
            $this->error('信息错误');
        }

        $where = [
            'uid' => $uid,
            'id'  => $id,
        ];

        $isok = Db::name('course_ware')->where($where)->delete();
        if ($isok === false) {
            $this->error('操作失败，请重试');
        }

        $this->success('操作成功');
    }

    /* 获取用户列表数量 */
    protected function getUserNums($stream)
    {
        $nums = zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        return $nums;
    }

    /* 获取用户列表数量 */
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

    /* 获取用户列表 */
    public function getUserLists()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $stream   = $data['stream'] ?? '';

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


    /* 上下麦 */
    public function setLinkmic()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $stream   = $data['stream'] ?? '';
        $touid    = $data['touid'] ?? '0';
        $type     = $data['type'] ?? '0';

        if ($courseid < 0 || $touid < 1) {
            $this->error('信息错误');
        }

        $where1     = ['id' => $courseid];
        $courseinfo = CourseModel::where($where1)->find();
        if (!$courseinfo) {
            $this->error('信息错误');
        }

        if ($courseinfo['uid'] != $uid && $courseinfo['tutoruid'] != $uid) {
            $this->error('无权操作');
        }
        $liveuid = $courseinfo['uid'];

        if ($type == 1) {
            /* 上麦 */
            $key     = 'linkmic_apply_' . $stream;
            $isapply = zScore($key, $touid);
            if (!$isapply) {
                $this->error('对方未举手');
            }

            zRem($key, $touid);

            /* 上麦后修改列表顺序 */
            $key2 = 'user_' . $stream;
            zAdd($key2, '1', $touid);

        }

        $this->success('操作成功');

    }

    /* 个人禁言 */
    public function setShutup()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $touid    = $data['touid'] ?? '0';
        $type     = $data['type'] ?? '0';

        if ($courseid < 1 || $touid < 1) {
            $this->error('信息错误');
        }

        $where1     = ['id' => $courseid];
        $courseinfo = CourseModel::where($where1)->find();
        if (!$courseinfo) {
            $this->error('信息错误');
        }

        if ($courseinfo['uid'] != $uid && $courseinfo['tutoruid'] != $uid) {
            $this->error('无权操作');
        }

        $liveuid = $courseinfo['uid'];

        if ($type == 1) {
            $where  = [
                'uid'      => $touid,
                'liveuid'  => $liveuid,
                'courseid' => $courseid,
                'lessonid' => $lessonid,
            ];
            $isshut = $this->isShutup($where);
            if ($isshut) {
                $this->error('对方已被禁言');
            }

            $insert = [
                'uid'        => $touid,
                'liveuid'    => $liveuid,
                'courseid'   => $courseid,
                'lessonid'   => $lessonid,
                'operateuid' => $uid,
                'addtime'    => time(),
            ];

            $isok = Db::name('live_shutup')->insert($insert);

            if (!$isok) {
                $this->error('操作失败，请重试');
            }

        } else {
            $where = [
                'uid'      => $touid,
                'liveuid'  => $liveuid,
                'courseid' => $courseid,
                'lessonid' => $lessonid,
            ];
            $isok  = Db::name('live_shutup')->where($where)->delete();
        }

        $this->success('操作成功');

    }




    /* 房间禁言 */
    public function roomShutup()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $type     = isset($data['type']) ? checkNull($data['type']) : '0';


        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $courseinfo = Db::name('course')->where(['id' => $courseid])->find();
        if (!$courseinfo) {
            $this->error('信息错误');
        }

        if ($courseinfo['uid'] != $uid && $courseinfo['tutoruid'] != $uid) {
            $this->error('信息错误');
        }

        $update = [
            'isshup' => $type
        ];

        if ($lessonid > 0) {
            $isok = Db::name('course_lesson')->where(['courseid' => $courseid, 'id' => $lessonid])->update($update);
        } else {
            $isok = CourseModel::where(['id' => $courseid])->update($update);
        }

        $this->success('操作成功');

    }

    /* 房间开放交流区 */
    public function roomChat()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';
        $type     = $data['type'] ?? '0';


        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $courseinfo = CourseModel::where(['id' => $courseid])->find();
        if (!$courseinfo) {
            $this->error('信息错误');
        }

        if ($courseinfo['uid'] != $uid && $courseinfo['tutoruid'] != $uid) {
            $this->error('信息错误');
        }

        $update = [
            'chatopen' => $type
        ];

        if ($lessonid > 0) {
            $isok = Db::name('course_lesson')->where(['courseid' => $courseid, 'id' => $lessonid])->update($update);
        } else {
            $isok = CourseModel::where(['id' => $courseid])->update($update);
        }

        $this->success('操作成功');

    }

    protected function isShutup($where)
    {
        $isshut = Db::name('live_shutup')->where($where)->find();
        if ($isshut) {
            return 1;
        }
        return 0;
    }

    /* 添加图片 */
    public function addPhoto()
    {
        $rs   = ['code' => 0, 'data' => [], 'msg' => '', 'url' => ''];
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $file = $_FILES['file'];
        if (!$file) {
            $this->error('请选择图片');
        }


        $res = upload();
        if ($res['code'] != 0) {
            $this->error($res['msg']);
        }
        $thumb = $res['url'];

        $res = [
            'thumb' => get_upload_path($thumb),
        ];

        $this->success('操作成功', '', $res);
    }

    /* 添加文档 */
    public function addFiles()
    {
        $rs   = ['code' => 0, 'data' => [], 'msg' => '', 'url' => ''];
        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $file = $_FILES['file'];
        if (!$file) {
            $this->error('请选择文档');
        }

        $pathinfo = pathinfo($file['name']);
        if (!isset($pathinfo['extension']) || $pathinfo['extension'] == '' || !in_array($pathinfo['extension'], ['doc', 'docx', 'ppt', 'pdf', 'pptx'])) {
            $this->error('文档仅支持：doc、docx、ppt、pdf、pptx');
        }

        $type = 'file';
        $res  = upload($file, $type);
        if ($res['code'] != 0) {
            $this->error($res['msg']);
        }
        $url = $res['url'];

        $res = [
            'url' => get_upload_path($url),
        ];

        $this->success('操作成功', '', $res);
    }




    /* 更新课程模式 */
    public function upMode()
    {

        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $livemode = $data['livemode'] ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $isexist = CourseModel::where(['uid' => $uid, 'id' => $courseid])->find();
        if (!$isexist) {
            $this->error('无权操作');
        }
        $update = ['livemode' => $livemode];
        $isok   = CourseModel::where(['uid' => $uid, 'id' => $courseid])->update($update);

        $this->success('操作成功');
    }

    /* 更新课程PPT页码 */
    public function upPPTindex()
    {

        $data = $this->request->param();

        $uid = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid    = $data['courseid'] ?? '0';
        $lessonid    = $data['lessonid'] ?? '0';
        $activeIndex = $data['activeIndex'] ?? '0';

        if ($courseid < 1) {
            $this->error('信息错误');
        }

        $isexist = CourseModel::where(['uid' => $uid, 'id' => $courseid])->find();
        if (!$isexist) {
            $this->error('无权操作');
        }

        if ($lessonid > 0) {
            $update = ['pptindex' => $activeIndex];
            $isok   = Db::name('course_lesson')->where(['courseid' => $courseid, 'id' => $lessonid])->update($update);
        } else {

            $update = ['pptindex' => $activeIndex];
            $isok   = CourseModel::where(['uid' => $uid, 'id' => $courseid])->update($update);
        }


        $this->success('操作成功');
    }

    /* 声网开始录制 */
    public function createRecord()
    {
        $data = $this->request->param();
        $uid  = session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if ($uid < 1) {
            $this->error('您的登陆状态失效，请重新登陆！');
        }

        $courseid = $data['courseid'] ?? '0';
        $lessonid = $data['lessonid'] ?? '0';

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







}


