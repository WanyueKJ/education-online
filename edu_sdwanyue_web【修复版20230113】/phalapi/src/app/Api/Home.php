<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Home as Domain_Home;
use App\Domain\Course as Domain_Course;
use App\Domain\Teacher as Domain_Teacher;
use App\Domain\Package as Domain_Package;
use App\Domain\Cart as Domain_Cart;

header("Access-Control-Allow-Origin: *");

/**
 * 首页
 */

class Home extends Api {

	public function getRules() {
        return array(
            'getIndex' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
            ),
            
            'search' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'desc' => '搜索内容'),
            ),
        );
	}
	
    /**
     * 网站信息
     * @desc 用于获取网站基本信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].site_name 网站名称
     * @return string info[0].name_coin 消费币名称
     * @return string info[0].apk_ver APK版本号
     * @return string info[0].apk_des APK更新说明
     * @return string info[0].apk_url APK下载链接
     * @return string info[0].ipa_ver IPA版本号
     * @return string info[0].ios_shelves IPA上架版本号
     * @return string info[0].ipa_des IPA更新说明
     * @return string info[0].ipa_url IPA下载链接
     * @return array info[0].login_type 登录方式
     * @return array info[0].share_type 分享方式
     * @return string msg 提示信息
     */
	public function getConfig() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $info=\App\getConfigPub();
        unset($info['site_url']);
        unset($info['site_seo_title']);
        unset($info['site_seo_keywords']);
        unset($info['site_seo_description']);
        unset($info['copyright']);
        unset($info['qr_url']);
        
        $info_pri=\App\getConfigPri();
        
        $info['login_type']=$info_pri['login_type'];
        $info['share_type']=$info_pri['share_type'];
        

        $rs['info'][0] = $info;
		
        return $rs;
	}

    /**
     * 首页
     * @desc 用于获取首页信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].cartnums 购物车数量 
     * @return array info[0].silide 轮播 
     * @return string info[0].silide[].image 图片
     * @return string info[0].silide[].url 链接
	 * @return array info[0].courseclass 课程分类 
     * @return string info[0].courseclass[].id
     * @return string info[0].courseclass[].name 技能名
     * @return string info[0].courseclass[].thumb 图标
     * @return array info[0].teacher 教师列表 
     * @return string info[0].teacher[].user_nickname 昵称
     * @return string info[0].teacher[].avatar 头像
     * @return string info[0].teacher[].sex 性别
     * @return array info[0].teacher[].identitys 身份标识
     * @return string info[0].teacher[].identitys[].name 名称
     * @return string info[0].teacher[].identitys[].colour 颜色
     * @return array info[0].course 好课推荐 
     * @return string info[0].course[].id 课程ID
     * @return string info[0].course[].uid 教师ID
     * @return string info[0].course[].user_nickname 教师昵称
     * @return string info[0].course[].avatar 教师头像
     * @return string info[0].course[].sort 类别，0内容1课程2直播
     * @return string info[0].course[].type 形式，1图文2视频3音频
     * @return string info[0].course[].name 名称
     * @return string info[0].course[].thumb 封面
     * @return string info[0].course[].paytype 获取形式，0免费1收费2密码
     * @return string info[0].course[].payval 价格位置显示内容，根据paytype区分颜色
     * @return string info[0].course[].lesson 课时位置显示内容
     * @return string info[0].course[].islive 是否在直播，0否1是
     * @return array info[0].live 直播课堂，同好课推荐
     * @return array info[0].list 精选内容，同好课推荐
     * @return array info[0].packs 精选套餐 详见 套餐列表
     * @return string msg 提示信息
     */
	public function getIndex() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $gradeid = \App\checkNull($this->gradeid);
        
        $nowtime=time();
        $Domain_Cart = new Domain_Cart();
        $where6=[
            'uid'=>$uid,
        ];
		$cartnums = $Domain_Cart->getNums($where6);
        $info['cartnums']=$cartnums;
        
        /* 轮播 */
        $domain = new Domain_Home();
		$silide = $domain->getSilide();
        $info['silide']=$silide;
        
        /* 课程分类 */
        $Domain_Course = new Domain_Course();
		$courseclass = $Domain_Course->getClass();
        $info['courseclass']=$courseclass;
        
		
		/* 教师列表 */
        $Domain_Teacher = new Domain_Teacher();
		$teacher = $Domain_Teacher->getTeachers(1,[],'courses desc,list_order asc',5);
        $info['teacher']=$teacher;

		/* 直播课堂 */
        $where3=[
            'gradeid'=>$gradeid,
            'status>?'=>0,
            'shelvestime<?'=>$nowtime,
            'sort>=?'=>2,
            'paytype!=?'=>1,
        ];
		$live = $Domain_Course->getList(1,$where3,'list_order asc,id desc',3);
        $info['live']=$live;
		
		/* 精选内容 */
        $where4=[
            'gradeid'=>$gradeid,
            'status>?'=>0,
            'shelvestime<?'=>$nowtime,
            'sort'=>0,
            'paytype!=?'=>1,
        ];
		$list = $Domain_Course->getList(1,$where4,'list_order asc,id desc',3);
        $info['list']=$list;

        $rs['info'][0] = $info;
		
        return $rs;
	}

    /**
     * 搜索
     * @desc 用于获取搜索信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0].teacher 教师
     * @return string info[0].teacher[].id
     * @return string info[0].teacher[].name 技能名
     * @return string info[0].teacher[].thumb 图标
     * @return array info[0].course 课程
     * @return string info[0].course[].id 昵称
     * @return string info[0].course[].user_nickname 昵称
     * @return string info[0].course[].avatar 头像
     * @return string info[0].course[].sex 性别
     * @return string info[0].course[].age 年龄
     * @return string msg 提示信息
     */
	public function search() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = \App\checkNull($this->uid);
        $keyword = \App\checkNull($this->keyword);
        $gradeid = \App\checkNull($this->gradeid);
        
        if($keyword==''){
            $rs['code'] = 1001;
            $rs['msg'] = \PhalApi\T('请输入关键词');
            return $rs;
        }
        
        /* 教师列表 */
        $where=[
            'id!=?'=>$uid,
            'user_nickname like ?'=>'%'.$keyword.'%',
        ];
        
        $Domain_Teacher = new Domain_Teacher();
		$teacher = $Domain_Teacher->getTeachers(1,$where,'list_order asc,id desc',3);
        $info['teacher']=$teacher;
		
		/* 好课推荐 */
        $where2=[
            'gradeid'=>$gradeid,
            'uid!=?'=>$uid,
            'name like ?'=>'%'.$keyword.'%',
        ];
        $Domain_Course = new Domain_Course();
		$course = $Domain_Course->getList(1,$where2,'list_order asc,id desc');
        $info['course']=$course;
        
        $rs['info'][0] = $info;
		
        return $rs;
	}    
    

}
