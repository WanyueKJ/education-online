<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/02 08:22
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\Live as Domain_Live;
/**
 * 直播
 */

class Live extends Api {

	public function getRules() {
        return array(
            'checkLive' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
            ),
            
            'enter' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
            ),
            
            'getUserLists' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'desc' => '直播标识'),
                'p' => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),
            
            'getNums' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'desc' => '直播标识'),
            ),
            
            'setChat' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'desc' => '类型，0文字1语音'),
                'content' => array('name' => 'content', 'type' => 'string', 'desc' => '内容'),
                'url' => array('name' => 'url', 'type' => 'string', 'desc' => '语音链接'),
                'length' => array('name' => 'length', 'type' => 'int', 'desc' => '语音时长 秒'),
                'status' => array('name' => 'status', 'type' => 'int', 'desc' => '形式，0普通1提问2回答'),
                'user_type' => array('name' => 'user_type', 'type' => 'int', 'desc' => '发布人身份，0学生1讲师2辅导'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名，uid liveuid courseid lessonid type content url user_type'),
            ),
            
            'getChat' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '讲师ID'),
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'lessonid' => array('name' => 'lessonid', 'type' => 'int', 'desc' => '课时ID'),
                'lastid' => array('name' => 'lastid', 'type' => 'int', 'desc' => '最后一条的ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'desc' => '类型，0全部1讲师2问答'),
            ),
            
            'upLive' => array(
                'stream' => array('name' => 'stream', 'type' => 'string', 'desc' => '直播标识'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名'),
            ),
        );
	}
	
    /**
     * 检测是否直播
     * @desc 用于检测是否直播
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islive  直播状态 
     * @return string msg 提示信息
     */
	public function checkLive() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $liveuid = \App\checkNull($this->liveuid);
        $courseid = \App\checkNull($this->courseid);
        $lessonid = \App\checkNull($this->lessonid);
       
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        $domain = new Domain_Live();
		$res = $domain->checkLive($uid,$liveuid,$courseid,$lessonid);
		
        return $res;
	}

    /**
     * 进入直播间
     * @desc 用于进入直播间
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].chatserver  聊天地址 
     * @return string info[0].pull  文件链接 
     * @return string info[0].stream  直播标识 liveuid_courseid_lessonid
     * @return string info[0].livetype 类型1语音ppt2语音视频3语音音频4白板5视频直播
     * @return string info[0].islive 直播状态，0未直播1直播中2直播结束
     * @return string info[0].nums 人数
     * @return string info[0].uuid netless白板房间标识符uuid 需解密
     * @return string info[0].roomtoken  netless白板房间token 需解密
     * @return string info[0].sound_appid 声网appid 需解密
     * @return string info[0].shutup_me 自己是否被禁言 0否1是  当 shutup_me=0 && shutup_room=0 时才能发言
     * @return string info[0].shutup_room 直播间是否禁言 0否1是 
     * @return string info[0].livemode 直播模式 0摄像头1ppt2共享屏幕 仅当 livetype=5时处理
     * @return string info[0].pptindex ppt当前展示序号 从0开始 
     * @return string info[0].intr 听课指南
     * @return string info[0].user_type 用户身份，0学生1讲师2辅导
     * @return array  info[0].ppts ppt图片列表
     * @return string info[0].ppts[].id ppt图片ID
     * @return string info[0].ppts[].thumb 图片链接
     * @return string msg 提示信息
     */
	public function enter() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $liveuid = \App\checkNull($this->liveuid);
        $courseid = \App\checkNull($this->courseid);
        $lessonid = \App\checkNull($this->lessonid);
        
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}

        $domain = new Domain_Live();
		$res = $domain->enter($uid,$token,$liveuid,$courseid,$lessonid);

		if($res['code']==0){
            $res['info'][0]['nums']=$this->getUserNums($liveuid,$res['info'][0]['stream']) ?? 0;
        }
        return $res;
	}

	/**
	 * 用户列表 
	 * @desc 用于直播间获取用户列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return array info[0].list 用户列表
	 * @return string info[0].list[].islink 是否连麦 0否1是
	 * @return string info[0].list[].ismic 是否开麦 0否1是
	 * @return string info[0].list[].iscamera 是否开启摄像头 0否1是
	 * @return string info[0].nums 房间人数
	 * @return string msg 提示信息
	 */
	public function getUserLists() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $liveuid = \App\checkNull($this->liveuid);
        $stream = \App\checkNull($this->stream);
        $p = \App\checkNull($this->p);

		/* 用户列表 */ 
		$list=$this->getUserList($liveuid,$stream,$p);
		$nums=$this->getUserNums($liveuid,$stream);
        
        $info['list']=$list;
        $info['nums']=$nums;
        
		$rs['info'][0]=$info;

        return $rs;
	}

	/**
	 * 用户人数 
	 * @desc 用于获取用户人数
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].nums 房间人数
	 * @return string msg 提示信息
	 */
	public function getNums() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $liveuid = \App\checkNull($this->liveuid);
        $stream = \App\checkNull($this->stream);
        
		$nums=$this->getUserNums($liveuid,$stream);

        $info['nums']=$nums;
        
		$rs['info'][0]=$info;

        return $rs;
	}

    protected function getUserList($liveuid,$stream,$p=1) {
		/* 用户列表 */ 
        if($p<1){
            $p=1;
        }
		$pnum=20;
		$start=($p-1)*$pnum;
        
        $key2='livemodel_m_'.$stream;
        $key3='livemodel_s_'.$stream;

        $list=array();

        $uidlist=\App\zRevRange('user_'.$stream,$start,$pnum,true);

        foreach($uidlist as $k=>$v){
            $userinfo=\App\getUserInfo($k);
            $ismic='1';
            $iscamera='1';
            $islink='0';


            if($v==1){
                $islink='1';
            }

            if($islink){
                if(\App\hGet($key2,$k)){
                    $ismic='0';
                }

                if(\App\hGet($key3,$k)){
                    $iscamera='0';
                }
            }

            if($islink==0){
                $ismic='0';
                $iscamera='0';
            }

            $userinfo['ismic']=$ismic;
            $userinfo['iscamera']=$iscamera;
            $userinfo['islink']=$islink;
            $list[]=$userinfo;
        }

        
        if(!$list){
            $list=array();
        }

        return $list; 
    }
    
    protected function getUserNums($liveuid,$stream) {    
		$nums=\App\zCard('user_'.$stream);
        if(!$nums){
            $nums=0;
        }

        return (string)$nums; 
    }
    
    /**
     * 记录聊天信息
     * @desc 用于记录聊天信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].chatid 记录ID 
     * @return string info[0].content 内容 
     * @return string info[0].url 音频链接 
     * @return string info[0].length 时长 
     * @return string msg 提示信息
     */
	public function setChat() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $liveuid = \App\checkNull($this->liveuid);
        $courseid = \App\checkNull($this->courseid);
        $lessonid = \App\checkNull($this->lessonid);
        $type = \App\checkNull($this->type);
        $content = urldecode($this->content);
        $url = \App\checkNull($this->url);
        $length = \App\checkNull($this->length);
        $sign = \App\checkNull($this->sign);
        $status = \App\checkNull($this->status);
        $user_type = \App\checkNull($this->user_type);
        
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        
        $checkdata=array(
            'uid'=>$uid,
            'liveuid'=>$liveuid,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
            'type'=>$type,
            'content'=>$content,
            'user_type'=>$user_type,
            'url'=>$url,
        );
        // file_put_contents('./zhifu.txt',date('Y-m-d H:i:s').' 提交参数信息 checkdata:'.json_encode($checkdata)."\r\n",FILE_APPEND);
        
        $issign=\App\checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
			$rs['msg']=\PhalApi\T('签名错误');
			return $rs;
        }
        
        if($type==0 && $content==''){
            $rs['code'] = 1002;
			$rs['msg'] = \PhalApi\T('请输入内容');
			return $rs;
        }
        
        if($type==1 && ($url=='' || $length<1) ){
            $rs['code'] = 1003;
			$rs['msg'] = \PhalApi\T('请录制语音');
			return $rs;
        }
        
        if($content!=strip_tags($content)){
            $rs['code'] = 1004;
			$rs['msg'] = \PhalApi\T('内容包含非法字符');
			return $rs;
        }
        
        if(!$status){
            $status=0;
        }
        $data=[
            'uid'=>$uid,
            'liveuid'=>$liveuid,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
            'type'=>$type,
            'content'=>$content,
            'url'=>$url,
            'length'=>$length,
            'status'=>$status,
            'user_type'=>$user_type,
            'addtime'=>time(),
        ];
        $domain = new Domain_Live();
		$res = $domain->setChat($data);
        if(!$res){
            $rs['code'] = 1004;
			$rs['msg'] = \PhalApi\T('提交失败，请重试');
			return $rs;
        }
        
        $info=[
            'chatid'=>$res['id'],
            'type'=>$type,
            'content'=>$content,
            'url'=>\App\get_upload_path($url),
            'length'=>$length,
            'status'=>$status,
        ];
        
        // file_put_contents('./zhifu.txt',date('Y-m-d H:i:s').' 提交参数信息 info:'.json_encode($info)."\r\n",FILE_APPEND);
        
        $rs['info'][0]=$info;
		
        return $rs;
	}

    /**
     * 聊天记录
     * @desc 用于获取聊天记录
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].id 
     * @return string info[].user_nickname 昵称
     * @return string info[].avatar 头像
     * @return string info[].type 类型，0文字1语音
     * @return string info[].content 文字内容
     * @return string info[].url 音频链接
     * @return string info[].length 音频时长 秒
     * @return string info[].add_time 格式化时间
     * @return string info[].user_type 发布人身份，0学生1讲师2辅导
     * @return string msg 提示信息
     */
	public function getChat() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $liveuid = \App\checkNull($this->liveuid);
        $courseid = \App\checkNull($this->courseid);
        $lessonid = \App\checkNull($this->lessonid);
        $lastid = \App\checkNull($this->lastid);
        $type = \App\checkNull($this->type);
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        if($liveuid<1 || $courseid<1){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('信息错误');
			return $rs;
        }
        
        $domain = new Domain_Live();
		$list = $domain->getChat($uid,$liveuid,$courseid,$lessonid,$type,$lastid);
        
        $rs['info']=$list;
		
        return $rs;
	}
    
    /**
     * 主播离开房间
     * @desc 用于处理主播离开直播间的相关操作
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
	public function upLive() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $stream = \App\checkNull($this->stream);
        $sign = \App\checkNull($this->sign);
        
        if($stream=='' || $sign==''){
            $rs['code']=1000;
			$rs['msg']=\PhalApi\T('信息错误');
			return $rs;
        }
        
        $checkdata=array(
            'uid'=>$uid,
            'stream'=>$stream,
        );
        
        $issign=\App\checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
			$rs['msg']=\PhalApi\T('签名错误');
			return $rs;
        }
        
        $domain = new Domain_Live();
		$res = $domain->upLive($stream);
		
        return $res;
	}

}
