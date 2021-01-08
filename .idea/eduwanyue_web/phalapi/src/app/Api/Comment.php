<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Comment as Domain_Comment;

header("Access-Control-Allow-Origin: *");

/**
 * 课程评论
 */

class Comment extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'star' => array('name' => 'star', 'type' => 'int', 'desc' => '星级'),
                'content' => array('name' => 'content', 'type' => 'string', 'desc' => '内容'),
            ),
            'getList' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),
        );
	}

    /**
     * 提交评论
     * @desc 用于提交评论
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
	public function add() {
        $rs = array('code' => 0, 'msg' => \PhalApi\T('评价成功'), 'info' => array());

        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        $star = \App\checkNull($this->star);
        $content = \App\checkNull($this->content);
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        if($courseid<1 || $star<0 || $star>5 ){
            $rs['code'] = 1001;
            $rs['msg'] = \PhalApi\T('信息错误');
            return $rs;
        }
        if($star==0 && $content==''){
            $rs['code'] = 1002;
            $rs['msg'] = \PhalApi\T('请选择星级或填写评论内容');
            return $rs;
        }
        
        if(mb_strlen($content)>100){
            $rs['code'] = 1003;
            $rs['msg'] = \PhalApi\T('评论内容不能多于100字');
            return $rs;
        }
        $data=[
            'uid'=>$uid,
            'courseid'=>$courseid,
            'star'=>$star,
            'content'=>$content,
            'addtime'=>time(),
        ];
        
        $domain = new Domain_Comment();
		$res = $domain->add($data);
        
		
        return $res;
	}
    
    
    /**
     * 评论列表
     * @desc 用于获取评论列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].star 星级
     * @return array info[0].list 评论列表
     * @return string info[0].list[].user_nickname 用户昵称
     * @return string info[0].list[].avatar 用户头像
     * @return string info[0].list[].content 评论内容
     * @return string info[0].list[].star 星级
     * @return string info[0].list[].add_time 时间日期
     * @return string info[0].list[].des 课时描述 仅当sort=1时显示
     * @return string msg 提示信息
     */
	public function getList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        $p = \App\checkNull($this->p);
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
        $where=[
            'courseid'=>$courseid
        ];
        $domain = new Domain_Comment();
		$list = $domain->getList($p,$where);
        
        $rs['info'][0]=$list;
		
        return $rs;
	}    

}
