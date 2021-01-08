<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Login extends NotORM {

	protected $fields='id,user_nickname,user_pass,avatar,avatar_thumb,sex,signature,coin,birthday,user_status,login_type,last_login_time,type,gradeid';

	/* 会员登录 */   	
    public function userLogin($where) {

		$info=\PhalApi\DI()->notorm->users
				->select($this->fields)
                ->where($where)
				->fetchOne();
                
        return $info;
    }	
	
	/* 会员注册 */
    public function userReg($data=[]) {
        
        $nowtime=time();
        $user_pass='edu'.$nowtime;
        $user_pass=\App\setPass($user_pass);

        $avatar='/default.png';
        $avatar_thumb='/default_thumb.png';

        $default=array(
            'user_pass' =>$user_pass,
            'signature' =>'',
            'avatar' =>$avatar,
            'avatar_thumb' =>$avatar_thumb,
            'last_login_ip' =>\PhalApi\Tool::getClientIp(),
            'create_time' => $nowtime,
            'user_status' => 1,
        );
        
        if(isset($data['user_pass'])){
            $data['user_pass']=\App\setPass($data['user_pass']);
        }
        $insert=array_merge($default,$data);
        
            
		$rs=\PhalApi\DI()->notorm->users->insert($insert);
        
        $id=$rs['id'];
        
        $info=\PhalApi\DI()->notorm->users
				->select($this->fields)
				->where('id=?',$id)
				->fetchOne();
                
		return $info;
    }

	/* 忘记密码 */
    public function forget($user_login,$data=[]) {
        
        if(!$data){
            return !1;
        }
        
        if(isset($data['user_pass'])){
            $data['user_pass']=\App\setPass($data['user_pass']);
        }

        
        $info=\PhalApi\DI()->notorm->users
				->where('user_login=?',$user_login)
				->update( $data );
                
		return $info;
    }
		

	/* 更新token 登陆信息 */
    public function updateToken($uid,$token,$data=array()) {
        
        $nowtime=time();
		$expiretime=$nowtime+60*60*24*150;

		\PhalApi\DI()->notorm->users
			->where('id=?',$uid)
			->update(array('last_login_time' => $nowtime, "last_login_ip"=>\PhalApi\Tool::getClientIp() ));

		$token_info=array(
			'user_id'=>$uid,
			'token'=>$token,
			'expire_time'=>$expiretime,
			'create_time'=>$nowtime,
		);
        $isexist=\PhalApi\DI()->notorm->users_token
			->where('user_id=?',$uid)
			->update( $token_info );
        if(!$isexist){
            \PhalApi\DI()->notorm->users_token
                ->insert( $token_info );
        }
		
		\App\hMSet("token_".$uid,$token_info);		
        
		return 1;
    }
    
    /* 更新极光ID */
    public function upUserPush($uid,$pushid){
        if(!$pushid){
            $pushid='';
        }
        
        $isexist=\PhalApi\DI()->notorm->users_pushid
                    ->select('*')
                    ->where('uid=?',$uid)
                    ->fetchOne();
        if(!$isexist){
            \PhalApi\DI()->notorm->users_pushid->insert(array('uid'=>$uid,'pushid'=>$pushid));
        }else if($isexist['pushid']!=$pushid){
            \PhalApi\DI()->notorm->users_pushid->where('uid=?',$uid)->update(array('pushid'=>$pushid));
        }
        return 1;
    }

}
