<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/07 16:32
// +----------------------------------------------------------------------
namespace App\Domain;

use App\Model\Ware as Model_Ware;
use App\Model\Course as Model_Course;

class Ware {

    /* 课件列表 */
	public function getList($where) {


        $model = new Model_Ware();
        $list= $model->getList($where);
        foreach($list as $k=>$v){
            $v['url']=\App\get_upload_path($v['url']);
            
            $list[$k]=$v;
        }

		return $list;
	}

    /* 课件列表 */
	public function getWare($data) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=$data['uid'];
        $liveuid=$data['liveuid'];
        $courseid=$data['courseid'];
        $lessonid=$data['lessonid'];
        
        $Model_Course = new Model_Course();
        $where=['uid'=>$liveuid,'id'=>$courseid];
        $courseinfo=$Model_Course->getDetail($where);
        if(!$courseinfo){
            $rs['code'] = 1002;
			$rs['msg'] = \PhalApi\T('课程不存在');
			return $rs;
        }
        
        if($courseinfo['paytype']!=0){
            $where2=['uid'=>$uid,'courseid'=>$courseid,'status'=>1];
            $ispay=$Model_Course->getBuy($where2);
            if(!$ispay){
                return $rs;
            }
        }
        
        $where3=['uid'=>$liveuid,'courseid'=>$courseid,'lessonid'=>$lessonid];
        $model = new Model_Ware();
        $list= $this->getList($where3);
        
        $rs['info']=$list;

		return $rs;
	}
	
	
}
