<?php

/* 分享页面 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class ShareController extends HomebaseController{

	public function index() {

        $data      = $this->request->param();
        $configpri=getConfigPri();
        $code=$data['code'] ?? '';
        if($configpri['agent_switch']!=1){
            $code='';
        }
        $userinfo=[];
        if($code!=''){
            $agentinfo=Db::name('agent_code')->where('code',$code)->find();
            if($agentinfo){
                $userinfo=getUserInfo($agentinfo['uid']);
            }
        }
        if(!$userinfo){
            $code='';
        }

        $this->assign('uid', '');
        $this->assign('token', '');
        $this->assign('code', $code);
        $this->assign('userinfo', $userinfo);


        $sharetrace_key=$configpri['sharetrace_key']??'';
        if($configpri['agent_type']!=1 || $code==''){
            $sharetrace_key='';
        }
        $this->assign('sharetrace_key', $sharetrace_key);

        return $this->fetch('index');
	}	
    
    public function course() {

        $data      = $this->request->param();

        $id=$data['id'] ?? '0';
        $code=$data['code'] ?? '';

        $this->assign('uid', '');
        $this->assign('token', '');
        $this->assign('code', $code);

        if($id<1){
            return $this->index();
        }

        //用户打开课程页面后, 分享人获得积分, 每天仅前3次成功才获得积分
        $now_time = time();
        // 当天结束的时间戳
        $today_end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1; //23:59:59
        $remain_time = $today_end - $now_time;

        $uid = db('agent_code')->where('code', $code)->value('uid');
        $todayShareNum = getcaches('today_share_nums_' . $uid);
        if (!$todayShareNum) {
            setcaches('today_share_nums_' . $uid, 1, $remain_time);
        } else {
            $todayShareNum = (int)$todayShareNum;
            if ($todayShareNum <= 3) {
                //增加积分
                db('users')->where('id', $uid)->setInc('integral');
                setcaches('today_share_nums_' . $uid, ($todayShareNum+1), $remain_time);
            }
        }

        $info=Db::name('course')->where('id',$id)->find();
        if(!$info){
            return $this->index();
        }

        $info=$this->handelInfo($info);

        $info['userinfo']=getUserInfo($info['uid']);

        //unset($info['info']);
        //unset($info['content']);
        unset($info['uptime']);

        /* 辅导老师 */
        $tutor=[];
        if($info['tutoruid']>0){
            $tutor[]=getUserInfo($info['tutoruid']);
        }
        unset($info['tutoruid']);

        $info['tutor']=$tutor;

        $this->assign('info', $info);

        /* 评论 */
        $star=$info['star'];

        $list= Db::name('course_com')->field('id,uid,sort,content,star,lessons,addtime')->where(['courseid'=>$info['id']])->order('id desc')->limit(0,20)->select()->toArray();;
        foreach($list as $k=>$v){
            $userinfo=getUserInfo($v['uid']);

            $v['user_nickname']=$userinfo['user_nickname'];
            $v['avatar']=$userinfo['avatar'];
            $v['add_time']=date('Y-m-d',$v['addtime']);
            $des="";
            if($v['sort']==1){
                $des="学习{$v['lessons']}个课时评价";
            }
            $v['des']=$des;
            unset($v['lessons']);

            $list[$k]=$v;
        }

        if( !$list){
            $star='5.0';
        }

        $this->assign('star', $star);
        $this->assign('list', $list);

        $lessons=[];
        if($info['sort']==1){
            $lessons=Db::name('course_lesson')->field('id,uid,courseid,pid,type,name,des,url,istrial,islive,starttime')->where('courseid',$info['id'])->order('list_order asc')->select()->toArray();
            $lessons=$this->handelLesson($lessons,0);

            foreach($lessons as $k=>$v){
                foreach($v['list'] as $k1=>$v1){

                    $status='0';
                    if($v1['istrial']==1){
                        /* 试学 */
                        $status='1';
                    }

                    if($v1['islive']==1){
                        /* 在直播 */
                        $status='3';
                    }

                    $v1['status']=$status;

                    $type_t=$this->getLessonTypes($v1['type']);

                    $time='';
                    if($v1['type']>=4){
                        //if($v1['islive']==0){
                        $time=date('m月d日 H:i',$v1['starttime']).' '.$type_t;
                        //}
                    }
                    $v1['time_date']=$time;

                    $v['list'][$k1]=$v1;
                }
                $lessons[$k]=$v;
            }
        }

        $this->assign('lessons', $lessons);

        return $this->fetch();
	}

    public function package() {

        $data      = $this->request->param();

        $id=$data['id'] ?? '0';
        $code=$data['code'] ?? '';

        $this->assign('uid', '');
        $this->assign('token', '');
        $this->assign('code', $code);

        if($id<1){
            return $this->index();
        }

        $info=Db::name('course_package')->where('id',$id)->find();
        if(!$info){
            return $this->index();
        }

        $info['thumb']=get_upload_path($info['thumb']);

        $ismaterial='0';
        $views='0';
        $coursesids=handelSetToArr($info['courseids']);

        $courses=Db::name('course')->where([['id','in',$coursesids]])->select()->toArray();

        foreach($courses as $k=>$v){
            $v=$this->handelInfo($v);
            $views+=$v['views'];

            $userinfo=getUserInfo($v['uid']);
            $v['user_nickname']=$userinfo['user_nickname'];
            $v['avatar']=$userinfo['avatar'];

            $courses[$k]=$v;
        }

        $info['courses']=$courses;
        $info['ismaterial']=$ismaterial;
        $info['views']=(string)$views;

        $this->assign('info', $info);

        return $this->fetch();
    }

    /* 内容形式 */
    protected function getLessonTypes($k=''){
        $type=[
            '1'=>'图文自学',
            '2'=>'视频自学',
            '3'=>'音频自学',
            '4'=>'PPT讲解',
            '5'=>'视频讲解',
            '6'=>'音频讲解',
            '8'=>'普通直播',
            '7'=>'白板互动',
        ];
        if($k===''){
            return $type;
        }
        return isset($type[$k])? $type[$k] : '' ;
    }

    protected function handelLesson($list=[],$pid=0){
        $rs=[];
        foreach($list as $k=>$v){
            if($v['pid']==$pid){
                unset($list[$k]);
                $v['list']=$this->handelLesson($list,$v['id']);
                $rs[]=$v;
            }
        }

        return $rs;
    }

    /* 内容形式 */
    protected function getTypes($k=''){
        $type=[
            '1'=>'图文自学',
            '2'=>'视频自学',
            '3'=>'音频自学',
        ];
        if($k==''){
            return $type;
        }
        return isset($type[$k])? $type[$k] : '' ;
    }

    /* 内容形式 */
    /* 直播形式 */
    protected function getLiveTypes($k=''){
        $type=[
            '1'=>'PPT讲解',
            '2'=>'视频讲解',
            '3'=>'音频讲解',
        ];
        if($k===''){
            return $type;
        }
        return isset($type[$k])? $type[$k] : '' ;
    }

    /* 处理课程信息 */
    protected function handelInfo($v){
        $v['thumb']=get_upload_path($v['thumb']);
        $nowtime=time();
        $payval='免费';
        $lesson='';
        $type_t='';
        $sort=$v['sort'];
        /* 内容 */
        if($sort==0){
            $lesson=$this->getTypes($v['type']);
            $type_t=$this->getTypes($v['type']);
        }

        /* 课程 */
        if($sort==1){
            $lesson='尚未添加内容';
            if($v['lessons']>0){
                $lesson=$v['lessons'].'课时';
            }
        }

        /* 直播 */
        if($sort==2){
            $type_t=$this->getLiveTypes($v['type']);
        }
        if($sort==3){
            $type_t='普通直播';
        }
        if($sort>=2){
            if($v['islive']==0){
                $lesson=handelsvctm($v['starttime']);
            }
            if($v['islive']==1){
                $lesson='正在直播';
            }
            if($v['islive']==2){
                $lesson='直播结束';
            }
        }

        $paytype=$v['paytype'];
        if($paytype==1){
            //$payval=number_format($v['payval'],2);
            $payval=$v['payval'];
        }

        if($paytype==2){
            $payval='密码';
        }


        $v['payval']=$payval;
        $v['lesson']=$lesson;
        $v['type_t']=$type_t;
        unset($v['status']);
        unset($v['shelvestime']);
        unset($v['lessons']);
        unset($v['starttime']);

        if(isset($v['addtime'])){
            $v['add_time']=date('Y-m-d',$v['addtime']);
        }

        return $v;
    }


}