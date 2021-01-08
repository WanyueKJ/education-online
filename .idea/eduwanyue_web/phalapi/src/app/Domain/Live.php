<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/05 11:45
// +----------------------------------------------------------------------
namespace App\Domain;

use App\Model\Live as Model_Live;
use App\Domain\Course as Domain_Course;

class Live {

    /* 检测是否直播 */
	public function checkLive($uid,$liveuid,$courseid,$lessonid) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        
        $nowtime=time();
        $where2=[
            'uid'=>$uid,
            'liveuid'=>$liveuid,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
        ];
        $iskick= $this->isKick($where2);
        if($iskick){
            $rs['code'] = 1004;
            $rs['msg'] = \PhalApi\T('您已被踢出');
            return $rs;
        }
        
        $islive=0;
        $model = new Model_Live();
        if($lessonid>0){
            $where=[
                'id'=>$lessonid,
                'uid'=>$liveuid,
                'courseid'=>$courseid,
            ];
            
            $info= $model->getLiveLesson($where);
            
            if(!$info){
                $rs['code'] = 1002;
                $rs['msg'] = \PhalApi\T('课时不存在');
                return $rs;
            }
            
            if($info['type']<4){
                $rs['code'] = 1003;
                $rs['msg'] = \PhalApi\T('当前非直播课程');
                return $rs;
            }
            $islive=$info['islive'];
            if($info['type']==7){
                if($islive==0){
                    $rs['code'] = 1004;
                    $rs['msg'] = \PhalApi\T('当前直播还未开始');
                    return $rs;
                }

            }
            
            
        }else{
            $where=[
                'id'=>$courseid,
                'uid'=>$liveuid,
            ];
            
            $info= $model->getLiveCourse($where);
            
            if(!$info){
                $rs['code'] = 1002;
                $rs['msg'] = \PhalApi\T('课程不存在');
                return $rs;
            }
            if($info['sort']<2){
                $rs['code'] = 1003;
                $rs['msg'] = \PhalApi\T('当前非直播课程');
                return $rs;
            }
            $islive=$info['islive'];
        }
        
        $rsinfo=[
            'islive'=>(string)$islive,
        ];

        $rs['info'][0]=$rsinfo;

		return $rs;
	}
    
    /* 进入房间 */
	public function enter($uid,$token,$liveuid,$courseid,$lessonid) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        
        $where2=[
            'uid'=>$uid,
            'liveuid'=>$liveuid,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
        ];
        $iskick= $this->isKick($where2);
        if($iskick){
            $rs['code'] = 1004;
            $rs['msg'] = \PhalApi\T('您已被踢出');
            return $rs;
        }
        
        
        $where3=[
            'uid'=>$uid,
            'liveuid'=>$liveuid,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
        ];
        $shutup_me = $this->isShutup($where3);
        
        $shutup_room = '0';
        
        $uuid='';
        $roomtoken='';
        $sound_appid='';
        $islive='';
        $livemode='0';
        $pptindex='0';
        $url='';
        
        $model = new Model_Live();
        if($lessonid>0){
            $where=[
                'id'=>$lessonid,
                'uid'=>$liveuid,
                'courseid'=>$courseid,
            ];
            
            $liveinfo= $model->getLiveLesson($where);
            
            if(!$liveinfo){
                $rs['code'] = 1002;
                $rs['msg'] = \PhalApi\T('课时不存在');
                return $rs;
            }
            if($liveinfo['type']<4){
                $rs['code'] = 1003;
                $rs['msg'] = \PhalApi\T('当前非直播课程');
                return $rs;
            }
            $type=$liveinfo['type']-3;
            
            $uuid=\App\encryption($liveinfo['uuid']);
            $roomtoken=\App\encryption($liveinfo['roomtoken']);
            
            $url=\App\encryption(\App\get_upload_path($liveinfo['url']));
            
            $where=[
                'id'=>$courseid,
                'uid'=>$liveuid,
            ];
            $courseinfo= $model->getLiveCourse($where);
            if(!$courseinfo){
                $rs['code'] = 1004;
                $rs['msg'] = \PhalApi\T('课程不存在');
                return $rs;
            }
            
            $tutoruid=$courseinfo['tutoruid'];
            $pptindex=$liveinfo['pptindex'];
            
        }else{
            $where=[
                'id'=>$courseid,
                'uid'=>$liveuid,
            ];
            $liveinfo= $model->getLiveCourse($where);
            
            if(!$liveinfo){
                $rs['code'] = 1002;
                $rs['msg'] = \PhalApi\T('课程不存在');
                return $rs;
            }
            if($liveinfo['sort']<2){
                $rs['code'] = 1003;
                $rs['msg'] = \PhalApi\T('当前非直播课程');
                return $rs;
            }
            
            $type=$liveinfo['type'];
            
            $tutoruid=$liveinfo['tutoruid'];
            $livemode=$liveinfo['livemode'];
            $pptindex=$liveinfo['pptindex'];
        }
        
        $shutup_room=$liveinfo['isshup'];
        $islive=$liveinfo['islive'];
        $intr=$liveinfo['intr'];
        
        /* 用户身份 */
        $user_type='0';
        if($uid==$liveuid){
            $user_type='1';
        }
        
        if($uid==$tutoruid){
            $user_type='2';
        }
        
        $configpri=\App\getConfigPri();
        $userinfo=\App\getUserInfo($uid);

        $usertype='30';
        $pull=\App\encryption(\App\get_upload_path($liveinfo['url']));
        
        $userinfo['usertype']=$user_type;
        $userinfo['user_type']=$user_type;
        $userinfo['sign']='0';
        
        \App\setcaches($token,$userinfo);
        
        /* ppt */
        $ppts=[];
        if($type==1 || $type==5){
            $where3=[
                'courseid'=>$courseid,
                'lessonid'=>$lessonid,
                'uid'=>$liveuid,
            ];
            $ppts= $model->getPPT($where3);
            foreach($ppts as $k=>$v){
                $v['thumb']=\App\get_upload_path($v['thumb']);
                unset($v['uid']);
                unset($v['courseid']);
                unset($v['lessonid']);
                unset($v['list_order']);
                unset($v['addtime']);
                $ppts[$k]=$v;
            }
        }
        
        $sound_appid=\App\encryption($configpri['sound_appid']);
        
        
        
        $info=[
            'chatserver'=>$configpri['chatserver'],
            'pull'=>$pull,
            'stream'=>$liveuid.'_'.$courseid.'_'.$lessonid,
            'livetype'=>$type,
            'ppts'=>$ppts,
            'roomtoken'=>$roomtoken,
            'uuid'=>$uuid,
            'sound_appid'=>$sound_appid,
            
            'islive'=>$islive,
            
            'shutup_me'=>$shutup_me,
            'shutup_room'=>$shutup_room,
            
            'intr'=>$intr,
            'user_type'=>$user_type,
            
            'livemode'=>$livemode,
            'pptindex'=>$pptindex,
            'url'=>$url,
        ];
        
        /* 学习情况 */
        \App\setLesson($uid,$courseid,$lessonid);
        
        $rs['info'][0]=$info;
        
		return $rs;
	}
    /* 是否禁言 */
	public function isShutup($where) {
        
        $model = new Model_Live();
        $info= $model->getShutup($where);
        if($info){
            return '1';
        }
        
		return '0';
	}
    
    /* 是否踢出 */
	public function isKick($where) {
        
        $model = new Model_Live();
        $info= $model->getKick($where);
        if($info){
            return '1';
        }
        
		return '0';
	}
    
    
    /* 写入聊天 */
	public function setChat($data) {
        
        $model = new Model_Live();
        $rs= $model->setChat($data);
        
        
		return $rs;
	}

    /* 聊天列表 */
	public function getChat($uid,$liveuid,$courseid,$lessonid,$type,$lastid) {
        
        
        $where=[
            'id'=>$courseid,
            'uid'=>$liveuid,
        ];
        $model = new Model_Live();
        $liveinfo= $model->getLiveCourse($where);
        if(!$liveinfo){
            return [];
        }
        
        if($liveinfo['paytype']!=0){
            $istrial='0';
            if($lessonid>0){
                $where3=[
                    'id'=>$lessonid,
                    'uid'=>$liveuid,
                    'courseid'=>$courseid,
                ];
                $lessoninfo= $model->getLiveLesson($where3);
                if(!$lessoninfo){
                    return [];
                }
                
                $istrial=$lessoninfo['istrial'];
            }
            
            if(!$istrial){
                $where2=[
                    'uid'=>$uid,
                    'courseid'=>$courseid,
                    'status'=>1,
                ];
                $Domain_Course = new Domain_Course();
                $ifpay= $Domain_Course->getBuy($where2);
                if(!$ifpay){
                    return [];
                }
            }
        }
        
        $where3=[
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
        ];
        if($type==1){
            $where3['user_type!=?']=0;
        }
        if($type==2){
            $where3['status!=?']=0;
        }
        if($lastid>0){
            $where3['id<?']=$lastid;
        }
        $order='id desc';
        $model = new Model_Live();
        $list= $model->getChat($where3,$order);
        
        foreach($list as $k=>$v){
            $v['chatid']=$v['id'];
            
            $userinfo=\App\getUserInfo($v['uid']);
            $v['user_nickname']=$userinfo['user_nickname'];
            $v['avatar']=$userinfo['avatar'];
            if($v['type']==1){
                $v['url']=\App\get_upload_path($v['url']);
            }
            
            $v['add_time']=date('Y-m-d H:i:s',$v['addtime']);
            
            $list[$k]=$v;
        }
        
        $list=array_reverse($list);
        
        
		return $list;
	}
	
	/* 更新直播信息 */
	public function upLive($stream) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $stream_a=explode('_',$stream);
        $uid=isset($stream_a[0])?$stream_a[0]:0;
        $courseid=isset($stream_a[1])?$stream_a[1]:0;
        $lessonid=isset($stream_a[2])?$stream_a[2]:0;
        
        $model = new Model_Live();
        
        if($lessonid>0){
            
        }else{
            $where=[
                'id'=>$courseid,
                'uid'=>$uid,
                'sort'=>'3',
            ];
            
            $updata=[
                'livemode'=>0,
                'pptindex'=>0,
            ];
            $res= $model->upCourse($where,$updata);
        }
        
        
        
		return $rs;
	}
}
