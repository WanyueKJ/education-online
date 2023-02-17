<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/04 13:15
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\User as Domain_User;

/**
 * 用户信息
 */
class User extends Api {
    public function getRules() {
        return array(
            'getBaseInfo' => array(
                'ios_version' => array('name' => 'ios_version', 'type' => 'string', 'default'=>'', 'desc' => 'IOS版本号'),
            ),
            
            'upUserInfo' => array(
                'fields' => array('name' => 'fields', 'type' => 'string', 'default'=>'', 'desc' => '修改信息json串'),
            ),
            
            'setAttent' => array(
                'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '对方ID'),
            ),
            
            'getFollow' => array(
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1', 'desc' => '页码'),
            ),
            
            'getFans' => array(
                'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '对方ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1', 'desc' => '页码'),
            ),
            
            'getHome' => array(
                'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '对方ID'),
            ),
            'checkAttent' => array(
                'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '对方ID'),
            ),
        );
    }

	/**
	 * 判断token
	 * @desc 用于判断token
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function iftoken() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);

		$checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
		return $rs;
	}
    
	/**
	 * 获取用户信息
	 * @desc 用于获取单个用户基本信息
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return array info[0] 用户信息
	 * @return int info[0].id 用户ID
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].type 类型0学员1讲师
	 * @return string info[0].school 院校
	 * @return string info[0].experience 教育经历
	 * @return string info[0].feature 教育特点
	 * @return string msg 提示信息
	 */
	public function getBaseInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        
		$checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBaseInfo($uid);
        if(!$info){
            $rs['code'] = 700;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
        }

		$configpub=\App\getConfigPub();

		$ios_shelves=$configpub['ios_shelves'];
		

		/* 个人中心菜单 */
        $ios_version=\App\checkNull($this->ios_version);

		$list=array();
		$shelves=1;
		if($ios_version && $ios_version==$ios_shelves){
			$shelves=0;
		}
        
        $list[]=array('id'=>'1','name'=>\PhalApi\T('已购买'),'thumb'=>\App\get_upload_path("/static/app/person/buy.png"),'href'=>'' );
        $list[]=array('id'=>'2','name'=>\PhalApi\T('我的课件'),'thumb'=>\App\get_upload_path("/static/app/person/kejian.png"),'href'=>'' );
        $list[]=array('id'=>'4','name'=>\PhalApi\T('关于我们'),'thumb'=>\App\get_upload_path("/static/app/person/about.png"),'href'=>'' );

		$list[]=array('id'=>'6','name'=>\PhalApi\T('设置'),'thumb'=>\App\get_upload_path("/static/app/person/set.png") ,'href'=>'');

		$info['list']=$list;

		$rs['info'][0] = $info;

		return $rs;
	}
    
    /**
	 * 更新基本信息
	 * @desc 用于用户更新基本信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function upUserInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $fields=$this->fields;
        
        if($fields==''){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }

        $fields_a=json_decode($fields,true);
        if(!$fields_a){
            $rs['code'] = 1002;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }

        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        unset($fields_a['sex']);
        
		$domain = new Domain_User();
		$info = $domain->upUserInfo($uid,$fields_a);
	 
		return $info;
	}
    
    /**
	 * 设置、取消关注
	 * @desc 用于设置、取消关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
     * @return string info[0].isattent 是否关注，0否1是
	 * @return string msg 提示信息
	 */
	public function setAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $touid=\App\checkNull($this->touid);

        if($touid<1){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }

		$checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        if($uid==$touid){
            $rs['code'] = 1002;
			$rs['msg'] = \PhalApi\T('不能关注自己');
			return $rs;
        }
        
        $domain = new Domain_User();
		$isattent = $domain->setAttent($uid,$touid);

        $info['isattent']=$isattent;
        $msg=\PhalApi\T('取消成功');
        if($isattent==1){
            $msg=\PhalApi\T('关注成功');
        }
        
        $rs['msg']=$msg;
        $rs['info'][0]=$info;
        
		return $rs;
	}

    /**
	 * 关注列表
	 * @desc 用于获取用户关注列表
	 * @return int code 操作码，0表示成功
	 * @return array info
     * @return object info[] 用户基本信息
     * @return string info[].isattent 是否关注，0否1是
	 * @return string msg 提示信息
	 */
	public function getFollow() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $p=\App\checkNull($this->p);
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_User();
		$list = $domain->getFollow($uid,$uid,$p);

        $rs['info']=$list;
        
		return $rs;
	}

    /**
	 * 粉丝列表
	 * @desc 用于获取用户粉丝列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return object info[] 用户基本信息
	 * @return string info[].isattent 是否关注，0否1是
	 * @return string msg 提示信息
	 */
	public function getFans() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $touid=\App\checkNull($this->touid);
        $p=\App\checkNull($this->p);
        if($touid<1){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }

        $domain = new Domain_User();
		$list = $domain->getFans($uid,$touid,$p);

        $rs['info']=$list;

		return $rs;
	}


    /**
	 * 检测关系
	 * @desc 用于检测两个用户间的关注情况
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string info[0].status 状态，0表示未关注，1表示我关注对方，2表示对方关注我，3表示互关
	 * @return string msg 提示信息
	 */
	public function checkAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $touid=\App\checkNull($this->touid);

        if($uid<1 || $token=='' || $touid<1){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_User();
		$status = $domain->checkAttent($uid,$touid);

        $rs['info'][0]['status']=$status;

		return $rs;
	}
} 
