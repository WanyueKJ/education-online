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

use app\admin\model\UsersModel;
use cmf\controller\HomeBaseController;
use think\Db;

/**
 * 登录
 */
class LoginController extends HomebaseController
{

    //首页
    public function index()
    {
        $redirect = $this->request->param("redirect");
        if (empty($redirect)) {
            $redirect = $this->request->server('HTTP_REFERER');
        } else {
            if (strpos($redirect, '/') === 0 || strpos($redirect, 'http') === 0) {
            } else {
                $redirect = base64_decode($redirect);
            }
        }
        if (!empty($redirect)) {
            session('login_http_referer', $redirect);
        }

        if (session('teacher')) { //已经登录时直接跳到首页
            return redirect(cmf_url("teacher/index/index"));
        } else {
            return $this->fetch();
        }
    }

    /* 手机验证码 */
    public function getCode()
    {

        $rs = ['code' => 0, 'msg' => '', 'info' => []];

        $data   = $this->request->param();
        $type   = $data['type'] ?? '0';
        $mobile = $data['mobile'] ?? '';

        $mobile = checkNull($mobile);

        if ($mobile == '') {
            $this->error('请输入手机号');
        }

        $where['user_login'] = $mobile;

        $checkuser = checkUser($where);

        if ($type == 1) {
            /* 忘记密码 */
            if (!$checkuser) {
                $this->error('该手机号尚未注册，请先注册');
            }

            $s_a = 'forget_account';
            $s_c = 'forget_code';
            $s_e = 'forget_expiretime';
        } else {
            /* 登录 */
            if (!$checkuser) {
                $this->error('该手机号尚未注册，请先注册');
            }

            $s_a = 'login_account';
            $s_c = 'login_code';
            $s_e = 'login_expiretime';
        }

        $nowtime = time();

        if (session($s_a) == $mobile && session($s_e) > $nowtime) {
            $this->error('验证码5分钟有效，请勿多次发送');
        }

        $mobile_code = random(6, 1);

        //密码可以使用明文密码或使用32位MD5加密
        $result = sendCode($mobile, $mobile_code);
        if ($result['code'] == 0) {
            session($s_a, $mobile);
            session($s_c, $result['msg']);
            session($s_e, time() + 60 * 5);

        } else if ($result['code'] == 667) {
            session($s_a, $mobile);
            session($s_c, $result['msg']);
            session($s_e, time() + 60 * 5);

            $this->error("验证码为：{$result['msg']}");
        } else {
            $this->error($result['msg']);
        }

        $this->success('验证码已送');
    }


    /* 密码登录 */
    public function loginbypass()
    {

        $data = $this->request->param();
        $name = $data['name'] ?? '';
        $pass = $data['pass'] ?? '';
        $name = checkNull($name);
        $pass = checkNull($pass);

        if ($name == '') {
            $this->error('请输入手机号');
        }
        if ($pass == '') {
            $this->error('请输入密码');
        }

        $user_pass = cmf_password($pass);

        $where['user_login'] = $name;
        $userinfo            = UsersModel::where($where)->find();

        if (!$userinfo || $userinfo['user_pass'] != $user_pass) {
            $this->error('账号或密码错误');
        }
        $this->handleInfo($userinfo);
        $this->success('登陆成功');

    }

    /* 验证码登录 */
    public function loginbycode()
    {

        $data = $this->request->param();
        $name = $data['name'] ?? '';
        $code = $data['code'] ?? '';
        $name = checkNull($name);
        $code = checkNull($code);

        if ($name == '') {
            $this->error('请输入手机号');
        }
        if ($code == '') {
            $this->error('请输入验证码');
        }

        if (!session('?login_account') || !session('?login_code')) {
            $this->error('请先获取验证码');
        }

        if (!session('?login_expiretime') || session('login_expiretime') < time()) {
            $this->error('验证码已过期');
        }

        if ($name != session('login_account')) {
            $this->error('手机号码不一致');
        }

        if ($code != session('login_code')) {
            $this->error('验证码错误');
        }

        $where['user_login'] = $name;
        $userinfo            = UsersModel::where($where)->find()->toArray();

        $this->handleInfo($userinfo);

        $this->success('登陆成功');
    }

    public function forget()
    {

        $data = $this->request->param();
        $name = $data['name'] ?? '';
        $pass = $data['pass'] ?? '';
        $code = $data['code'] ?? '';
        $name = checkNull($name);
        $pass = checkNull($pass);
        $code = checkNull($code);

        if ($name == '') {
            $this->error('请输入手机号');
        }
        if ($code == '') {
            $this->error('请输入验证码');
        }

        if ($pass == '') {
            $this->error('请输入密码');
        }

        if (!session('?forget_account') || !session('?forget_code')) {
            $this->error('请先获取验证码');
        }

        if (!session('?forget_expiretime') || session('forget_expiretime') < time()) {
            $this->error('验证码已过期');
        }

        if ($name != session('forget_account')) {
            $this->error('手机号码不一致');
        }

        if ($code != session('forget_code')) {
            $this->error('验证码错误');
        }

        $check = checkPass($pass);

        if (!$check) {
            $this->error('密码为6-20位数字与字母组合');
        }

        $user_pass = cmf_password($pass);

        $where['user_login'] = $name;
        $ifreg               = DB::name('users')->field("id")->where($where)->find();
        if (!$ifreg) {
            $this->error('该帐号不存在');
        }
        $result = DB::name('users')->where("id",$ifreg['id'])->setField("user_pass", $user_pass);
        if ($result === false) {
            $this->error('重置失败，请重试');
        }
        $this->success('操作成功');
    }

    /**
     * 退出
     */
    public function logout()
    {

        session('teacher', null);

        $this->success('退出登录');
    }

    /**
     * qq第三方登陆
     */
    public function qq()
    {
        $href = $_SERVER['HTTP_REFERER'];
        cookie('href', $href, 3600000);
        cookie('identity', 'teacher', 3600000);
        $referer = $_SERVER['HTTP_REFERER'];
        session('login_referer', $referer);

        require_once CMF_ROOT . 'sdk/qqApi/qqConnectAPI.class.php';

        $qc1 = new \QC();
        $qc1->qq_login();
    }

    public function qqCallback()
    {

        require_once CMF_ROOT . 'sdk/qqApi/qqConnectAPI.class.php';

        $qc    = new \QC();
        $token = $qc->qq_callback();

        $openid2 = $qc->get_openid();
        $openid  = $openid2['openid'];
        $unionid = $openid2['unionid'];

        $qq  = new \QC($token, $openid);
        $arr = $qq->get_user_info();

        $type     = '1';
        $openid   = $unionid;
        $nickname = $arr['nickname'];
        $avatar   = $arr['figureurl_qq_2'];

        $identity = cookie('identity');
        if ($identity == 'teacher') {
            $this->loginByThird($type, $openid, $nickname, $avatar);
        } else {
            $this->loginByThirdStudent($type, $openid, $nickname, $avatar);
        }

        $href = cookie('href');
        echo "<meta http-equiv=refresh content='0; url=$href'>";
        exit;
    }

    /**
     * 微信登陆
     **/
    public function weixin()
    {
        $configpri = getConfigPri();

        //-------配置
        $href = $_SERVER['HTTP_REFERER'];
        cookie('href', $href, 3600000);
        $AppID     = $configpri['wx_appid_pc'];
        $AppSecret = $configpri['wx_appsecret_pc'];
        $callback  = get_upload_path('/teacher/login/weixin_callback'); //回调地址
        //微信登录
        //-------生成唯一随机串防CSRF攻击
        $state                = md5(uniqid(rand(), TRUE));
        $_SESSION["wx_state"] = $state; //存到SESSION
        $callback             = urlencode($callback);
        $wxurl                = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $AppID . "&redirect_uri={$callback}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
        header("Location: $wxurl");
    }

    /**
     * 微信登陆回调
     **/
    public function weixin_callback()
    {
        $configpri = getConfigPri();
        $code      = $_GET['code'] ?? '';
        if ($code != "") {
            $AppID     = $configpri['wx_appid_pc'];
            $AppSecret = $configpri['wx_appsecret_pc'];
            $url       = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $AppID . '&secret=' . $AppSecret . '&code=' . $code . '&grant_type=authorization_code';
            $ch        = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
            $arr = json_decode($json, 1);

            if (isset($arr['errcode'])) {
                $this->error($arr['errmsg']);
            }

            //得到 access_token 与 openid
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $arr['access_token'] . '&openid=' . $arr['openid'] . '&lang=zh_CN';
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
            $arr = json_decode($json, 1);
            //得到 用户资料
            // $openid=$arr['openid'];
            $openid = $arr['unionid'];

            $type     = '2';
            $openid   = $openid;
            $nickname = $arr['nickname'];
            $avatar   = $arr['headimgurl'];

            $this->loginByThird($type, $openid, $nickname, $avatar);

        }
    }

    protected function loginByThirdStudent($type, $openid, $nickname, $avatar)
    {
        $userinfo = DB::name('users')
          //  ->where("openid='{$openid}' and login_type='{$type}'")
            ->where("openid",$openid)
            ->where("login_type",$type)
            ->find();

        if (!$userinfo) {
            $nowtime = time();
            /* 注册 */
            $type_a     = ['web', 'qq', 'wx', 'sina', 'facebook', 'twitter'];
            $user_login = $type_a[$type] . '_' . $nowtime . rand(100, 999);

            if (!$nickname) {
                //$nickname=\PhalApi\T('用户').substr($openid,-3);
                $nickname = '';
            }

            $data = array(
                'user_login'    => $user_login,
                'user_nickname' => $nickname,
                "source"        => 'web',
                "openid"        => $openid,
                "login_type"    => $type,
            );

            if ($avatar) {
                $avatar       = htmlspecialchars_decode($avatar);
                $avatar_thumb = $avatar;

                $data['avatar']       = $avatar;
                $data['avatar_thumb'] = $avatar_thumb;
            }

            $user_pass = 'edu' . $nowtime;
            $user_pass = cmf_password($user_pass);

            $avatar       = '/default.png';
            $avatar_thumb = '/default_thumb.png';

            $default = array(
                'user_pass'     => $user_pass,
                'signature'     => '',
                'avatar'        => $avatar,
                'avatar_thumb'  => $avatar_thumb,
                'last_login_ip' => get_client_ip(),
                'create_time'   => $nowtime,
                'user_status'   => 1,
            );

            if (isset($data['user_pass'])) {
                $data['user_pass'] = cmf_password($data['user_pass']);
            }
            $insert = array_merge($default, $data);


            $rs = Db::name('users')->insertGetId($insert);

            $id = $rs;

            $userinfo = Db::name('users')
                ->where(['id' => $id])
                ->find();
        }
        $this->handleInfoStudent($userinfo);
        $href = cookie('href');
        echo "<meta http-equiv=refresh content='0; url=$href'>";
        exit;
    }

    protected function loginByThird($type, $openid, $nickname, $avatar)
    {

        $userinfo = DB::name('users')
            //->where("openid='{$openid}' and login_type='{$type}'")
			->where("openid",$openid)
            ->where("login_type",$type)
            ->find();

        $this->handleInfo($userinfo);

        $href = cookie('href');
        echo "<meta http-equiv=refresh content='0; url=$href'>";

        exit;
    }

    /* 更新学生token 登陆信息 */
    protected function handleInfoStudent($userinfo)
    {
        if (!$userinfo) {
            $this->error('账号未注册');
        }

        if ($userinfo['user_status'] == 0) {
            $this->error('账号已被禁用');
        }

        $token             = md5(md5($userinfo['id'] . $userinfo['user_login'] . time()));
        $userinfo['token'] = $token;


        $this->updateTokenStudent($userinfo['id'], $userinfo['token']);

        $gradeid   = session('student.gradeid');
        $gradeinfo = Db::name('course_grade')->where(['id' => $gradeid])->find();

        if ($gradeinfo) {
            $gradename = $gradeinfo['name'];
        } else {
            $gradename = '';
        }

        $userinfo['gradename'] = $gradename;
        session('student', $userinfo);


    }

    /* 更新学生token 登陆信息 */
    protected function updateTokenStudent($uid, $token)
    {
        $nowtime = time();

        $expiretime = $nowtime + 60 * 60 * 24 * 300;


        $ip     = get_client_ip();
        $ipinfo = Db::name('ip_garde')
            ->where(['ip' => $ip])
            ->find();

        $gradeid = 0;
        if ($ipinfo) {
            $gradeid = $ipinfo['gardeid'];
        }

        DB::name("users")
           // ->where("id={$uid}")
            ->where("id",$uid)
            ->update(array('last_login_time' => $nowtime, "last_login_ip" => get_client_ip(0, true), 'gradeid' => $gradeid));

        $isok = DB::name("users_token")
         //   ->where("user_id={$uid}")
            ->where("user_id",$uid)
            ->update(array("token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
        if (!$isok) {
            DB::name("users_token")
                ->insert(array("user_id" => $uid, "token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
        }

        $token_info = array(
            'uid'         => $uid,
            'token'       => $token,
            'expire_time' => $expiretime,
        );

        setcaches("token_" . $uid, $token_info);
        /* 删除PUSH信息 */
        DB::name("users_pushid")->where("uid",$uid)->delete();

        return 1;
    }

    /* 更新token 登陆信息 */
    protected function handleInfo($userinfo)
    {
        if (!$userinfo) {
            $this->error('账号未注册');
        }

        if ($userinfo['user_status'] == 0) {
            $this->error('账号已被禁用');
        }

        if ($userinfo['type'] != 1) {
            $this->error('您还不是讲师,无权登录');
        }

        $token             = md5(md5($userinfo['id'] . $userinfo['user_login'] . time()));
        $userinfo['token'] = $token;


        $this->updateToken($userinfo['id'], $userinfo['token']);

        session('teacher', $userinfo);


    }

    /* 更新token 登陆信息 */
    protected function updateToken($uid, $token)
    {
        $nowtime = time();

        $expiretime = $nowtime + 60 * 60 * 24 * 300;

        DB::name("users")
            ->where("id",$uid)
            ->update(array('last_login_time' => $nowtime, "last_login_ip" => get_client_ip(0, true)));

        $isok = DB::name("users_token")
            ->where("user_id",$uid)
            ->update(array("token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
        if (!$isok) {
            DB::name("users_token")
                ->insert(array("user_id" => $uid, "token" => $token, "expire_time" => $expiretime, "create_time" => $nowtime));
        }

        $token_info = array(
            'uid'         => $uid,
            'token'       => $token,
            'expire_time' => $expiretime,
        );

        setcaches("token_" . $uid, $token_info);
        //删除PUSH信息
        DB::name("users_pushid")->where("uid",$uid)->delete();

        return 1;
    }
}


