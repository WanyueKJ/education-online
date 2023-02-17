<?php

namespace App\Api;

use PhalApi\Api;
use App\Domain\Agent as Domain_Agent;

/**
 * 推广
 */
class Agent extends Api
{

    public function getRules()
    {
        return array(
            'getAgentList' => array(
                'p' => array('name' => 'p', 'type' => 'int', 'default' => '1', 'desc' => '页码'),
            ),

            'checkAgent' => array(
                'type' => array('name' => 'type', 'type' => 'int', 'default' => '0', 'desc' => '类型 1 isreg=1时'),
            ),

            'setAgent' => array(
                'code' => array('name' => 'code', 'type' => 'string', 'default' => '', 'desc' => '邀请码'),
            ),
            'getShareImage' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'string', 'default' => '', 'desc' => '课程id'),
                'type'     => array('name' => 'type', 'type' => 'string', 'default' => '', 'desc' => '分享类型 1 课程 2套餐')
            )
        );
    }

    /**
     * 我的推广信息
     * @desc 用于获取推广页面数据
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].code 邀请码
     * @return string info[0].income 累计收益
     * @return string info[0].nums 人数
     * @return string info[0].isagent 是否推广员 0否1是
     * @return string info[0].istips 提醒 0不提醒 1 提醒非推广员  2 提醒审核中 3 认证失败 4 提醒成功
     * @return string info[0].isshowauth 是否显示认证  0否1是
     * @return array  info[0].tips 推广说明
     * @return string msg 提示信息
     */
    public function myAgent()
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

        $domain = new Domain_Agent();
        $info   = $domain->myAgent($uid);

        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 推广列表
     * @desc 用于获取推广列表
     * @return int code 操作码，0表示成功
     * @return array info 用户列表
     * @return string msg 提示信息
     */
    public function getAgentList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $p     = \App\checkNull($this->p);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $where  = ['pid' => $uid];
        $domain = new Domain_Agent();
        $info   = $domain->getAgentList($where, $p);

        $rs['info'] = $info;

        return $rs;
    }


    /**
     * 获取邀请码
     * @desc 用于获取邀请码
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string info[0].code 邀请码
     * @return string msg 提示信息
     */
    public function getCode()
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

        $domain = new Domain_Agent();
        $code   = $domain->getCode($uid);

        $info          = [
            'code' => $code,
        ];
        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 检测邀请弹窗
     * @desc 用于获取邀请码
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string info[0].ispop 是否弹窗 0否1是
     * @return string info[0].ismust 是否必填 0否1是
     * @return string msg 提示信息
     */
    public function checkAgent()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $type  = \App\checkNull($this->type);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Agent();
        $info   = $domain->checkAgent($uid, $type);

        $rs['info'][0] = $info;

        return $rs;
    }


    /**
     * 设置邀请码
     * @desc 用于设置邀请码
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string msg 提示信息
     */
    public function setAgent()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $code  = \App\checkNull($this->code);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        if ($code == '') {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('请输入邀请码');
            return $rs;
        }
        $domain = new Domain_Agent();
        $res    = $domain->setAgent($uid, $code);

        return $res;
    }


    /**
     * 获取推广海报图片地址
     * @desc 用于获取海报推广图片地址
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string msg 提示信息
     */
    public function getPopuImage()
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

        $domain = new Domain_Agent();
        $res    = $domain->getPopuImage($uid);

        return $res;
    }


    /**
     * 获取分享海报图片地址
     * @desc 用户获取分享海报图片地址
     * @return int code 操作码，0表示成功
     * @return array  info
     * @return string msg 提示信息
     */
    public function getShareImage()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid   = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        $type = \App\checkNull($this->type);

        $checkToken = \App\checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg']  = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }

        $domain = new Domain_Agent();
        $res    = $domain->getShareImage($uid, $courseid, $type);

        return $res;

    }





}
