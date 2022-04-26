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
use cmf\controller\TeacherBaseController;
use think\Db;
/**
 * 账户
 */
class AccountController extends TeacherBaseController {
    
	public function index() {
        
        $cur='account';
        $this->assign('cur',$cur);

        return $this->fetch();
    }
    
    public function uploadImg(){
        
		$data      = $this->request->param();
        
        $uid=session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }

        if($uid<1){
            $this->error('您的登陆状态失效，请重新登陆！');
        }
        
        $file=$_FILES['file'];
        if(!$file){
            $this->error('请选择图片');
        }

        $res=upload();
        if($res['code']!=0){
            $this->error($res['msg']);
        }
        $thumb=$res['url'];
        
        $data=[
            'avatar'=>$thumb.'?imageView2/2/w/600/h/600',
            'avatar_thumb'=>$thumb.'?imageView2/2/w/150/h/150',
        ];
        
        $isok= UsersModel::where(['id'=>$uid])->update($data);
        if($isok===false){
            $this->error('更换失败，请重试');
        }
        
        $_SESSION['teacher']['avatar'] = $data['avatar'];
        $_SESSION['teacher']['avatar_thumb'] = $data['avatar_thumb'];
		
		$this->success('操作成功');
	}
    
    
    public function upname(){
        
		$data      = $this->request->param();
        
        $uid=session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }
        
        if($uid<1){
            $this->error('您的登陆状态失效，请重新登陆！');
        }
        
        $name=isset($data['name']) ? checkNull($data['name']): '0';
        
        if($name==''){
            $this->error('请输入昵称');
            
        }
        
        $count=mb_strlen($name);
        if($count>10){
            $this->error('昵称最多10个字');
        }
        
        $isexist=UsersModel::where([['id','<>',$uid],['user_nickname','=',$name]])->find();

        if($isexist){
            $this->error('昵称已存在');
        }
        

        $data=[
            'user_nickname'=>$name,
        ];
        
        $isok=UsersModel::where(['id'=>$uid])->update($data);
        if($isok===false){
            $this->error('更换失败，请重试');
        }

        $_SESSION['teacher']['user_nickname'] = $name;
		
		$this->success('操作成功');
	}
}


