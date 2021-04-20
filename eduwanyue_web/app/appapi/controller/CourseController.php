<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/12 17:08
// +----------------------------------------------------------------------
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

/*
 * 课程
 * Class CourseController
 * @package app\appapi\controller
 */
class CourseController extends HomebaseController{

	function info(){       
		$data = $this->request->param();
        $uid= $data['uid'] ?? '';
        $token=$data['token'] ?? '';
        $courseid= $data['courseid'] ?? '';
        $uid=(int)checkNull($uid);
        $courseid=(int)checkNull($courseid);
        $token=checkNull($token);
        
        $this->assign('uid', $uid);
        $this->assign('token', $token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason='您的登陆状态失效，请重新登陆！';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
        
        if($courseid<1){
            $reason='信息错误';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
		  
		$nowtime=time();

		$courseinfo=Db::name('course')->field('name,info')->where(["id"=>$courseid])->find();
		if(!$courseinfo){
            $reason='课程不存在';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }

		$this->assign("title",$courseinfo['name']);
		$this->assign("body",$courseinfo['info']);

		return $this->fetch();
	    
	}
    
    function content(){       
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $courseid=isset($data['courseid']) ? $data['courseid']: '';
        $uid=(int)checkNull($uid);
        $courseid=(int)checkNull($courseid);
        $token=checkNull($token);
        
        $this->assign('uid', $uid);
        $this->assign('token', $token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason='您的登陆状态失效，请重新登陆！';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
        
        if($courseid<1){
            $reason='信息错误';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
		  
		$nowtime=time();

		$courseinfo=Db::name('course')->field('name,sort,type,paytype,trialtype,trialval,content')->where(["id"=>$courseid])->find();
		if(!$courseinfo){
            $reason='课程不存在';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
        $isbuy='0';
        
        $sort=$courseinfo['sort'];
        $type=$courseinfo['type'];
        $paytype=$courseinfo['paytype'];
        $trialtype=$courseinfo['trialtype'];
        $trialval=$courseinfo['trialval'];
        $per=$trialval;
        if($sort==0){
            if($paytype!=0){
                $ispay=Db::name('course_users')->field('id')->where(["uid"=>$uid,"courseid"=>$courseid,"status"=>1])->find();
                if($ispay){
                    $per=0;
                    $isbuy=1;
                }
            }else{
                $isbuy=1;
            }
            
            if($isbuy==1){
                $this->setLesson($uid,$courseid);
            }
            
        }else{
            $isbuy=1;
            $per=0;
        }

		$this->assign("title",$courseinfo['name']);
		$this->assign("body",$courseinfo['content']);
		$this->assign("per",$per);
		$this->assign("isbuy",$isbuy);

		return $this->fetch();
	    
	}

    function lesson(){       
		$data = $this->request->param();
        $uid=isset($data['uid']) ? $data['uid']: '';
        $token=isset($data['token']) ? $data['token']: '';
        $lessonid=isset($data['lessonid']) ? $data['lessonid']: '';
        $uid=(int)checkNull($uid);
        $lessonid=(int)checkNull($lessonid);
        $token=checkNull($token);
        
        $this->assign('uid', $uid);
        $this->assign('token', $token);
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$reason='您的登陆状态失效，请重新登陆！';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
		}
        
        if($lessonid<1){
            $reason='信息错误';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
		  
		$nowtime=time();

		$lessoninfo=Db::name('course_lesson')->field('*')->where(["id"=>$lessonid])->find();
        if(!$lessoninfo){
            $reason='课时不存在';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
        
        if($lessoninfo['pid']==0){
            /* 章节 */
            $this->assign("title",$lessoninfo['name']);
            $this->assign("body",'');

            return $this->fetch();
        }

        $courseid=$lessoninfo['courseid'];
		$courseinfo=Db::name('course')->field('name,sort,type,paytype')->where(["id"=>$courseid])->find();
		if(!$courseinfo){
            $reason='课程不存在';
			$this->assign('reason', $reason);
			return $this->fetch(':error');
        }
        $isbuy='0';
        
        $paytype=$courseinfo['paytype'];

        $body='';
        if($paytype!=0){
            if($lessoninfo['istrial']==1){
                $isbuy=1;
            }else{
                $ispay=Db::name('course_users')->field('id')->where(["uid"=>$uid,"courseid"=>$courseid,"status"=>1])->find();
                if($ispay){
                    $isbuy=1;
                }
            }
            
        }else{
            $isbuy=1;
            
        }
        if($isbuy==1){
            $body=$lessoninfo['content'];
            $this->setLesson($uid,$courseid,$lessonid);
        }

		$this->assign("title",$lessoninfo['name']);
		$this->assign("body",$body);


		return $this->fetch();
	    
	}
    
    /* 更新进度 */
    protected function setLesson($uid,$courseid,$lessonid=0){
        $nowtime=time();
        $isview=Db::name('course_views')->where(['uid'=>$uid,'courseid'=>$courseid,'lessonid'=>$lessonid])->find();
        if($isview){
            Db::name('course_views')->where(["id"=>$isview['id']])->setField('addtime',$nowtime);
            return !1;
        }
        
        $course=Db::name('course')->field('sort,type,paytype,lessons,uid')->where(["id"=>$courseid])->find();
        if(!$course){
            return !1;
        }
        
        $sort=$course['sort'];
        
        $data=[
            'uid'=>$uid,
            'sort'=>$sort,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
            'addtime'=>$nowtime
        ];
        Db::name('course_views')->insert($data);
        
        $nums=Db::name('course_views')->where(['uid'=>$uid,'courseid'=>$courseid])->count();
        if($nums<2){
            /* 同一课程下的课时 记一次课程学习数 */
            Db::name('course')->where(["id"=>$courseid])->setInc('views',1);
        }

        $isexist=Db::name('course_users')->where(['uid'=>$uid,'courseid'=>$courseid])->find();
        if(!$isexist){
            /*  */
            $status=0;
            $paytype=$course['paytype'];
            if($paytype==0){
                $status=1;
            }
            $data2=[
                'uid'=>$uid,
                'sort'=>$course['sort'],
                'paytype'=>$paytype,
                'courseid'=>$courseid,
                'liveuid'=>$course['uid'],
                'status'=>$status,
                'addtime'=>$nowtime,
                'paytime'=>$nowtime,
            ];
            Db::name('course_users')->insert($data2);
            
            $isexist=Db::name('course_users')->where(['uid'=>$uid,'courseid'=>$courseid])->find();
        }
        
        if($lessonid>0){
            Db::name('course_users')->where(['id'=>$isexist['id']])->setInc('lessons',1);
            
            $lessons= Db::name('course_users')->where(['id'=>$isexist['id']])->value('lessons');
            if($lessons>=$course['lessons']){
                /* 看完 */
                Db::name('course_users')->where(['id'=>$isexist['id']])->setField('step',2);
            }else{
                Db::name('course_users')->where(['id'=>$isexist['id']])->setField('step',1);
            }
        }else{
            Db::name('course_users')->where(['id'=>$isexist['id']])->setField('step',2);
        }
    }
	

}