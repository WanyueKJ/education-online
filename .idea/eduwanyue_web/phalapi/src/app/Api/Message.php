<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/02 10:22
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\Message as Domain_Message;
use App\Domain\User as Domain_User;

/**
 * 消息
 */
class Message extends Api
{

    public function getRules()
    {
        return array(
            'getList' => array(
                'lastid' => array('name' => 'lastid', 'type' => 'int', 'default' => '0', 'desc' => '最后一条id，首次为0'),
                'type'   => array('name' => 'type', 'type' => 'int', 'default' => '0', 'desc' => '类型，0系统1课程2讲师'),
            ),
        );
    }

    /**
     * 最新的一条消息
     * @desc 用于获取最新的一条消息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0].sys 系统消息
     * @return array info[0].course 课程消息
     * @return array info[0].teacher 讲师消息
     * @return string msg 提示信息
     */
    public function getNew()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);


        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $where = [];

        $where[" ( touid = '' or  touid like ?)"] = '%[' . $uid . ']%';

        $nums = 1;

        $where4      = ['id' => $uid];
        $Domain_User = new Domain_User();
        $userinfo    = $Domain_User->getUsersInfo($where4);

        $domain              = new Domain_Message();
        $where1              = $where;
        $where1['type']      = 0;
        $where1['addtime>?'] = $userinfo['create_time'];
        $list1               = $domain->getList($where1, $nums);

        $where2         = $where;
        $where2['type'] = 1;
        $list2          = $domain->getList($where2, $nums);

        $where3         = $where;
        $where3['type'] = 2;
        $list3          = $domain->getList($where3, $nums);

        $info['sys']     = $list1;
        $info['course']  = $list2;
        $info['teacher'] = $list3;

        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 消息列表
     * @desc 用于获取消息列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[].id 消息ID
     * @return string info[].content 消息内容
     * @return string info[].addtime 时间
     * @return string msg 提示信息
     */
    public function getList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid    = \App\checkNull($this->uid);
        $token  = \App\checkNull($this->token);
        $lastid = \App\checkNull($this->lastid);
        $type   = \App\checkNull($this->type);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if (!$type) {
            $type = 0;
        }
        if (!$lastid) {
            $lastid = 0;
        }
        $where = [
            'type' => $type,
        ];

        if ($type == 0) {
            $where4             = ['id' => $uid];
            $Domain_User        = new Domain_User();
            $userinfo           = $Domain_User->getUsersInfo($where4);
            $where['addtime>?'] = $userinfo['create_time'];
        }

        $where[" ( touid = '' or  touid like ?)"] = '%[' . $uid . ']%';

        if ($lastid > 0) {
            $where['id < ?'] = $lastid;
        }

        $domain = new Domain_Message();
        $list   = $domain->getList($where);

        $rs['info'] = $list;

        return $rs;
    }


}
