<?php

namespace cmf\controller;
use think\Db;

class TeacherBaseController extends HomeBaseController
{

    public function initialize()
    {
        parent::initialize();
        $this->checkUserLogin();
    }

    public function checkUserLogin()
    {

        $userId = session('teacher.id');
        if (empty($userId)) {
            $userId=0;
        }
    
        if (empty($userId)) {
            if ($this->request->isAjax()) {
                $this->error("您尚未登录", cmf_url("teacher/login/index"));
            } else {
                $this->redirect(cmf_url("teacher/login/index"));
            }
        }
        
        $userinfo=Db::name('users')
					->where(['id'=>$userId])
					->find();
        if(!$userinfo){
            session("teacher", null);
            $this->redirect(cmf_url("teacher/login/index"));
        }
        
        if($userinfo['type']!=1){
            session("teacher", null);
            $this->error("您不是讲师，无法访问", "/" );
            return !1;
        }
        
        if($userinfo['user_status']==0){
            session("teacher", null);
            $this->error("该账号已被禁用", "/" );
            return !1;
        }
        
        $userinfo_s=session('teacher');
        $userinfo_s = is_array($userinfo_s) ? $userinfo_s : $userinfo_s->toArray();
        $userinfo=array_merge($userinfo_s,$userinfo);
        $userinfo=handleUser($userinfo);
        
        unset($userinfo['create_time']);
        unset($userinfo['last_login_time']);
        unset($userinfo['user_status']);
        unset($userinfo['user_pass']);
        unset($userinfo['last_login_ip']);
        unset($userinfo['openid']);
        unset($userinfo['source']);
        
        $this->assign('userinfo',$userinfo);
        $this->assign('userinfoj',json_encode($userinfo));
    }    
}