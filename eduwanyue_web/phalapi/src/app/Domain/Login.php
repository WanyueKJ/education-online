<?php

namespace App\Domain;

use App\Model\Login as Model_Login;
use App\Domain\Course as Domain_Course;

class Login {

    public function loginByCode($user_login,$source) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        

        $where=[
            'user_login = ?'=>$user_login,
        ];
        
        $model = new Model_Login();
        $info = $model->userLogin($where);
        
        if(!$info){
            /* 注册 */
            $nickname=\PhalApi\T('用户').substr($user_login,-4);
            $data=array(
                'user_login' => $user_login,
                'user_nickname' => $nickname,
                "source"=>$source,
                "mobile"=>$user_login,
            );
            $model = new Model_Login();
            $info = $model->userReg($data);		
        }
        
        
        $info=$this->handleInfo($info);
  
        return $info;

    }

    public function login($user_login,$pass) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        

        $where=[
            'user_login = ?'=>$user_login,
        ];
        
        $model = new Model_Login();
        $info = $model->userLogin($where);
        
        // if(!$info){
            // $rs['code']=1001;	
            // $rs['msg']=\PhalApi\T('该手机号还未注册');
			// return $rs;
        // }
		
		if(!$info || $info['user_pass']!= \App\setPass($pass)){
			$rs['code']=1002;	
            $rs['msg']=\PhalApi\T('账号或密码错误');
			return $rs;
		}
        
        
        $info=$this->handleInfo($info);
  
        return $info;

    }
    
    public function reg($user_login,$user_pass,$source) {
        
        $rs = array('code' => 0, 'msg' => \PhalApi\T('注册成功'), 'info' => array());
        /* 注册 */
        $nickname=\PhalApi\T('用户').substr($user_login,-3);
        $data=array(
            'user_login' => $user_login,
            'user_pass' => $user_pass,
            'user_nickname' => $nickname,
            'mobile' => $user_login,
            "source"=>$source,
        );
        $model = new Model_Login();
        $info = $model->userReg($data);
        
		$info=$this->handleInfo($info);
  
        return $info;
        
    }
	
    public function userLoginByThird($openid,$type,$nickname,$avatar,$source) {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Login();
        
        $nowtime=time();
        
        $where=[
            'openid = ?'=>$openid,
            'login_type = ?'=>$type,
        ];
        
        $info = $model->userLogin($where);
        
        if(!$info){
            /* 注册 */
            $type_a=['web','qq','wx','sina','facebook','twitter'];
			$user_login=$type_a[$type].'_'.$nowtime.rand(100,999);

			if(!$nickname){
				//$nickname=\PhalApi\T('用户').substr($openid,-3);
				$nickname='';
			}

			$data=array(
				'user_login' => $user_login,
				'user_nickname' =>$nickname,
				"source"=>$source,
				"openid"=>$openid,
				"login_type"=>$type,
			);
            
            if($avatar){
				$avatar=htmlspecialchars_decode($avatar);
                $avatar_thumb=$avatar;
                
                $data['avatar']=$avatar;
                $data['avatar_thumb']=$avatar_thumb;
			}
            
            $info = $model->userReg($data);
        }

		$info=$this->handleInfo($info);
  
        return $info;

    }
    
    public function forget($user_login,$user_pass) {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        /* 注册 */
        $data=array(
            'user_pass' => $user_pass,
        );
        $model = new Model_Login();
        $info = $model->forget($user_login,$data);

  
        return $rs;
        
    }
    
    public function upUserPush($uid,$pushid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->upUserPush($uid,$pushid);

        return $rs;
    }	
    
    protected function handleInfo($info){
        $rs = array('code' => 0, 'msg' => \PhalApi\T('登录成功'), 'info' => array());
        
		if($info['user_status']=='0'){
			$rs['code'] = 1004;
            $rs['msg'] = \PhalApi\T('该账号已被禁用');
            return $rs;	
        }
        
		unset($info['user_status']);
		unset($info['user_pass']);
		
		$info['isreg']='0';
		if(!$info['last_login_time']){
			$info['isreg']='1';
		}
        unset($info['last_login_time']);
        
        $gradeid=$info['gradeid'];
        $gradename='';
        if($gradeid!=0){
            $Domain_Course = new Domain_Course();
            $gradeinfo=$Domain_Course->getGradeInfo($gradeid);
            if($gradeinfo){
                $gradename=$gradeinfo['name'];
            }else{
                $gradeid='0';
            }
        }
        
        $info['gradeid']=(string)$gradeid;
        $info['gradename']=(string)$gradename;

		$info=\App\handleUser($info);

        
        \App\delcache('userinfo_'.$info['id']);
        
        $model = new Model_Login();
        $token=md5(md5($info['id'].$info['user_nickname'].time()));
		$info['token']=$token;
		$model->updateToken($info['id'],$token);
        
        
        $rs['info'][0]=$info;
        return $rs;
    }

}
