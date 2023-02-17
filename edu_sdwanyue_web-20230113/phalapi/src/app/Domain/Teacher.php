<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/06 09:48
// +----------------------------------------------------------------------
namespace App\Domain;

use App\Model\Teacher as Model_Teacher;

class Teacher {
    
    
    /* 教师列表 */
    public function getTeachers($p=1,$where=[],$order='courses desc,list_order asc',$nums=0){
        
        $model = new Model_Teacher();
        $list=$model->getTeachers($p,$where,$order,$nums);
        
        foreach($list as $k=>$v){
            $v=\App\handleUser($v);
            
            $list[$k]=$v;
        }
        
        return $list;
    }
	
    /* 讲师主页 */
    public function getHome($uid,$touid){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $model = new Model_Teacher();
        $info=$model->getInfo($touid);
        if(!$info){
            $rs['code'] = 1002;
            $rs['msg'] = \PhalApi\T('用户不存在');
            return $rs;
        }
        
        $info=\App\handleUser($info);
        
        if($info['school']==''){
            $info['school']='';
        }
        
        if($info['experience']==''){
            $info['experience']='';
        }
        
        if($info['feature']==''){
            $info['feature']='';
        }
        
        $isattent=\App\isAttent($uid,$touid);
            
        $info['isattent']=$isattent;
        
        $info['fans']=\App\getFansNum($touid);        
        
        $rs['info'][0]=$info;
        
        return $rs;
        
    }
    
    /* 讲师信息 */
    public function getInfo($uid,$touid){
        
        $model = new Model_Teacher();
        $info=$model->getInfo($touid);
        if(!$info){
            $rs['code'] = 1002;
            $rs['msg'] = \PhalApi\T('用户不存在');
            return $rs;
        }
        
        $info=\App\handleUser($info);
        
        if($info['school']==''){
            $info['school']='';
        }
        
        if($info['experience']==''){
            $info['experience']='';
        }
        
        if($info['feature']==''){
            $info['feature']='';
        }
        
        $isattent=\App\isAttent($uid,$touid);
            
        $info['isattent']=$isattent;

        
        return $info;
        
    }
	
}
