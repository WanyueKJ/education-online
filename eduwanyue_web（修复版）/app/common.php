<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/08 13:43
// +----------------------------------------------------------------------
use think\Db;
use cmf\lib\Storage;
use cmf\lib\Upload;

// 应用公共文件
require_once dirname(__FILE__) . '/redis.php';

/* 去除NULL 判断空处理 主要针对字符串类型*/
function checkNull($checkstr)
{
    $checkstr = trim($checkstr);

    if (strstr($checkstr, 'null') || (!$checkstr && $checkstr != 0)) {
        $str = '';
    } else {
        $str = $checkstr;
    }
    return $str;
}

/* 校验签名 */
function checkSign($data, $sign)
{
    if ($sign == '') {
        return 0;
    }
    $key = '';
    $str = '';
    ksort($data);
    foreach ($data as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }
    $str     .= $key;
    $newsign = md5($str);

    if ($sign == $newsign) {
        return 1;
    }
    return 0;
}

/* 校验邮箱 */
function checkEmail($email)
{
    $preg = '/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/';
    $isok = preg_match($preg, $email);
    if ($isok) {
        return 1;
    }
    return 0;
}

/* 校验密码 */
function checkPass($pass)
{
    /* 必须包含字母、数字 */
    $preg = '/^(?=.*[A-Za-z])(?=.*[0-9])[a-zA-Z0-9~!@&%#_]{6,20}$/';
    $isok = preg_match($preg, $pass);
    if ($isok) {
        return 1;
    }
    return 0;
}

/* 检测用户是否存在 */
function checkUser($where)
{
    if ($where == '') {
        return 0;
    }

    $isexist = Db::name('users')->where($where)->find();
    if ($isexist) {
        return 1;
    }

    return 0;
}

/* 检验手机号 */
function checkMobile($mobile)
{
    $ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/", $mobile);
    if ($ismobile) {
        return 1;
    }

    return 0;

}

/* 随机数 */
function random($length = 6, $numeric = 1)
{
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if ($numeric) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash  = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max   = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}

/* 发送验证码 */
function sendCode($account, $code)
{
    $rs     = array('code' => 1001, 'msg' => '发送失败');
    $config = getConfigPri();

    if (!$config['sendcode_switch']) {
        $rs['code'] = 667;
        $rs['msg']  = '123456';
        return $rs;
    }

    $res = sendCodeByTx($account, $code);
    return $res;
}

/* 容联云短信验证码 */
function sendCodeByCCP($mobile, $code)
{
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $config = getConfigPri();

    require_once CMF_ROOT . 'sdk/ronglianyun/CCPRestSDK.php';

    //主帐号
    $accountSid = $config['ccp_sid'];
    //主帐号Token
    $accountToken = $config['ccp_token'];
    //应用Id
    $appId = $config['ccp_appid'];
    //请求地址，格式如下，不需要写https://
    $serverIP = 'app.cloopen.com';
    //请求端口
    $serverPort = '8883';
    //REST版本号
    $softVersion = '2013-12-26';

    $tempId = $config['ccp_tempid'];

    file_put_contents(CMF_ROOT . 'data/sendCode_ccp_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 post_data: accountSid:' . $accountSid . ";accountToken:{$accountToken};appId:{$appId};tempId:{$tempId}\r\n", FILE_APPEND);

    $rest = new \REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);

    $datas   = [];
    $datas[] = $code;

    $result = $rest->sendTemplateSMS($mobile, $datas, $tempId);
    file_put_contents(CMF_ROOT . 'data/sendCode_ccp_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 result:' . json_encode($result) . "\r\n", FILE_APPEND);

    if ($result == NULL) {
        $rs['code'] = 1002;
        $rs['msg']  = "发送失败";
        return $rs;
    }
    if ($result->statusCode != 0) {
        //TODO 添加错误处理逻辑
        $rs['code'] = 1002;
        $rs['msg']  = "发送失败";
        return $rs;
    }

    return $rs;
}

/**
 * 转化数据库保存图片的文件路径，为可以访问的url
 * @param string $file 文件路径，数据存储的文件相对路径
 * @param string $style 图片样式,支持各大云存储
 * @return string 图片链接
 */
function get_upload_path($file, $style = 'watermark')
{
    if (empty($file)) {
        return '';
    }

    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return cmf_get_domain() . $file;
    } else {

        $storage = Storage::instance();
        return $storage->getImageUrl($file, $style);
    }
}

/* 公共配置 */
function getConfigPub()
{
    $key    = 'getConfigPub';
    $config = getcaches($key);
    if (!$config) {
        $config = Db::name('option')
            ->field('option_value')
            ->where(['option_name' => 'site_info'])
            ->find();
        $config = json_decode($config['option_value'], true);
        setcaches($key, $config);
    }

    return $config;
}

/* 私密配置 */
function getConfigPri()
{
    $key    = 'getConfigPri';
    $config = getcaches($key);
    if (!$config) {
        $config = Db::name('option')
            ->field('option_value')
            ->where(['option_name' => 'configpri'])
            ->find();
        $config = json_decode($config['option_value'], true);
        setcaches($key, $config);
    }

    if (isset($config['login_type'])) {
        if (is_array($config['login_type'])) {

        } else if ($config['login_type']) {
            $config['login_type'] = preg_split('/,|，/', $config['login_type']);
        } else {
            $config['login_type'] = array();
        }
    }

    if (isset($config['share_type'])) {
        if (is_array($config['share_type'])) {

        } else if ($config['share_type']) {
            $config['share_type'] = preg_split('/,|，/', $config['share_type']);
        } else {
            $config['share_type'] = array();
        }
    }

    return $config;
}

/* 判断token */
function checkToken($uid, $token)
{
    if ($uid < 1 || $token == '') {
        return 700;
    }
    $key      = "token_" . $uid;
    $userinfo = hGetAll($key);
    if (!$userinfo) {
        $userinfo = Db::name('users_token')
            ->field('token,expire_time')
            ->where(['user_id' => $uid])
            ->find();
        if ($userinfo) {
            hMSet($key, $userinfo);
        }
    }

    if (!$userinfo || $userinfo['token'] != $token || $userinfo['expire_time'] < time()) {
        return 700;
    }

    return 0;

}

/* 用户基本信息 */
function getUserInfo($uid, $type = 0)
{
    $info = hGetAll("userinfo_" . $uid);
    if (!$info) {
        $info = Db::name('users')
            ->field('id,user_nickname,avatar,avatar_thumb,sex,signature,birthday,type,signoryid,identity')
            ->where(['id' => $uid])
            ->find();
        if ($info) {

        } else if ($type == 1) {
            return $info;

        } else {
            $info['id']            = $uid;
            $info['user_nickname'] = lang('用户不存在');
            $info['avatar']        = '/default.png';
            $info['avatar_thumb']  = '/default_thumb.png';
            $info['sex']           = '0';
            $info['signature']     = '';
            $info['birthday']      = '0';
        }
        if ($info) {
            hMSet("userinfo_" . $uid, $info);
        }
    }

    if ($info) {
        $info = handleUser($info);
    }

    return $info;
}

/* 处理用户信息 */
function handleUser($info)
{

    $info['avatar']       = get_upload_path($info['avatar']);
    $info['avatar_thumb'] = get_upload_path($info['avatar_thumb']);

    if (isset($info['signoryid'])) {
        $info['signory'] = getSignory($info['signoryid']);
    }

    if (isset($info['identity'])) {
        $info['identitys'] = getSignory($info['identity']);
    }

    return $info;
}


/* 年龄计算 */
function getAge($time = 0)
{
    if ($time <= 0) {
        return '';
    }
    $nowtime = time();
    $y_n     = date('Y', $nowtime);
    $y_b     = date('Y', $time);

    $age = $y_n - $y_b;

    return (string)$age;
}


/* 统计粉丝数 */
function getFansNum($uid)
{
    $nums = Db::name('users_attention')
        ->where(['touid' => $uid])
        ->count();
    return (string)$nums;
}

/* 统计关注数 */
function getFollowNum($uid)
{
    $nums = Db::name('users_attention')
        ->where(['uid' => $uid])
        ->count();
    return (string)$nums;
}

/* 是否关注 */
function isAttent($uid, $liveuid)
{
    if ($uid < 1 || $liveuid < 1 || $uid == $liveuid) {
        return '0';
    }

    $isok = Db::name('users_attention')
        ->field('*')
        ->where(['uid' => $uid, 'touid' => $liveuid])
        ->find();

    if ($isok) {
        return '1';
    }
    return '0';
}

/* 获取用户最新余额*/
function getUserCoin($uid)
{
    $info = Db::name('users')
        ->field('coin')
        ->where(['id' => $uid])
        ->find();
    return $info;
}

/* 扣费 */
function upCoin($uid, $total = 0, $type = 0)
{
    if ($uid < 1 || $total <= 0) {
        return 0;
    }
    if ($type == 1) {
        $ifok = Db::name('users')
            ->where([['id', '=', $uid], ['coin', '>=', $total]])
            ->dec('coin', $total)
            ->update();

        return $ifok;
    }
    $ifok = Db::name('users')
        ->where([['id', '=', $uid], ['coin', '>=', $total]])
        ->dec('coin', $total)
        ->inc('consumption', $total)
        ->update();
    return $ifok;
}

/* 退费 */
function addCoin($uid, $total = 0, $type = 0)
{
    if ($uid < 1 || $total <= 0) {
        return 0;
    }
    if ($type == 1) {
        $ifok = Db::name('users')
            ->where(['id' => $uid])
            ->inc('coin', $total)
            ->update();

        return $ifok;
    }
    $ifok = Db::name('users')
        ->where(['id' => $uid])
        ->inc('coin', $total)
        ->dec('consumption', $total)
        ->update();
    return $ifok;
}

/* 增加映票 */
function addVotes($uid, $votes = 0, $votestotal = 0)
{

    if ($uid < 1 || $votes <= 0) {
        return 0;
    }

    if (!$votestotal) {
        $ifok = Db::name('users')
            ->where(['id' => $uid])
            ->inc('votes', $votes)
            ->update();
        return $ifok;
    }
    $ifok = Db::name('users')
        ->where(['id' => $uid])
        ->inc('votes', $votes)
        ->inc('votestotal', $votestotal)
        ->update();
    return $ifok;
}

/* 扣除映票 */
function reduceVotes($uid, $votes = 0, $votestotal = 0)
{

    if ($uid < 1 || $votes <= 0) {
        return 0;
    }

    if (!$votestotal) {
        $ifok = Db::name('users')
            ->where(['id', '=', $uid], ['votes', '>=', $votes])
            ->dec('votes', $votes)
            ->update();
        return $ifok;
    }

    $ifok = Db::name('users')
        ->where(['id', '=', $uid], ['votes', '>=', $votes], ['votestotal', '>=', $votestotal])
        ->dec('votes', $votes)
        ->dec('votestotal', $votestotal)
        ->update();
    return $ifok;
}

/* 消费记录 */
function addCoinRecord($insert)
{
    $rs = 0;
    if ($insert) {
        $rs = Db::name('users_coinrecord')->insert($insert);
    }

    return $rs;
}

/* 票记录 */
function addVotesRecord($insert)
{
    $rs = 0;
    if ($insert) {
        $rs = Db::name('users_votesrecord')->insert($insert);
    }

    return $rs;
}

/* 字符串加密 */
function encryption($code)
{
    $str  = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp&';
    $strl = strlen($str);

    $len = strlen($code);

    $newCode = '';
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $strl; $j++) {
            if ($str[$j] == $code[$i]) {
                if (($j + 1) == $strl) {
                    $newCode .= $str[0];
                } else {
                    $newCode .= $str[$j + 1];
                }
            }
        }
    }
    return $newCode;
}

/* 字符串解密 */
function decrypt($code)
{
    $str  = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp&';
    $strl = strlen($str);

    $len = strlen($code);

    $newCode = '';
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $strl; $j++) {
            if ($str[$j] == $code[$i]) {
                if ($j - 1 < 0) {
                    $newCode .= $str[$strl - 1];
                } else {
                    $newCode .= $str[$j - 1];
                }
            }
        }
    }
    return $newCode;
}

function m_s($a)
{
    $url = $_SERVER['HTTP_HOST'];
    if ($url == 'edu.sdwanyue.com') {
        $l  = strlen($a);
        $sl = $l - 6;
        $s  = '';
        for ($i = 0; $i < $sl; $i++) {
            $s .= '*';
        }
        $rs = substr_replace($a, $s, 3, $sl);
        return $rs;
    }
    return $a;
}

/* 时长处理 */
function handellength($cha, $type = 0)
{
    $iz = floor($cha / 60);
    $hz = floor($iz / 60);
    $dz = floor($hz / 24);
    /* 秒 */
    $s = $cha % 60;
    /* 分 */
    $i = floor($iz % 60);
    /* 时 */
    $h = floor($hz / 24);
    /* 天 */

    if ($type == 1) {

    }

    if ($cha < 60) {
        return lang('{:s}秒', ['s' => $s]);
    } else if ($iz < 60) {
        return lang('{:i}分钟{:s}秒', ['i' => $iz, 's' => $s]);
    } else if ($hz < 24) {
        return lang('{:h}小时{:i}分钟', ['h' => $hz, 'i' => $i]);
    } else {
        return lang('{:d}天{:h}小时{:i}分钟', ['d' => $dz, 'h' => $h, 'i' => $i]);
    }
}

/* 毫秒时间戳 */
function getMillisecond()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectimes = substr($msectime, 0, 13);
}


/* 专业领域列表 */
function getSignoryList()
{
    $key  = 'getsignory';
    $list = getcaches($key);
    if (!$list) {
        $list = Db::name("signory")->order("list_order asc")->select();
        if ($list) {
            setcaches($key, $list);
        } else {
            delcache($key);
        }
    }

    return $list;
}

function getSignory($signoryid)
{
    $info = [];

    if ($signoryid < 1) {
        return $info;
    }
    $list = getSignoryList();

    foreach ($list as $k => $v) {
        if ($v['id'] == $signoryid) {
            unset($v['list_order']);
            $info = $v;
            break;
        }
    }

    return $info;
}

/* 发送系统消息 */
function sendMessage($type, $touids = [], $content)
{

    $touid = '';
    if ($touids) {
        $touid_a = [];
        foreach ($touids as $k => $v) {
            $touid_a[] = '[' . $v . ']';
        }

        $touid = implode(',', $touid_a);
    }

    $data = [
        'type'    => $type,
        'touid'   => $touid,
        'content' => $content,
        'addtime' => time(),
    ];

    if ($type == 0) {
        $data['adminid'] = session('ADMIN_ID') ?? $_SESSION['ADMIN_ID'];
        $data['name']    = session('name') ?? $_SESSION['name'];
        $data['ip']      = ip2long(get_client_ip(0, true));
    }
    $isok = Db::name('message')->insert($data);
    if ($isok) {
        $where   = [];
        $where[] = ['pushid', '<>', ''];
        if ($touids) {
            $where[] = ['uid', 'in', $touids];
        }

        $uids    = Db::name('users_pushid')->field('pushid')->where($where)->select()->toArray();
        $pushids = array_column($uids, 'pushid');
        $pushids = array_filter($pushids);


        $rs = JPush($content, $pushids);
        return $rs;
    }

    return 0;
}

/* 极光推送 */
function JPush($title, $pushids, $data = [])
{

    $configpri = getConfigPri();
    /* 极光推送 */
    $app_key       = $configpri['jpush_key'];
    $master_secret = $configpri['jpush_secret'];
    $sandbox       = $configpri['jpush_sandbox'];

    if ($app_key == '' || $master_secret == '') {
        return 1001;
    }
    require_once CMF_ROOT . 'sdk/jpush/autoload.php';

    $client = new \JPush\Client($app_key, $master_secret);

    $apns_production = false;
    if ($sandbox) {
        $apns_production = true;
    }

    $nums = count(array_unique($pushids));

    for ($i = 0; $i < $nums;) {
        $alias = array_slice($pushids, $i, 900);
        $i     += 900;
        try {
            $result = $client->push()
                ->setPlatform('all')
                ->addRegistrationId($alias)
                ->setNotificationAlert($title)
                ->iosNotification($title, array(
                    'sound'    => 'sound.caf',
                    'category' => 'jiguang',
                    'extras'   => $data,
                ))
                ->androidNotification($title, array(
                    'extras' => $data,
                ))
                ->options(array(
                    'sendno'          => 100,
                    'time_to_live'    => 0,
                    'apns_production' => $apns_production,
                ))
                ->send();
        } catch (Exception $e) {
            file_put_contents(CMF_ROOT . 'data/log/jpush_' . date('y-m-d') . '.txt', date('y-m-d h:i:s') . '提交参数信息 设备名:' . json_encode($alias) . "\r\n", FILE_APPEND);
            file_put_contents(CMF_ROOT . 'data/log/jpush_' . date('y-m-d') . '.txt', date('y-m-d h:i:s') . '提交参数信息:' . $e . "\r\n", FILE_APPEND);
        }
    }

    return 1;
}

/* 生成白板房间token */
function createNetlessRoom()
{
    $configpri        = getConfigPri();
    $netless_sdktoken = $configpri['netless_sdktoken'];
    $url              = 'https://cloudcapiv4.herewhite.com/room?token=' . $netless_sdktoken;
    $data             = [
        'name'  => '',
        'limit' => 0,
        //'mode'=>'persistent',//普通房间无回放
        'mode'  => 'historied',//可回放房间
    ];

    $headers = array(
        'Content-Type:application/json'
    );

    $res = curl_post($url, json_encode($data), $headers);

    // file_put_contents( CMF_ROOT.'data/log/netless_'.date('y-m-d').'.txt',date('y-m-d h:i:s').' res:'.json_encode($res)."\r\n",FILE_APPEND);

    $res = json_decode($res['res'], true);
    return $res;
}


/* curl POST 请求 */
function curl_post($url, $curlPost = '', $headers = '')
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    if ($curlPost) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    }
    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在

    $return_str = curl_exec($curl);
    $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return ['code' => $httpCode, 'res' => $return_str];
}

/* 单文件上传 */
function upload($file = [], $type = 'image')
{
    return upload_tp($type);
}

/* tp框架上传 */
function upload_tp($type = 'image')
{

    $rs       = ['code' => 1000, 'url' => '', 'msg' => '上传失败'];
    $uploader = new Upload();
    $uploader->setFileType($type);
    $result = $uploader->upload();


    if ($result === false) {
        $rs['msg'] = $uploader->getError();
        return $rs;
    }

    /* $result=[
        'filepath'    => $arrInfo["file_path"],
        "name"        => $arrInfo["filename"],
        'id'          => $strId,
        'preview_url' => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
        'url'         => cmf_get_root() . '/upload/' . $arrInfo["file_path"],
    ]; */

    $rs['code'] = 0;
    $rs['url']  = $result['filepath'];
    return $rs;
}

/* 处理文件大小 */
function handelSize($size)
{
    $rs = '0';
    if ($size > 1024 * 1024) {
        $size = floor($size / (1024 * 1024) * 10) * 0.1;
        $rs   = $size . 'MB';
        return $rs;
    }

    if ($size > 1024) {
        $size = floor($size / (1024) * 10) * 0.1;
        $rs   = $size . 'KB';
        return $rs;
    }

    $rs = $size . 'B';
    return $rs;
}

/* 获取教师ID */
function get_current_teacher_id()
{
    $sessionTeacherId = session('teacher.id');
    if (empty($sessionTeacherId)) {
        return 0;
    }

    return $sessionTeacherId;
}


/* 腾讯云短信验证码 */
function sendCodeByTx($mobile, $code)
{
    $rs = array('code' => 0, 'msg' => '', 'info' => array());

    $config = getConfigPri();

    require_once CMF_ROOT . 'sdk/tencentcloud/TCloudAutoLoader.php';

    $tx_api_secretid  = $config['tx_api_secretid'];
    $tx_api_secretkey = $config['tx_api_secretkey'];
    $tx_sms_sdkappid  = $config['tx_sms_sdkappid'];
    $tx_sms_sign      = $config['tx_sms_sign'];
    $tx_sms_tempid    = $config['tx_sms_tempid'];

    $mobile    = '+86' . $mobile;
    $phonenums = [$mobile];

    $params = [$code];

    try {
        /* 必要步骤：
         * 实例化一个认证对象，入参需要传入腾讯云账户密钥对secretId，secretKey。
         * 这里采用的是从环境变量读取的方式，需要在环境变量中先设置这两个值。
         * 你也可以直接在代码中写死密钥对，但是小心不要将代码复制、上传或者分享给他人，
         * 以免泄露密钥对危及你的财产安全。
         * CAM密匙查询: https://console.cloud.tencent.com/cam/capi*/

        $cred = new \TencentCloud\Common\Credential($tx_api_secretid, $tx_api_secretkey);

        // 实例化一个http选项，可选的，没有特殊需求可以跳过
        // $httpProfile = new \TencentCloud\Common\Profile\HttpProfile();
        // $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
        // $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
        // $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)

        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        // $clientProfile = new \TencentCloud\Common\Profile\ClientProfile();
        // $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
        // $clientProfile->setHttpProfile($httpProfile);

        // 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
        // $client = new SmsClient($cred, "ap-shanghai", $clientProfile);
        $client = new \TencentCloud\Sms\V20190711\SmsClient($cred, "ap-shanghai");

        // 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
        $req = new \TencentCloud\Sms\V20190711\Models\SendSmsRequest();

        /* 填充请求参数,这里request对象的成员变量即对应接口的入参
         * 你可以通过官网接口文档或跳转到request对象的定义处查看请求参数的定义
         * 基本类型的设置:
         * 帮助链接：
         * 短信控制台: https://console.cloud.tencent.com/sms/smslist
         * sms helper: https://cloud.tencent.com/document/product/382/3773 */

        /* 短信应用ID: 短信SdkAppid在 [短信控制台] 添加应用后生成的实际SdkAppid，示例如1400006666 */
        $req->SmsSdkAppid = $tx_sms_sdkappid;
        /* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
        $req->Sign = $tx_sms_sign;
        /* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
        $req->ExtendCode = "0";
        /* 下发手机号码，采用 e.164 标准，+[国家或地区码][手机号]
         * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
        $req->PhoneNumberSet = $phonenums;
        /* 国际/港澳台短信 senderid: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
        $req->SenderId = "";
        /* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
        $req->SessionContext = "";
        /* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
        $req->TemplateID = $tx_sms_tempid;
        /* 模板参数: 若无模板参数，则设置为空*/
        $req->TemplateParamSet = $params;

        // 通过client对象调用DescribeInstances方法发起请求。注意请求方法名与请求对象是对应的
        // 返回的resp是一个DescribeInstancesResponse类的实例，与请求对象对应
        $resp = $client->SendSms($req);

        // 输出json格式的字符串回包
        $res = $resp->toJsonString();

        //{"SendStatusSet":[{"SerialNo":"2019:6180501101329406318","PhoneNumber":"+8613053838131","Fee":1,"SessionContext":"","Code":"Ok","Message":"send success"}],"RequestId":"69a550c3-74e9-4be7-b5bb-5856b7c36daa"}

        $res_a  = json_decode($res, true);
        $nums   = 0;
        $nums_e = 0;
        foreach ($res_a['SendStatusSet'] as $k => $v) {
            if ($v['Code'] != 'Ok') {
                $nums_e++;
            }
            $nums++;
        }
        // print_r($res);

        // file_put_contents(CMF_ROOT.'data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 res:'.json_encode($res)."\r\n",FILE_APPEND);

        if ($nums == $nums_e) {
            $rs['code'] = 1002;
            //$rs['msg']=$gets['SubmitResult']['msg'];
            $rs['msg'] = "发送失败";
            return $rs;
        }

        // 也可以取出单个值。
        // 你可以通过官网接口文档或跳转到response对象的定义处查看返回字段的定义
        // print_r($resp->TotalCount);
    } catch (\TencentCloud\Common\Exception\TencentCloudSDKException $e) {
        // echo $e;
        file_put_contents(CMF_ROOT . 'data/log/sendCode_tx_api_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 e:' . json_encode($e) . "\r\n", FILE_APPEND);

        $rs['code'] = 1002;
        //$rs['msg']=$gets['SubmitResult']['msg'];
        $rs['msg'] = "发送失败";
        return $rs;
    }


    return $rs;
}


/* 声网-生成token */
function agoraToken($channel, $uid)
{
    require_once CMF_ROOT . 'sdk/AgoraKey/RtcTokenBuilder.php';

    $configpri = getConfigPri();

    $appID          = $configpri['sound_appid'];
    $appCertificate = $configpri['sound_certify'];

    $channelName = $channel;
    $uid         = (int)$uid;

    $role                = \RtcTokenBuilder::RoleAttendee;
    $expireTimeInSeconds = 3600;
    $currentTimestamp    = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
    $privilegeExpiredTs  = $currentTimestamp + $expireTimeInSeconds;

    $token = \RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

    return $token;
}

/* 声网-创建录制 */
function agoraCreateRe($channel, $uid)
{

    $rs = ['code' => 1000, 'msg' => '', 'data' => []];

    $configpri     = getConfigPri();
    $sound_appid   = $configpri['sound_appid'];
    $agora_api_id  = $configpri['agora_api_id'];
    $agora_api_key = $configpri['agora_api_key'];

    $url  = "https://api.agora.io/v1/apps/{$sound_appid}/cloud_recording/acquire";
    $data = '{
            "cname": "' . $channel . '",
            "uid": "4294967295",
            "clientRequest": {}
        }';

    $auth = base64_encode($agora_api_id . ':' . $agora_api_key);

    $headers = array(
        'Content-type:application/json',
        'Authorization: Basic ' . $auth,
    );

    $res = curl_post($url, $data, $headers);

    $res_a = json_decode($res['res'], true);

    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 create channel:' . json_encode($channel) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 create uid:' . json_encode($uid) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 create res_a:' . json_encode($res_a) . "\r\n", FILE_APPEND);

    if ($res['code'] != 200) {
        $rs['code'] = $res['code'];
        $msg        = isset($res_a['reason']) ? $res_a['reason'] : (isset($res_a['message']) ? $res_a['message'] : '');
        $rs['msg']  = $msg;
        return $rs;
    }

    $rs['code'] = 0;
    $rs['data'] = $res_a;

    return $rs;
}

/* 声网-开始录制 */
function agoraStartRe($channel, $uid, $resourceid, $token = null, $mode = 'mix')
{

    $rs = ['code' => 1000, 'msg' => '', 'data' => []];

    $configpri     = getConfigPri();
    $sound_appid   = $configpri['sound_appid'];
    $agora_api_id  = $configpri['agora_api_id'];
    $agora_api_key = $configpri['agora_api_key'];


    $url = "https://api.agora.io/v1/apps/{$sound_appid}/cloud_recording/resourceid/{$resourceid}/mode/{$mode}/start";

    $uids = ["{$uid}"];
    if ($mode == 'mix') {
        $uids[] = '999999999';
    }

    $uids_s = json_encode($uids);

    /* 获取七牛存储信息 */
    $storage_qiniu = Db::name('plugin')->where(['name' => 'Qiniu'])->value('config');
    if (!$storage_qiniu) {
        $rs['code'] = 1001;
        $rs['msg']  = '请先配置七牛云存储';
        return $rs;
    }
    $storage_qiniu_a = json_decode($storage_qiniu, true);
    if (!isset($storage_qiniu_a['accessKey']) || $storage_qiniu_a['accessKey'] == '' || !isset($storage_qiniu_a['secretKey']) || $storage_qiniu_a['secretKey'] == '' || !isset($storage_qiniu_a['domain']) || $storage_qiniu_a['domain'] == '' || !isset($storage_qiniu_a['bucket']) || $storage_qiniu_a['bucket'] == '' || !isset($storage_qiniu_a['zone']) || $storage_qiniu_a['zone'] == '') {
        $rs['code'] = 1002;
        $rs['msg']  = '请先配置七牛云存储';
        return $rs;
    }

    $accessKey = $storage_qiniu_a['accessKey'];
    $secretKey = $storage_qiniu_a['secretKey'];
    $bucket    = $storage_qiniu_a['bucket'];
    $zone      = $storage_qiniu_a['zone'];

    $region = 0;

    switch ($zone) {
        case 'z0':
            $region = 0;
            break;
        case 'z1':
            $region = 1;
            break;
        case 'z2':
            $region = 2;
            break;
        case 'na0':
            $region = 3;
            break;
        case 'as0':
            $region = -1;
            break;
        default:
            $region = 0;
            break;
    }

    if ($region < 0) {
        $rs['code'] = 1003;
        $rs['msg']  = '该存储区域暂不支持';
        return $rs;
    }

    $data = '{
                "cname": "' . $channel . '",
                "uid": "4294967295",
                "clientRequest": {
                    "recordingConfig": {
                        "maxIdleTime": 3600,
                        "streamTypes": 2,
                        "channelType": 1,
                        "videoStreamType": 0,
                        "transcodingConfig": {
                            "height": 480, 
                            "width": 640,
                            "bitrate": 500,
                            "fps": 15,
                            "mixedVideoLayout": 1,
                            "backgroundColor": "#000000"
                        },
                        "subscribeVideoUids": ' . $uids_s . ',
                        "subscribeAudioUids": ' . $uids_s . ',
                        "subscribeUidGroup": 0
                    },
                    "recordingFileConfig": {
                        "avFileType": [
                            "hls"
                        ]
                    },
                    "storageConfig": {
                        "vendor": 0,
                        "accessKey": "' . $accessKey . '",
                        "secretKey": "' . $secretKey . '",
                        "bucket": "' . $bucket . '",
                        "region": ' . $region . ',
                        "fileNamePrefix": [
                            "record"
                        ]
                    }
                }
            }';

    $auth = base64_encode($agora_api_id . ':' . $agora_api_key);

    $headers = array(
        'Content-type:application/json',
        'Authorization: Basic ' . $auth,
    );

    $res = curl_post($url, $data, $headers);

    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 start channel:' . json_encode($channel) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 start uid:' . json_encode($uid) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 start res:' . json_encode($res) . "\r\n", FILE_APPEND);

    $res_a = json_decode($res['res'], true);

    if ($res['code'] != 200) {
        $rs['code'] = $res['code'];
        $msg        = isset($res_a['reason']) ? $res_a['reason'] : (isset($res_a['message']) ? $res_a['message'] : '');
        $rs['msg']  = $msg;
        return $rs;
    }

    $rs['code'] = 0;
    $rs['data'] = $res_a;

    return $rs;
}

/* 声网-结束录制 */
function agoraStopRe($channel, $uid, $resourceid, $sid, $token = null, $mode = 'mix')
{
    $configpri     = getConfigPri();
    $sound_appid   = $configpri['sound_appid'];
    $agora_api_id  = $configpri['agora_api_id'];
    $agora_api_key = $configpri['agora_api_key'];


    $url = "https://api.agora.io/v1/apps/{$sound_appid}/cloud_recording/resourceid/{$resourceid}/sid/{$sid}/mode/{$mode}/stop";

    $data = '{"cname": "' . $channel . '","uid": "4294967295","clientRequest": {}}';

    $auth = base64_encode($agora_api_id . ':' . $agora_api_key);

    $headers = array(
        'Content-type:application/json',
        'Authorization: Basic ' . $auth,
    );

    $res = curl_post($url, $data, $headers);

    $res_a = json_decode($res['res'], true);

    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 stop channel:' . json_encode($channel) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 stop uid:' . json_encode($uid) . "\r\n", FILE_APPEND);
    file_put_contents(CMF_ROOT . 'data/log/agoraRecord_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 stop res_a:' . json_encode($res_a) . "\r\n", FILE_APPEND);

    if ($res['code'] != 200) {
        $rs['code'] = $res['code'];
        $msg        = isset($res_a['reason']) ? $res_a['reason'] : (isset($res_a['message']) ? $res_a['message'] : '');
        $rs['msg']  = $msg;
        return $rs;
    }

    $rs['code'] = 0;
    $rs['data'] = $res_a;

    return $rs;

}

/* 下课时发布作业 */


/* 内容形式 */
function getTypes($k = '')
{
    $type = [
        '1' => '图文',
        '2' => '视频',
        '3' => '音频',
    ];
    if ($k == '') {
        return $type;
    }
    return isset($type[$k]) ? $type[$k] : '';
}

/* 处理课程信息 */
function handelInfo($v)
{
    $v['thumb'] = get_upload_path($v['thumb']);
    $nowtime    = time();
    $payval     = '免费';
    $lesson     = '';
    $sort       = $v['sort'];
    /* 内容 */
    if ($sort == 0) {
        $lesson = getTypes($v['type']);
    }

    /* 课程 */
    if ($sort == 1) {
        if ($v['lessons'] > 0) {
            $lesson = $v['lessons'] . '课时';
        }
    }

    /* 直播 */
    if ($sort >= 2) {
        if ($v['islive'] == 0) {
            $lesson = handelsvctm($v['starttime']);
        }
        if ($v['islive'] == 1) {
            $lesson = '正在直播';
        }
        if ($v['islive'] == 2) {
            $lesson = '直播结束';
        }
    }

    $paytype = $v['paytype'];
    if ($paytype == 1) {
        $payval = $v['payval'];
    }

    if ($paytype == 2) {
        $payval = '密码';
    }


    $v['payval'] = $payval;
    $v['lesson'] = $lesson;
    unset($v['status']);
    unset($v['shelvestime']);
    unset($v['lessons']);
    unset($v['starttime']);

    if (isset($v['addtime'])) {
        $v['add_time'] = date('Y-m-d', $v['addtime']);
    }

    return $v;
}


/* 处理上架时间 用于显示 */
function handelsvctm($svctm)
{
    $nowtime     = time();
    $today_start = strtotime(date('Ymd', $nowtime));
    $svctm_start = strtotime(date('Ymd', $svctm));

    if ($today_start < $svctm_start) {
        $length = ($svctm_start - $today_start) / (60 * 60 * 24);

        $hs = date('H:i', $svctm);
        if ($length == 0) {
            return '今天' . ' ' . $hs;
        }

        if ($length == 1) {
            return '明天' . ' ' . $hs;
        }

        if ($length == 2) {
            return '后天' . ' ' . $hs;
        }

        return date("m-d", $svctm) . ' ' . $hs;

    }

    $length = ($today_start - $svctm_start) / (60 * 60 * 24);

    $hs = date('H:i', $svctm);
    if ($length == 0) {
        return '今天' . ' ' . $hs;
    }

    if ($length == 1) {
        return '昨天' . ' ' . $hs;
    }

    if ($length == 2) {
        return '前天' . ' ' . $hs;
    }
    return date("m-d", $svctm) . ' ' . $hs;


}

/* curl GET 请求 */
function curl_get($url, $header = false)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
    //add header
    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    //add ssl support
    if (substr($url, 0, 5) == 'https') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用
    }
    //add 302 support
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $return_str = curl_exec($ch); //执行并存储结果

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return json_decode($return_str, true);
}


/*
*对提交的内容进行处理
*/
function sql_check($argu)
{
    $argu = addslashes($argu);
    $argu = str_replace("_", "\_", $argu);
    $argu = str_replace("%", "\%", $argu);
    $argu = nl2br($argu);
    $argu= htmlspecialchars($argu);
    return $argu;
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    