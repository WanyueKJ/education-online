<?php

namespace App\Api;

use PhalApi\Api;
use App\Domain\Login as Domain_Login;

header("Access-Control-Allow-Origin: *");

/**
 * 注册、登录
 */
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

class Login extends Api
{
    public function getRules()
    {
        return array(
            'loginByCode' => array(
                'username' => array('name' => 'username', 'type' => 'string', 'desc' => '用户名'),
                'code'     => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
                'source'   => array('name' => 'source', 'type' => 'int', 'default' => '0', 'desc' => '来源设备,0web，1android，2ios，3小程序'),
                'pushid'   => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
                'env'      => array('name' => 'env', 'type' => 'string', 'desc' => '环境'),
            ),

            'getCode' => array(
                'account' => array('name' => 'account', 'type' => 'string', 'desc' => '手机号码'),
                'type'    => array('name' => 'type', 'type' => 'int', 'desc' => '类型，0登录1注册2忘记密码'),
                'sign'    => array('name' => 'sign', 'type' => 'string', 'default' => '', 'desc' => '签名'),
                'env'     => array('name' => 'env', 'type' => 'string', 'default' => '', 'desc' => '环境'),
            ),

            'login' => array(
                'username' => array('name' => 'username', 'type' => 'string', 'desc' => '用户名'),
                'pass'     => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
                'pushid'   => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
            ),

            'reg' => array(
                'username' => array('name' => 'username', 'type' => 'string', 'desc' => '用户名'),
                'code'     => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
                'pass'     => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
                'source'   => array('name' => 'source', 'type' => 'int', 'default' => '0', 'desc' => '来源设备,0web，1android，2ios，3小程序'),
                'pushid'   => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
                'env'      => array('name' => 'env', 'type' => 'string', 'desc' => '环境'),
            ),

            'forget' => array(
                'username' => array('name' => 'username', 'type' => 'string', 'desc' => '用户名'),
                'pass'     => array('name' => 'pass', 'type' => 'string', 'desc' => '密码'),
                'code'     => array('name' => 'code', 'type' => 'string', 'desc' => '验证码'),
                'env'      => array('name' => 'env', 'type' => 'string', 'desc' => '环境'),
            ),

            'loginByThird' => array(
                'openid'   => array('name' => 'openid', 'type' => 'string', 'desc' => '第三方openid'),
                'type'     => array('name' => 'type', 'type' => 'int', 'default' => '0', 'desc' => '第三方标识,0PC，1QQ，2微信，3新浪，4facebook，5twitter'),
                'nicename' => array('name' => 'nicename', 'type' => 'string', 'default' => '', 'desc' => '第三方昵称'),
                'avatar'   => array('name' => 'avatar', 'type' => 'string', 'default' => '', 'desc' => '第三方头像'),
                'sign'     => array('name' => 'sign', 'type' => 'string', 'default' => '', 'desc' => '签名'),
                'source'   => array('name' => 'source', 'type' => 'int', 'default' => '0', 'desc' => '来源设备,0web，1android，2ios，3小程序'),
                'pushid'   => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光推送ID'),
            ),

            'getUnionid' => array(
                'code' => array('name' => 'code', 'type' => 'string', 'desc' => '微信登录code'),
            )

        );
    }


    /**
     * 登录方式开关信息
     * @desc 用于获取登录方式开关信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[][0] 登录方式标识
     * @return string msg 提示信息
     */

    public function getLoginType()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $configpri  = \App\getConfigPri();
        $rs['info'] = $configpri['login_type'];

        return $rs;
    }


    /**
     * 获取注册验证码
     * @desc 用于注册获取验证码
     * @return int code 操作码，0表示成功,2发送失败
     * @return array info
     * @return string msg 提示信息
     */
    public function getCode()
    {

        $rs = array('code' => 0, 'msg' => \PhalApi\T('发送成功，请注意查收'), 'info' => array());

        $account = \App\checkNull($this->account);
        $type    = \App\checkNull($this->type);
        $sign    = \App\checkNull($this->sign);
        $env     = \App\checkNull($this->env);

        if ($account == '') {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入手机号');
            return $rs;
        }

        $isok = \App\checkMobile($account);
        if (!$isok) {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入正确的手机号');
            return $rs;
        }

        $checkdata = array(
            'account' => $account,
        );

        $issign = \App\checkSign($checkdata, $sign);
        if (!$issign) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('签名错误');
            return $rs;
        }

        $where   = ['user_login=?' => $account];
        $isexist = \App\checkUser($where);
        if ($type == 0) {
            /* 登陆 */
            if (!$isexist) {
                $rs['code'] = 1004;
                $rs['msg']  = \PhalApi\T('该手机号尚未注册，请先注册');
                return $rs;
            }
            $s_a = 'login_account';
            $s_c = 'login_code';
            $s_e = 'login_expiretime';

        }

        if ($type == 1) {
            /* 注册 */
            if ($isexist) {
                $rs['code'] = 1002;
                $rs['msg']  = \PhalApi\T('该手机号已注册，请登录');
                return $rs;
            }

            $s_a = 'reg_account';
            $s_c = 'reg_code';
            $s_e = 'reg_expiretime';
        }

        if ($type == 2) {
            /* 忘记密码 */
            if (!$isexist) {
                $rs['code'] = 1003;
                $rs['msg']  = \PhalApi\T('该手机号尚未注册，请先注册');
                return $rs;
            }

            $s_a = 'forget_account';
            $s_c = 'forget_code';
            $s_e = 'forget_expiretime';
        }


        if (isset($_SESSION[$s_a]) && $_SESSION[$s_a] == $account && isset($_SESSION[$s_e]) && $_SESSION[$s_e] > time()) {
            $rs['code'] = 996;
            $rs['msg']  = \PhalApi\T('验证码5分钟有效，请勿多次发送');
            return $rs;
        }

        $code = \App\random(6);

        /* 发送验证码 */
        $result = \App\sendCode($account, $code);

        $currentTime = time();

        if ($result['code'] == 0) {

            $_SESSION[$s_a] = $account;
            $_SESSION[$s_c] = $code;
            $_SESSION[$s_e] = $currentTime + 60 * 5;

        } else if ($result['code'] == 667) {
            $_SESSION[$s_a] = $account;
            $_SESSION[$s_c] = $result['msg'];
            $_SESSION[$s_e] = $currentTime + 60 * 5;

            $rs['code'] = 1002;
            $rs['msg']  = \PhalApi\T('验证码为：{n}', ['n' => $result['msg']]);

            if (($env != '') && ($env == 'weixin')) {
                //环境 小程序验证码存库 (无法存session)
                $verifiData = [
                    'send_time'   => $currentTime,
                    'expire_time' => $currentTime + (60 * 5),
                    'code'        => '123456',
                    'account'     => $account,
                ];
                \PhalApi\DI()->notorm->verification_code->insert($verifiData);
            }

        } else {
            $rs['code'] = 1002;
            $rs['msg']  = $result['msg'];
        }

        return $rs;
    }


    /**
     * 验证码登录
     * @desc 用于用户通过验证码登录
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nickname 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].type 类型，0学生1讲师
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function loginByCode()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $username = \App\checkNull($this->username);
        $code     = \App\checkNull($this->code);
        $source   = \App\checkNull($this->source);
        $pushid   = \App\checkNull($this->pushid);
        $env = \App\checkNull($this->env);

        if ($username == '') {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入手机号');
            return $rs;
        }

        if ($code == '') {
            $rs['code'] = 996;
            $rs['msg']  = \PhalApi\T('请输入验证码');
            return $rs;
        }

        if (($env != '') && $env == 'weixin') {

            $rs['code'] = 996;
            $vData = \PhalApi\DI()->notorm->verification_code
                ->select("id,send_time,expire_time,code,account")
                ->where('code=?', $code)
                ->order('send_time desc')
                ->fetchOne();
            if (!$vData) {
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

            if (time() > $vData['expire_time']) {
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($vData['account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

        } else {

            if (!isset($_SESSION['login_account']) || !$_SESSION['login_account'] || !isset($_SESSION['login_code']) || !$_SESSION['login_code']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('请先获取验证码');
                return $rs;
            }

            if (time() > $_SESSION['login_expiretime']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($_SESSION['login_account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

            if ($_SESSION['login_code'] != $code) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

        }


        $domain = new Domain_Login();
        $info   = $domain->loginByCode($username, $source);

        if ($info['code'] == 0) {
            $domain->upUserPush($info['info'][0]['id'], $pushid);
        }

        return $info;
    }


    /**
     * 密码登录
     * @desc 用于用户通过密码登录
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nickname 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function login()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $username = \App\checkNull($this->username);
        $pass     = \App\checkNull($this->pass);
        $pushid   = \App\checkNull($this->pushid);

        if ($username == '') {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入手机号');
            return $rs;
        }

        if ($pass == '') {
            $rs['code'] = 997;
            $rs['msg']  = \PhalApi\T('请输入密码');
            return $rs;
        }

        $domain = new Domain_Login();
        $info   = $domain->login($username, $pass);

        if ($info['code'] == 0) {
            $domain->upUserPush($info['info'][0]['id'], $pushid);
        }
        return $info;
    }

    /**
     * 手机号注册
     * @desc 用于用户通过手机号注册
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nickname 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function reg()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $username = \App\checkNull($this->username);
        $pass     = \App\checkNull($this->pass);
        $code     = \App\checkNull($this->code);
        $source   = \App\checkNull($this->source);
        $pushid   = \App\checkNull($this->pushid);
        $env = \App\checkNull($this->env);

        if ($username == '') {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入手机号');
            return $rs;
        }

        if ($code == '') {
            $rs['code'] = 996;
            $rs['msg']  = \PhalApi\T('请输入验证码');
            return $rs;
        }

        if ($pass == '') {
            $rs['code'] = 997;
            $rs['msg']  = \PhalApi\T('请输入密码');
            return $rs;
        }

        if (!\App\checkPass($pass)) {
            $rs['code'] = 997;
            $rs['msg']  = \PhalApi\T('密码为6-20位字母数字组合');
            return $rs;
        }


        if (($env != '') && $env == 'weixin') {

            $rs['code'] = 996;
            $vData = \PhalApi\DI()->notorm->verification_code
                ->select("id,send_time,expire_time,code,account")
                ->where('code=?', $code)
                ->order('send_time desc')
                ->fetchOne();
            if (!$vData) {
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

            if (time() > $vData['expire_time']) {
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($vData['account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

        } else {

            if (!isset($_SESSION['login_account']) || !$_SESSION['login_account'] || !isset($_SESSION['login_code']) || !$_SESSION['login_code']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('请先获取验证码');
                return $rs;
            }

            if (time() > $_SESSION['login_expiretime']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($_SESSION['login_account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

            if ($_SESSION['login_code'] != $code) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

        }


        $where   = ['user_login=?' => $username];
        $isexist = \App\checkUser($where);
        if ($isexist) {
            $rs['code'] = 1004;
            $rs['msg']  = \PhalApi\T('该手机号已注册，请登录');
            return $rs;

        }

        $domain = new Domain_Login();
        $info   = $domain->reg($username, $pass, $source);

        if ($info['code'] == 0) {
            $domain->upUserPush($info['info'][0]['id'], $pushid);
        }

        return $info;
    }

    /**
     * 忘记密码
     * @desc 用于用户忘记密码操作
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function forget()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $username = \App\checkNull($this->username);
        $pass     = \App\checkNull($this->pass);
        $code     = \App\checkNull($this->code);
        $env = \App\checkNull($this->env);

        if ($username == '') {
            $rs['code'] = 995;
            $rs['msg']  = \PhalApi\T('请输入手机号');
            return $rs;
        }

        if ($code == '') {
            $rs['code'] = 996;
            $rs['msg']  = \PhalApi\T('请输入验证码');
            return $rs;
        }

        if ($pass == '') {
            $rs['code'] = 997;
            $rs['msg']  = \PhalApi\T('请输入密码');
            return $rs;
        }

        if (!\App\checkPass($pass)) {
            $rs['code'] = 997;
            $rs['msg']  = \PhalApi\T('密码为6-20位字母数字组合');
            return $rs;
        }


        if (($env != '') && $env == 'weixin') {

            $rs['code'] = 996;
            $vData = \PhalApi\DI()->notorm->verification_code
                ->select("id,send_time,expire_time,code,account")
                ->where('code=?', $code)
                ->order('send_time desc')
                ->fetchOne();
            if (!$vData) {
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

            if (time() > $vData['expire_time']) {
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($vData['account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

        } else {

            if (!isset($_SESSION['login_account']) || !$_SESSION['login_account'] || !isset($_SESSION['login_code']) || !$_SESSION['login_code']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('请先获取验证码');
                return $rs;
            }

            if (time() > $_SESSION['login_expiretime']) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码已过期，请重新获取');
                return $rs;
            }

            if ($_SESSION['login_account'] != $username) {
                $rs['code'] = 995;
                $rs['msg']  = \PhalApi\T('手机号码错误');
                return $rs;
            }

            if ($_SESSION['login_code'] != $code) {
                $rs['code'] = 996;
                $rs['msg']  = \PhalApi\T('验证码错误');
                return $rs;
            }

        }

        $domain = new Domain_Login();
        $info   = $domain->forget($username, $pass);

        return $info;
    }


    /**
     * 三方登录
     * @desc 用于用户三方登录
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nickname 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].level_anchor 主播等级
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function loginByThird()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $openid   = \App\checkNull($this->openid);
        $type     = \App\checkNull($this->type);
        $nicename = \App\checkNull($this->nicename);
        $avatar   = \App\checkNull($this->avatar);
        $source   = \App\checkNull($this->source);
        $sign     = \App\checkNull($this->sign);
        $pushid   = \App\checkNull($this->pushid);

        file_put_contents('a.txt', $openid);
        if ($openid == '' || $type == '' || $sign == '') {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $checkdata = array(
            'type'   => $type,
            'openid' => $openid
        );

        $issign = \App\checkSign($checkdata, $sign);
        if (!$issign) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('签名错误');
            return $rs;
        }

        $domain = new Domain_Login();

        $info   = $domain->userLoginByThird($openid, $type, $nicename, $avatar, $source);

        if ($info['code'] == 0) {
            $domain->upUserPush($info['info'][0]['id'], $pushid);
        }

        return $info;
    }


    /**
     * 获取unionid(小程序用)
     * @desc uni-app 小程序端登录时, 获取unionid
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].openid 用户唯一标识
     * @return string info[0].unionid 用户在开放平台的唯一标识符
     * @return string info[0].errcode 错误码 -1 系统繁忙 0 请求成功 40029 code无效 45011 频率限制
     * @return string info[0].errmsg 错误信息
     */
    public function getUnionid()
    {

        $rs   = array('code' => 0, 'msg' => '', 'info' => array());
        $code = \App\checkNull($this->code);

        if ($code == '') {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $wx_url = "https://api.weixin.qq.com/sns/jscode2session?appid=wxa78ad20db2e78eb9&secret=88781cfcd01eaca7abcdd733571f1159&js_code=" . $code
            . "&grant_type=authorization_code";
        $curl   = new \PhalApi\CUrl();
        $rs     = $curl->get($wx_url, 3000);

        $arr = json_decode($rs, true);
        return $arr;
        // 输出类似如下：
        // {"ret":200,"data":{"title":"Hello dogstar","version":"2.1.2","time":1513506356},"msg":""}


    }


}
