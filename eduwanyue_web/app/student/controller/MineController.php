<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/12 08:17
// +----------------------------------------------------------------------
namespace app\student\controller;

use cmf\controller\StudentBaseController;
use think\Db;

/**
 * 我的
 * Class MineController
 * @package app\student\controller
 */
class MineController extends StudentBaseController
{
    /**
     * 我购买的
     * @return mixed
     */
    public function mybuy()
    {

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student');
        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;
        $baseinfo = curl_get($url);

        //已购买的课程
        $url = $this->siteUrl . '/api/?s=Course.GetMyBuy&uid=' . $uid . '&token=' . $token . '&p=1';

        $list = curl_get($url);

        $isMore = 0;
        if (count($list['data']['info']) >= 20) {
            $isMore = 1;
        }

        $this->assign([
            'baseinfo' => $baseinfo['data']['info'][0],
            'isMore'   => $isMore,
            'lists'    => $list['data']['info'],
            'mynavid'  => 13,
            'navid'    => -1
        ]);

        return $this->fetch();
    }


    /**
     * 我的课程里面我的课程
     * @return mixed
     */
    public function index()
    {

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo = curl_get($url);
        $baseinfoDataInfo = $baseinfo['data']['info'][0] ?? [];
        $this->assign('baseinfo', $baseinfoDataInfo);


        //我的全部课程
        $url = $this->siteUrl . '/api/?s=Course.GetMyCourse&uid=' . $uid . '&token=' . $token . '&type=0';

        $bothlist         = curl_get($url);
        $isMore0          = 0;
        $bothlistDataInfo = $bothlist['data']['info'] ?? [];
        if (count($bothlistDataInfo) >= 20) {
            $isMore0 = 1;
        }
        $this->assign('isMore0', $isMore0);
        $this->assign('bothlist', $bothlistDataInfo);


        //我的未开始课程
        $url = $this->siteUrl . '/api/?s=Course.GetMyCourse&uid=' . $uid . '&token=' . $token . '&type=1';

        $nostartlist = curl_get($url);

        $isMore1             = 0;
        $nostartlistDataInfo = $nostartlist['data']['info'] ?? [];
        if (count($nostartlistDataInfo) >= 20) {
            $isMore1 = 1;
        }
        $this->assign('isMore1', $isMore1);
        $this->assign('nostartlist', $nostartlistDataInfo);

        //我的学习中课程
        $url = $this->siteUrl . '/api/?s=Course.GetMyCourse&uid=' . $uid . '&token=' . $token . '&type=2';

        $handlist         = curl_get($url);
        $isMore2          = 0;
        $handlistDataInfo = $handlist['data']['info'] ?? [];
        if (count($handlistDataInfo) >= 20) {
            $isMore2 = 1;
        }
        $this->assign('isMore2', $isMore2);
        $this->assign('handlist', $handlistDataInfo);

        //已学完的课程
        $url = $this->siteUrl . '/api/?s=Course.GetMyCourse&uid=' . $uid . '&token=' . $token . '&type=3';

        $overlist = curl_get($url);

        $isMore3          = 0;
        $overlistDataInfo = $overlist['data']['info'] ?? [];
        if (count($overlistDataInfo) >= 20) {
            $isMore3 = 1;
        }
        $this->assign('isMore3', $isMore3);
        $this->assign('overlist', $overlistDataInfo);

        $this->assign('mynavid', 1);
        $this->assign('navid', -1);

        return $this->fetch();
    }


    /**
     * 我的直播课程
     * @return mixed
     */
    public function livelist()
    {
        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo         = curl_get($url);
        $baseinfoDataInfo = $baseinfo['data']['info'][0] ?? [];

        //购买的直播
        $url = $this->siteUrl . '/api/?s=Course.GetMyBuy&uid=' . $uid . '&token=' . $token . '&p=1&sort=2';

        $bothlist         = curl_get($url);
        $isMore0          = 0;
        $bothlistDataInfo = $bothlist['data']['info'] ?? [];
        if (count($bothlistDataInfo) >= 20) {
            $isMore0 = 1;
        }

        $this->assign([
            'baseinfo' => $baseinfoDataInfo,
            'isMore0'  => $isMore0,
            'bothlist' => $bothlistDataInfo,
            'mynavid'  => 2,
            'navid'    => -1
        ]);

        return $this->fetch();
    }

    /**
     * 我的内容课程
     * @return mixed
     */
    public function contlist()
    {
        //判断有没有登录
        $this->checkMyLogin();
        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo         = curl_get($url);
        $baseinfoDataInfo = $baseinfo['data']['info'][0] ?? [];

        //内容
        $url = $this->siteUrl . '/api/?s=Course.GetMyBuy&uid=' . $uid . '&token=' . $token . '&p=1&sort=-1';

        $bothlist         = curl_get($url);
        $bothlistDataInfo = $bothlist['data']['info'] ?? [];
        $isMore0          = 0;
        if (count($bothlistDataInfo) >= 20) {
            $isMore0 = 1;
        }

        $this->assign([
            'baseinfo' => $baseinfoDataInfo,
            'isMore0'  => $isMore0,
            'bothlist' => $bothlistDataInfo,
            'mynavid'  => 3,
            'navid'    => -1
        ]);

        return $this->fetch();
    }


    /**
     * 语音
     */
    public function addAudio()
    {
        $file = $_FILES['file'];
        /* var_dump($file); */
        if (!$file) {
            $this->error('请先录制语音');
        }
        $_FILES['file']['name'] = $_FILES['file']['name'] . '.mp3';

        $res = upload($file, 'audio');

        if ($res['code'] != 0) {
            $this->error($res['msg']);
        }
        $url = get_upload_path($res['url']);
        $this->success("发送成功！", '', $url);
    }


    /**
     * 账号设置
     * @return mixed
     */
    public function mybase()
    {

        //判断有没有登录
        $this->checkMyLogin();
        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo = curl_get($url);
        $this->assign('baseinfo', $baseinfo['data']['info'][0]);
        $this->assign('mynavid', 5);
        $this->assign('navid', -1);

        return $this->fetch();

    }

    /**
     * 上传头像
     */
    public function uploadImg()
    {

        $data = $this->request->param();

        $uid = session('student.id');
        if (!$uid) {
            $uid = $_SESSION['student']['id'] ?? 0;
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

        $data = [
            'avatar' => $res['url'],
            'url'    => get_upload_path($res['url'])
        ];

        $_SESSION['student']['avatar']       = get_upload_path($res['url']);
        $_SESSION['student']['avatar_thumb'] = get_upload_path($res['url']);

        $this->success('操作成功', '', $data);
    }


    /**
     * 我的课件
     * @return mixed
     */
    public function message()
    {

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo = curl_get($url);
        $this->assign('baseinfo', $baseinfo['data']['info'][0]);

        //系统消息
        $url = $this->siteUrl . '/api/?s=Message.GetList&uid=' . $uid . '&token=' . $token . '&type=0';

        $syslist = curl_get($url);
        $this->assign('syslist', $syslist['data']['info']);

        //课程消息
        $url = $this->siteUrl . '/api/?s=Message.GetList&uid=' . $uid . '&token=' . $token . '&type=1';

        $classlist = curl_get($url);
        $this->assign('classlist', $classlist['data']['info']);

        //讲师消息
        $url = $this->siteUrl . '/api/?s=Message.GetList&uid=' . $uid . '&token=' . $token . '&type=2';

        $teacherlist = curl_get($url);
        $this->assign('teacherlist', $teacherlist['data']['info']);
        $this->assign('mynavid', 6);
        $this->assign('navid', -1);

        return $this->fetch();
    }


    /**
     * 关注的讲师
     * @return mixed
     */
    public function follows()
    {
        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];

        $uid   = $userinfo['id'];
        $token = $userinfo['token'];

        //用户信息
        $url = $this->siteUrl . '/api/?s=User.GetBaseInfo&uid=' . $uid . '&token=' . $token;

        $baseinfo = curl_get($url);
        $this->assign('baseinfo', $baseinfo['data']['info'][0]);


        //关注的讲师
        $url = $this->siteUrl . '/api/?s=User.GetFollow&uid=' . $uid . '&token=' . $token . '&p=1';

        $followslist = curl_get($url);
        $this->assign('followslist', $followslist['data']['info']);

        $isMore = 0;
        if (count($followslist['data']['info']) >= 50) {
            $isMore = 1;
        }

        $this->assign('isMore', $isMore);
        $this->assign('mynavid', -1);
        $this->assign('navid', -1);
        return $this->fetch();
    }


}