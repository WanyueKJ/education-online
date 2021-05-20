<?php

namespace App\Domain;

use App\Domain\Auth as Domain_Auth;
use App\Domain\User as Domain_User;
use App\Model\Agent as Model_Agent;
use App\Model\Course as Model_Course;
use App\Model\Package as Model_Package;

class Agent
{

    public function getCode($uid)
    {

        $key  = 'agent_code_' . $uid;
        if(isset($GLOBALS[$key])){
            return $GLOBALS[$key];
        }
        $code = \App\getcaches($key);
        if (!$code) {
            $model    = new Model_Agent();
            $where    = ['uid' => $uid];
            $codeinfo = $model->getAgent($where);
            if (!$codeinfo) {
                $code = $this->createCode(6, 'ALL2');
                $data = ['uid' => $uid, 'code' => $code];
                $model->setCode($data);
            } else {
                $code = $codeinfo['code'];
            }
            \App\setcaches($key, $code);
        }
        $GLOBALS[$key]=$code;
        return $code;
    }

    /* 生成邀请码 */
    public function createCode($len = 6, $format = 'ALL')
    {
        $is_abc   = $is_numer = 0;
        $password = $tmp = '';
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'ALL2':
                $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }

        while (strlen($password) < $len) {
            $tmp = substr($chars, (mt_rand() % strlen($chars)), 1);
            if (($is_numer <> 1 && is_numeric($tmp) && $tmp > 0) || $format == 'CHAR') {
                $is_numer = 1;
            }
            if (($is_abc <> 1 && preg_match('/[a-zA-Z]/', $tmp)) || $format == 'NUMBER') {
                $is_abc = 1;
            }
            $password .= $tmp;
        }
        if ($is_numer <> 1 || $is_abc <> 1 || empty($password)) {
            $password = $this->createCode($len, $format);
        }
        if ($password != '') {

            $model   = new Model_Agent();
            $where   = ['code' => $password];
            $oneinfo = $model->getAgent($where);
            if (!$oneinfo) {
                return $password;
            }
        }
        $password = $this->createCode($len, $format);
        return $password;
    }

    /* 我的信息 */


    /* 检测宴请弹窗 */
    public function checkAgent($uid, $type)
    {

        $info = [
            'ispop'  => '0',
            'ismust' => '0',
        ];

        $configpri = \App\getConfigPri();
        if ($configpri['agent_switch'] == 0) {
            return $info;
        }

        $model = new Model_Agent();

        $where = ['pid' => $uid];
        $nums  = $model->getAgentNums($where);
        if ($nums > 0) {
            return $info;
        }

        $where = ['uid' => $uid];
        $nums  = $model->getAgentNums($where);
        if ($nums > 0) {
            return $info;
        }


        $info['ispop'] = '1';
        if ($type == 0) {
            $info['ispop'] = '0';
        }

        if ($info['ismust'] == 1 && $info['ispop'] == 0) {
            $info['ispop'] = '1';
        }

        return $info;
    }

    /* 设置邀请关系 */
    public function setAgent($uid, $code)
    {

        $rs = array('code' => 0, 'msg' => '操作成功', 'info' => array());

        $model = new Model_Agent();

        $configpri = \App\getConfigPri();
        if ($configpri['agent_switch'] == 0) {
            $rs['code'] = 1001;
            $rs['msg']  = \PhalApi\T('未开通推广关系设置');
            return $rs;
        }

        $where = ['uid' => $uid];
        $nums  = $model->getAgentNums($where);
        if ($nums > 0) {
            $rs['code'] = 1002;
            $rs['msg']  = \PhalApi\T('已设置');
            return $rs;
        }

        $where = ['pid' => $uid];
        $nums  = $model->getAgentNums($where);
        if ($nums > 0) {
            $rs['code'] = 1003;
            $rs['msg']  = \PhalApi\T('已有下级，不能设置');
            return $rs;
        }
        $where     = ['code' => $code];
        $agentinfo = $model->getAgent($where);
        if (!$agentinfo) {
            $rs['code'] = 1004;
            $rs['msg']  = \PhalApi\T('邀请码错误');
            return $rs;
        }

        $pid = $agentinfo['uid'];
        if ($pid == $uid) {
            $rs['code'] = 1005;
            $rs['msg']  = \PhalApi\T('不能填写自己的邀请码');
            return $rs;
        }

        $data = ['uid' => $uid, 'pid' => $pid, 'addtime' => time()];
        $model->addAgent($data);

        return $rs;
    }

    /* 更新推广状态 */
    public function upAgent($uid, $isagent)
    {

        $model = new Model_Agent();
        if ($isagent == 0) {
            $where = ['uid' => $uid];
            $up    = ['isagent' => 0, 'istips' => 0];
        } else {
            $where = ['uid' => $uid, 'isagent!=?' => 1];
            $up    = ['isagent' => 1, 'istips' => 1];
        }
        $model->upCode($where, $up);

        return 1;
    }


    /**
     * 制作推广海报图片地址
     */
    public function getPopuImage($uid)
    {
        $rs = array('code' => 0, 'msg' => '操作成功', 'info' => array());

        //用户 头像 名字 邀请码
        $userInfo    = \App\getUserInfo($uid,1);
        if (!$userInfo) {
            $rs['code'] = '1004';
            $rs['msg'] = '信息错误';
            return $rs;
        }

        $avatar_path = $userInfo['avatar'];
        $nickname    = $userInfo['user_nickname'];

        $agent_code = $this->getCode($uid);
        $qrcodeUrl  = \App\get_host() . '/appapi/share/index?code=' . $agent_code; //二维码分享url地址
        //生成二维码
        $qrUpPath = API_ROOT . '/../public/upload/uni_qrcode';
        $qrcodePath = \App\scerweima($qrcodeUrl, 8.2068965517241379310344827586207, $qrUpPath);

        $salt = $qrcodeUrl . $avatar_path . '30'; //防止用户换头像但还是取旧头像

        //背景图片
        $bg_path = API_ROOT . '/../public/static/tuiguang/images/tuiguang_haibao_bg3.png';

        $original = getimagesize($bg_path); //得到原图片的信息数组

        $bigImg = imagecreatefromstring(file_get_contents($bg_path));//原图地址资源

        /***二维码大图合并***/
        \App\makeAndImg($qrcodePath, $bigImg, 145, 350);

        /***邀请码处理***/
        //邀请码标题  粗体!
        \App\makeTextWater($bigImg, '邀请码:', 18, [255, 255, 255], 201, 715);
        //邀请码编号 粗体
        \App\makeTextWater($bigImg, $agent_code, 18, [255, 255, 255], 301, 716);

        /***用户信息***/
        //用户昵称
        \App\makeTextWater($bigImg, $nickname, 16, [50, 50, 50], 250, 842);

        //用户头像
        //头像路径名
        $uni_user_path = API_ROOT . '/../public/upload/uni_user';
        if (!is_dir($uni_user_path)) {
            mkdir($uni_user_path);
        }

        //头像处理后的新路径地址 用户换头像后自动更新
        $avatarNewPath = $uni_user_path . '/' . md5($salt) .'_avatar7';

        //原有头像 缩放切圆角
        $avatarPath = \App\get_upload_path($avatar_path);
        //缩放图片
        $avatarNewImg = \App\zoomSmall($avatarPath, 70, 70, $avatarNewPath);
        //切圆角
        $circleImg = \App\zoomImg($avatarNewImg, $avatarNewPath . '_circle8');
        //合并头像和大图
        \App\makeAndImg($circleImg, $bigImg, 160, 812);


        /***邀请您来万岳在线教育系统学习***/
        \App\makeTextWater($bigImg, '邀请您来万岳在线教育系统学习', 16, [50, 50, 50], 250, 872);

        //    生成新的图片
        //最终大图存放路径
        $up_path = API_ROOT . '/../public/upload/uni_tuiguang_haibao/';
        if (!is_dir($up_path)) {
            mkdir($up_path);
        }

        $new_img_path = \App\outImage($bigImg, $up_path, $original[2], $salt);

        if ($new_img_path) {
            $return_path = substr($new_img_path, strpos((String)$new_img_path, '/upload'));
            //返回图片地址
            $rs['info'] = ['poster_url' => \App\get_upload_path($return_path)];
        }

        return $rs;
    }


    /*
     * 制作分享海报图片地址
     * @param int $uid 用户id
     * @param int $courseid 课程/套餐id
     * @param int $type 类型 1课程 2套餐
     */
    public function getShareImage($uid, $courseid, $type)
    {
        $rs = array('code' => 0, 'msg' => '操作成功', 'info' => array());

        //背景图片
        $bg_path = API_ROOT . '/../public/static/share/images/share_haibao_bg.png';
        //上传路径
        $up_path = API_ROOT . '/../public/upload/uni_fenxiang_haibao';
        if (!is_dir($up_path)) {
            mkdir($up_path);
        }

        $original = getimagesize($bg_path); //得到原图片的信息数组
        $userInfo   = \App\getUserInfo($uid);
        if (!$userInfo) {
            $rs['code'] = 1004;
            $rs['msg'] = '信息错误';
            return $rs;
        }

        //课程展示课时 大班课展示直播状态 内容展示类型 套餐展示课程数
        if ($type == 1) {
            //课程
            $bigArr = $this->makeShareCourse($uid, $courseid, $bg_path, $up_path);
        } elseif($type == 2) {
            //套餐
            $bigArr = $this->makeSharePackage($uid, $courseid, $bg_path, $up_path);
        }

        $bigImg = $bigArr['big_img'] ?? '';
        $salt = $bigArr['salt'] ?? '';

        if (!$bigImg || !$salt) {
            $rs['code'] = 1005;
            $rs['msg'] = '操作失败, 请重试';
            return $rs;
        }

        /***用户信息 名字 邀请码 ***/
        $nickname  = $userInfo['user_nickname'];
        \App\makeTextWater($bigImg, $nickname, 22, [50, 50, 50], 241, 742);

        /***邀请您来万岳在线教育系统学习***/
        \App\makeTextWater($bigImg, '邀请您来万岳在线教育系统学习', 16, [50, 50, 50], 241, 782);

        // 生成新的图片
        $new_img_dir  = $up_path . '/';//图片名
        $new_img_path = \App\outImage($bigImg, $new_img_dir, $original[2], $salt);

        if ($new_img_path) {
            //截图/upload后边部分
            $return_path = substr($new_img_path, strpos((String)$new_img_path, '/upload'));
            //返回图片地址
            $rs['info'] = ['poster_url' => \App\get_upload_path($return_path)];
        }

        return $rs;

    }



    /*
     * 处理分享海报的课程信息
     * @param $uid 用户id
     * @param $courseid 课程id
     * @param $bg_path 背景图路径
     * @param $up_path 上传路径
     * @return array 处理完成的图片资源和二维码地址url
     */
    private function makeShareCourse($uid, $courseid, $bg_path, $up_path)
    {
        $courseModel = new Model_Course();

        $courseData = $courseModel->getDetail(['id' => $courseid], 'uid, name, lessons, payval, thumb, paytype, islive, type, sort');
        if (!$courseData) {
            return [];
        }

        $bigImg     = imagecreatefromstring(file_get_contents($bg_path));//原图地址资源
        $courseImg  = \App\get_upload_path($courseData['thumb']);

        //获取邀请码及二维码分享地址
        $agent_code = $this->getCode($uid);
        $host_url   = \App\get_host();

        $qrcodeUrl  = $host_url . '/appapi/share/course' . '?code=' . $agent_code . '&id=' . $courseid;

        //缩放课程图片
        $courseNewPath = $up_path . '/' . md5($qrcodeUrl) . '_course_6';
        $courseNewImg  = \App\zoomSmall($courseImg, 520, 356, $courseNewPath);
        imagecopy($bigImg, $courseNewImg, 35, 48, 0, 0, 520, 356); //生成图像水印

        //课程标题
        $courseName = $courseData['name'] ?? '';
        //截取标题字符串17位一行 剩余一行
        $courseNameLength = mb_strlen($courseName);
        if ($courseNameLength > 17) {
            $courseNameTop = 449;
            for ($i = 0; $i < $courseNameLength; $i += 17) {
                $lineTitle             = mb_substr($courseName, $i, $i + 16);
                \App\makeTextWater($bigImg, $lineTitle, 22, [50, 50, 50], 67, $courseNameTop);
                $courseNameTop         += 40;
            }

        } else {
            \App\makeTextWater($bigImg, $courseName, 22, [50, 50, 50], 67, 449);
        }

        $course_salt = '';
        $sort = $courseData['sort'] ?? '';
        if ($sort == 1) {
            //课时
            $courseLessons     = $courseData['lessons'] ? ($courseData['lessons'] . '课时') : '';
            $course_salt = $courseData['lessons'];
            \App\makeTextWater($bigImg, $courseLessons, 14, [150, 150, 150], 67, 539);
        }
        elseif ($sort > 1) {
            //大班课状态
            $liveStatus = ($courseData['islive'] == 1) ? '正在直播' : '直播结束';
            \App\makeTextWater($bigImg, $liveStatus, 14, [150, 150, 150], 67, 539);
        }
        elseif ($sort == 0) {
            //图文类型
            switch ((int)$courseData['type'])
            {
                case 1:
                    $contentType = '图文自学';
                    break;
                case 2:
                    $contentType = '视频自学';
                    break;
                case 3:
                    $contentType = '音频自学';
                    break;
                default:
                    $contentType = '';
            }
            //边框
            \App\makeTextWater($bigImg, '———', 20, [150, 150, 150], 62, 524);
            \App\makeTextWater($bigImg, '|', 20, [150, 150, 150], 60, 537);
            \App\makeTextWater($bigImg, $contentType, 14, [150, 150, 150], 68, 539);
            \App\makeTextWater($bigImg, '———', 20, [150, 150, 150], 62, 554);
            \App\makeTextWater($bigImg, '|', 20, [150, 150, 150], 145, 537);

        }


        //老师头像
        $teacherData          = \App\getUserInfo($courseData['uid']);
        $teacherAvatarPath    = \App\get_upload_path($teacherData['avatar']);
        $salt = $qrcodeUrl . $teacherAvatarPath . $course_salt; //盐值, 防止老师换头像和课程更新课时

        $teacherAvatarNewPath = $up_path . '/' . md5($salt) . '_teacher_avatar_2';
        //缩放
        $teacherAvatarNewImg = \App\zoomSmall($teacherAvatarPath, 35, 35, $teacherAvatarNewPath);
        //切圆
        $circleImg              = \App\zoomImg($teacherAvatarNewImg, $teacherAvatarNewPath . '_circle_2');
        imagecopy($bigImg, $circleImg, 67, 569, 0, 0, 35, 35); //生成图像水印

        //老师名字
        $courseTeacherName       = $teacherData['user_nickname'];
        \App\makeTextWater($bigImg, $courseTeacherName, 12, [50, 50, 50], 113, 589);

        //课程价格/免费/密码/收费
        $coursePayval = $courseData['payval'] ?? '';
        if ($courseData['paytype'] == 0) { //免费
            $coursePayvalColor = [56, 218, 166];
            $coursePayval = '免费';
        } else if ($courseData['paytype'] == 2) { //密码
            $coursePayvalColor = [67,133,255];
            $coursePayval = '密码';
        } else if ($courseData['paytype'] == 1) { //收费
            $coursePayval      = '￥' . $coursePayval;
            $coursePayvalColor = [255, 27, 32];
        }
        \App\makeTextWater($bigImg, $coursePayval, 22, $coursePayvalColor, 421, 594);


        //生成二维码路径
        $qrUpPath = API_ROOT . '/../public/upload/uni_qrcode';
        $qrcodePath     = \App\scerweima($qrcodeUrl, 4.2068965517241379310344827586207, $qrUpPath);
        \App\makeAndImg($qrcodePath, $bigImg, 55, 673);

        return [
            'big_img'   => $bigImg,
            'salt' => $salt,
        ];


    }



    /**
     * 处理分享海报的套餐信息
     * @param $uid 用户id
     * @param $packageid 套餐id
     * @param $bg_path 背景图路径
     * @param $up_path 上传路径
     * @return resource 处理完成的图片资源
     */
    private function makeSharePackage($uid, $packageid, $bg_path, $up_path)
    {

        $packageModel = new Model_Package();

        $packageData = $packageModel->getInfo(['id' => $packageid]);
        if (!$packageData) {
            return [];
        }

        $bigImg     = imagecreatefromstring(file_get_contents($bg_path));//原图地址资源

        //获取邀请码及二维码地址
        $agent_code = $this->getCode($uid);
        $host_url   = \App\get_host();

        $qrcodeUrl      = $host_url . '/appapi/share/package' . '?code=' . $agent_code . '&id=' . $packageid;

        $packageImg  = \App\get_upload_path($packageData['thumb']);
        //缩放套餐图片
        $packageNewPath = $up_path . '/' . md5($qrcodeUrl) . '_package';
        $packageNewImg  = \App\zoomSmall($packageImg, 520, 356, $packageNewPath);
        imagecopy($bigImg, $packageNewImg, 35, 48, 0, 0, 520, 356); //生成套餐图像水印

        //套餐标题
        $packageName = $packageData['name'] ?? '';
        //截取标题字符串17位一行 剩余一行
        $packageNameLength = mb_strlen($packageName);
        if ($packageNameLength > 17) {
            $packageNameTop = 449;
            for ($i = 0; $i < $packageNameLength; $i += 17) {
                $lineTitle             = mb_substr($packageName, $i, $i + 16);
                \App\makeTextWater($bigImg, $lineTitle, 22, [50, 50, 50], 67, $packageNameTop);
                $packageNameTop         += 40;
            }

        } else {
            \App\makeTextWater($bigImg, $packageName, 22, [50, 50, 50], 67, 449);
        }

        //套餐显示课程数
        $courses =  explode(',', $packageData['courseids']);
        $coursesNum =  count($courses) . '课程';
        \App\makeTextWater($bigImg, $coursesNum, 12, [50, 50, 50], 67, 594);


        //套餐价格/免费
        $packagePrice = $packageData['price'] ?? '';
        if (is_numeric($packagePrice) && $packagePrice > 0) {
            $packagePrice      = '￥' . $packagePrice;
            $packagePriceColor = [255, 27, 32];
        }
        \App\makeTextWater($bigImg, $packagePrice, 22, $packagePriceColor, 421, 594);


        //二维码路径
        $qrUpPath = API_ROOT . '/../public/upload/uni_qrcode';
        $qrcodePath     = \App\scerweima($qrcodeUrl, 4.2068965517241379310344827586207, $qrUpPath);
        \App\makeAndImg($qrcodePath, $bigImg, 55, 673);

        return [
            'big_img'   => $bigImg,
            'salt' => $qrcodeUrl, //直接用二维码地址做盐
        ];

    }





}
