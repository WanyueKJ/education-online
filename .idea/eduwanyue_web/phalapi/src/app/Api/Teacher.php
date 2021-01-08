<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/03 09:22
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\Teacher as Domain_Teacher;

header("Access-Control-Allow-Origin: *");

/**
 * 讲师
 */
class Teacher extends Api {

	public function getRules() {
		return array(
            'getTeachers' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
			),
            
            'search' => array(
				'keyword' => array('name' => 'keyword', 'type' => 'string', 'desc' => '关键词'),
				'p' => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
			),
            
            'getHome' => array(
                'touid' => array('name' => 'touid', 'type' => 'int', 'desc' => '讲师ID'),
            ),
		);
	}
    
	/**
	 * 名师名课列表 
	 * @desc 用于获取名师名课列表
	 * @return int code 操作码，0表示成功
	 * @return array info 列表
     * @return string info[].total 总数
	 * @return string msg 提示信息
	 */
	public function getTeachers() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=\App\checkNull($this->uid);
        $p=\App\checkNull($this->p);
        
        
        $where=[
            'id!=?'=>$uid
        ];
        $domain = new Domain_Teacher();
		$list = $domain->getTeachers($p,$where);
        

        foreach($list as $k=>$v){
            $isattent=\App\isAttent($uid,$v['id']);
            $v['isattent']=$isattent;
            
            $list[$k]=$v;
        }
        
        $rs['info']=$list;
        
		return $rs;
	}

	/**
	 * 搜索教师 
	 * @desc 用于获取搜索教师列表
	 * @return int code 操作码，0表示成功
	 * @return array info 列表
     * @return string info[].total 总数
	 * @return string msg 提示信息
	 */
	public function search() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=\App\checkNull($this->uid);
        $keyword=\App\checkNull($this->keyword);
        $p=\App\checkNull($this->p);
        
        if($keyword==''){
            $rs['code'] = 1001;
            $rs['msg'] = \PhalApi\T('请输入关键词');
            return $rs;
        }
        
        $where=[
            'id!=?'=>$uid,
            'user_nickname like ?'=>'%'.$keyword.'%',
        ];
        $domain = new Domain_Teacher();
		$list = $domain->getTeachers($p,$where);

        
        $rs['info']=$list;
        
		return $rs;
	}
    
    /**
	 * 讲师主页
	 * @desc 用于讲师主页信息
	 * @return int code 操作码，0表示成功
	 * @return array info 用户基本信息
	 * @return string info[].isattent 是否关注，0否1是
	 * @return string info[].fans 学员数
	 * @return string msg 提示信息
	 */
	public function getHome() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $uid=\App\checkNull($this->uid);
        $token=\App\checkNull($this->token);
        $touid=\App\checkNull($this->touid);
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        if($uid<1 || $touid<1){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }

        $domain = new Domain_Teacher();
		$res = $domain->getHome($uid,$touid);

		return $res;
	}
    

}
