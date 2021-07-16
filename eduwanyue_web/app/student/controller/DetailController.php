<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/09 09:22
// +----------------------------------------------------------------------
namespace app\student\controller;

use app\admin\model\CourseModel;
use app\admin\model\CourseUsersModel;
use app\admin\model\CourseViewsModel;
use cmf\controller\StudentBaseController;
use think\Db;

/**
 * 大班课/内容详情
 */
class DetailController extends StudentBaseController
{

    public function initialize()
    {
        parent::initialize();
    }



    /*
     * 学生访问接口日志(直播、内容)
     * @param $courseid 直播/内容id
     * @return bool|int
     */
    private function setStudentLog($courseid)
    {
        $studentId = session('student.id') ?? 0;
        if (!$studentId) {
            return 0;
        }
        $data = [
            'student_id' => $studentId,
            'ip'         => get_client_ip(0, true),
            'created_at' => time(),
            'course_id'   => $courseid
        ];

        db("student_log")->insert($data);


        return !0;
    }


    /**
     * 直播课程详情页面
     * @return mixed
     */
    public function live()
    {
        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];
        $uid      = $userinfo['id'] ?? 0;
        $token    = $userinfo['token'] ?? '';
        $courseid = $data['id'] ?? 0;

        //直播详情
        $url = $this->siteUrl . '/api/?s=Course.GetDetail&uid=' . $uid . '&token=' . $token . '&courseid=' . $courseid;

        $info = curl_get($url);
        if ($info['data']['code'] != 0) {
            $this->error($info['data']['msg']);
        }

        $info   = $info['data']['info'][0];
        $isMore = 0;

        $this->setStudentLog($courseid); //记录日志

        $this->assign([
            'info'   => $info,
            'isMore' => $isMore,
            'navid'  => -1
        ]);

        return $this->fetch();
    }


    /**
     * 内容详情页面
     * @return mixed
     */
    public function substance()
    {
        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];
        $uid      = $userinfo['id'] ?? 0;
        $token    = $userinfo['token'] ?? '';
        $courseid = $data['id'] ?? 0;

        //内容详情
        $url = $this->siteUrl . '/api/?s=Course.GetDetail&uid=' . $uid . '&token=' . $token . '&courseid=' . $courseid;

        $info = curl_get($url);
        if ($info['data']['code'] != 0) {
            $this->error($info['data']['msg']);
        }
        $info   = $info['data']['info'][0];
        $isMore = 0;

        $this->setStudentLog($courseid); //记录日志

        $this->assign([
            'info'   => $info,
            'isMore' => $isMore,
            'navid'  => -1
        ]);

        return $this->fetch();
    }


    /**
     * 内容学习页面
     * @return mixed
     */
    public function substancestudy()
    {
        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];
        $uid      = $userinfo['id'] ?? 0;
        $token    = $userinfo['token'] ?? '';
        $courseid = $data['courseid'] ?? 0;

        //内容详情
        $url = $this->siteUrl . '/api/?s=Course.GetDetail&uid=' . $uid . '&token=' . $token . '&courseid=' . $courseid;

        $info = curl_get($url);
        if ($info['data']['code'] != 0) {
            $this->error($info['data']['msg']);
        }
        $info = $info['data']['info'][0];

        if (($info['paytype'] != 0 && $info['ifbuy'] == 0 && $info['trialtype'] == 0) || $info['sort'] != 0) {
            $this->redirect(cmf_url("student/index/index"));
        }

        $info['url'] = decrypt($info['url']);
        $this->assign('info', $info);

        $courseinfo = CourseModel::where(["id" => $courseid])->find();
        if (!$courseinfo) {
            $this->error('课程不存在');
        }

        $sort      = $courseinfo['sort'];
        $type      = $courseinfo['type'];
        $paytype   = $courseinfo['paytype'];
        if ($sort == 0) {
            $isbuy = 0;
            if ($paytype != 0) {
                $ispay = CourseUsersModel::field('id')->where(["uid" => $uid, "courseid" => $courseid, "status" => 1])->find();
                if ($ispay) {
                    $isbuy = 1;
                }
            } else {
                $isbuy = 1;
            }

            if ($isbuy == 1) {
                $this->setLesson($uid, $courseid);
            }

        }
        $this->assign('navid', -1);
        return $this->fetch();
    }


    /*
     * 更新课程进度
     * @param $uid
     * @param $courseid
     * @param int $lessonid
     * @return bool
     */
    protected function setLesson($uid, $courseid, $lessonid = 0)
    {
        $nowtime = time();
        $isview  = CourseViewsModel::where(['uid' => $uid, 'courseid' => $courseid, 'lessonid' => $lessonid])->find();
        if ($isview) {
            CourseViewsModel::where(["id" => $isview['id']])->setField('addtime', $nowtime);
            return !1;
        }

        $course = CourseModel::where(["id" => $courseid])->find();
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
        CourseViewsModel::create($data, true);

        $nums = CourseViewsModel::where(['uid' => $uid, 'courseid' => $courseid])->count();
        if ($nums < 2) {
            /* 同一课程下的课时 记一次课程学习数 */
            CourseModel::where(["id" => $courseid])->setInc('views', 1);
        }

        $isexist = CourseUsersModel::where(['uid' => $uid, 'courseid' => $courseid])->find();
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
            CourseUsersModel::create($data2, true);

            $isexist = CourseUsersModel::where(['uid' => $uid, 'courseid' => $courseid])->find();
        }

        if ($lessonid > 0) {
            CourseUsersModel::where(['id' => $isexist['id']])->setInc('lessons', 1);

            $lessons = CourseUsersModel::where(['id' => $isexist['id']])->value('lessons');
            if ($lessons >= $course['lessons']) {
                /* 看完 */
                CourseUsersModel::where(['id' => $isexist['id']])->setField('step', 2);
            } else {
                CourseUsersModel::where(['id' => $isexist['id']])->setField('step', 1);
            }
        } else {
            CourseUsersModel::where(['id' => $isexist['id']])->setField('step', 2);
        }
    }


    /**
     * 购买页面
     * @return mixed
     */
    public function buy()
    {
        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $courseid   = sql_check($data['courseid']) ?? '';
        $type       = sql_check($data['type']) ?? '';
        $method     = sql_check($data['method']) ?? '0';
        $totalmoney = sql_check($data['totalmoney']) ?? '0';
        $name       = sql_check($data['name']) ?? '购物车购买';
        $ismaterial = sql_check($data['ismaterial']) ?? '';

        //支付方式
        $configpri    = getConfigPri();
        $alipc_switch = $configpri['alipc_switch'];
        $wxpc_switch  = $configpri['wxpc_switch'];

        $paylist = [];
        if ($alipc_switch == 1) {
            $paylist[] = [
                'id'    => '1',
                'name'  => '支付宝支付',
                'thumb' => $this->siteUrl . "/static/app/pay/ali.png",
            ];
        }

        if ($wxpc_switch == 1) {
            $paylist[] = [
                'id'    => '2',
                'name'  => '微信支付',
                'thumb' => $this->siteUrl . "/static/app/pay/wx.png",
            ];
        }

        $this->assign([
            'courseid'   => $courseid,
            'type'       => $type,
            'method'     => $method,
            'totalmoney' => $totalmoney,
            'ismaterial' => $ismaterial,
            'name'       => $name,
            'paylist'    => $paylist,
            'navid'      => -1
        ]);

        return $this->fetch();
    }


    /**
     * 支付页面
     * @return mixed
     */
    public function payment()
    {

        $data = $this->request->param();

        //判断有没有登录
        $this->checkMyLogin();

        $userinfo = session('student') ?? $_SESSION['student'];

        $uid        = $userinfo['id'];
        $token      = $userinfo['token'];
        $method     = sql_check($data['method']) ?? '0';
        $ismaterial = sql_check($data['ismaterial']) ?? '';
        $payid      = sql_check($data['payid']) ?? '0';
        $courseid   = sql_check($data['courseid']) ?? '0';
        $type       = sql_check($data['type']) ?? '0';

        $addrid = 0;
        if ($ismaterial == 1) {
            $addrlist = Db::name('users_addrs')->field('id')->where(['uid' => $uid])->order('id desc')->find();
            if (!$addrlist) {
                $this->error('网络错误，请稍后重试');
            }
            $addrid = $addrlist['id'];
        }

        $goods = [
            ['type' => $type, 'typeid' => $courseid]
        ];

        $url2 = $this->siteUrl . '/api/?s=Cart.Buy&uid=' . $uid . '&token=' . $token . '&payid=' . $payid . '&addrid=' . $addrid . '&method=' . $method . '&goods=' . json_encode($goods) . '&source=PC';
        $pay  = curl_get($url2);

        if (($pay['data']['code'] ?? 0) != 0) {
            $this->error($pay['data']['msg']);
        }
        $configpri = getConfigPri();
        $configpub = getConfigPub();
        if ($payid == 1) { //支付宝
            
            if ($configpri['aliapp_partner'] == "" || $configpri['aliapp_seller_id'] == "" || $configpri['aliapp_key'] == "") {
                $this->error('支付宝支付未配置,无法支付');
            }

            //合作身份者id，以2088开头的16位纯数字
            $alipay_config['partner'] = $configpri['aliapp_partner'];
            //安全检验码，以数字和字母组成的32位字符
            $alipay_config['key'] = $configpri['aliapp_check'];
            //支付宝账号
            $alipay_config['seller_email'] = $configpri['aliapp_seller_id'];
            //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
            //签名方式 不需修改
            $alipay_config['sign_type'] = strtoupper('MD5');
            //字符编码格式 目前支持 gbk 或 utf-8
            $alipay_config['input_charset'] = strtolower('utf-8');
            //ca证书路径地址，用于curl中ssl校验
            //请保证cacert.pem文件在当前文件夹目录中
            $alipay_config['cacert'] = CMF_ROOT . 'sdk/alipay/cacert.pem';
            //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
            $alipay_config['transport'] = 'http';
            //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
            require_once CMF_ROOT . "sdk/alipay/lib/alipay_submit.class.php";

            //支付记录					
            /**************************请求参数**************************/
            //支付类型
            $payment_type = "1";
            //必填，不能修改
            //服务器异步通知页面路径
            $notify_url = $configpub['site_url'] . '/appapi/cartpay/notify_pc_ali';
            //需http://格式的完整路径，不能加?id=123这类自定义参数
            //页面跳转同步通知页面路径
            $return_url = $configpub['site_url'] . '/student';
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
            //商户网站订单系统中唯一订单号，必填
            //订单名称
            $subject = "购买课程";
            //付款金额
            $total_fee = $pay['data']['info'][0]['money'];
            //订单描述
            $body = "购买课程";
            //商品展示地址
            $show_url = $configpub['site_url'];
            //防钓鱼时间戳
            $anti_phishing_key = "";
            //若要使用请调用类文件submit中的query_timestamp函数
            //客户端的IP地址
            $exter_invoke_ip = "";
            /************************************************************/
            $parameter = array(
                "service"           => "create_direct_pay_by_user",
                "partner"           => trim($alipay_config['partner']),
                "payment_type"      => $payment_type,
                "notify_url"        => $notify_url,
                "return_url"        => $return_url,
                "seller_email"      => trim($alipay_config['seller_email']),
                "out_trade_no"      => $pay['data']['info'][0]['orderid'],
                "subject"           => $subject,
                "total_fee"         => $total_fee,
                "body"              => $body,
                "show_url"          => $show_url,
                "qr_pay_mode"       => 2,
                "anti_phishing_key" => $anti_phishing_key,
                "exter_invoke_ip"   => $exter_invoke_ip,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
            );

            //建立请求
            $alipaySubmit = new \AlipaySubmit($alipay_config);
            $html_text    = $alipaySubmit->buildRequestForm($parameter, "get", "1");
            echo $html_text;
            exit;
        } else if ($payid == 2) { //微信

            $configpri = getConfigPri();

            if ($configpri['pc_wx_appid'] == "" || $configpri['pc_wx_mchid'] == "" || $configpri['pc_wx_key'] == "") {
                $this->error('微信支付未配置, 无法支付');
            }

            $noceStr = md5(rand(100, 1000) . time());//获取随机字符串
            $time    = time();
            $orderid = $pay['data']['info'][0]['orderid'];
            // var_dump($orderid);
            $paramarr = array(
                "appid"        => $configpri['pc_wx_appid'],
                "body"         => '购买课程',
                "mch_id"       => $configpri['pc_wx_mchid'],
                "nonce_str"    => $noceStr,
                "notify_url"   => $configpub['site_url'] . '/appapi/cartpay/notify_pc_wx',
                "out_trade_no" => $orderid,
                "total_fee"    => $pay['data']['info'][0]['money'] * 100,
                "trade_type"   => "NATIVE"
            );
            $sign     = "";
            foreach ($paramarr as $k => $v) {
                $sign .= $k . "=" . $v . "&";
            }

            $sign             .= "key=" . $configpri['pc_wx_key'];
            $sign             = strtoupper(md5($sign));
            $paramarr['sign'] = $sign;
            $paramXml         = "<xml>";
            foreach ($paramarr as $k => $v) {
                $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
            }
            $paramXml .= "</xml>";

            $ch = curl_init();
            @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
            @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
            @curl_setopt($ch, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($ch, CURLOPT_POST, 1);
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $paramXml);
            @$resultXmlStr = curl_exec($ch);
            curl_close($ch);

            $msg     = array();
            $postStr = $resultXmlStr;
            $msg     = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($msg['return_code'] == 'SUCCESS' && $msg['return_msg'] == 'OK') {

                require_once CMF_ROOT . "vendor/phpqrcode/phpqrcode.php";
                $value = $msg['code_url'];
                // var_dump($value);
                $errorCorrectionLevel = 'L';//容错级别
                $i                    = 320;
                $j                    = floor($i / 37 * 100) / 100 + 0.01;
                $matrixPointSize      = $j;

                $str      = $uid . '_' . time();
                $filename = CMF_ROOT . 'public/upload/wxpcpay/' . $str . '.png';
                $QRcode   = new \QRcode();
                $QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                $QR = $filename;
                $QR = imagecreatefromstring(file_get_contents($QR));

                $this->success('', '', $configpub['site_url'] . '/upload/wxpcpay/' . $str . '.png');
            } else {
                $this->error('请求失败请稍后重试');
            }
        }

    }
}


