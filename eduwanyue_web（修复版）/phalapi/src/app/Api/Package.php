<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Package as Domain_Package;
/**
 * 套餐
 */

class Package extends Api {

	public function getRules() {
        return array(
            'getList' => array(
                'gradeid' => array('name' => 'gradeid', 'type' => 'int', 'desc' => '学级分类ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'desc' => '页码'),
            ),
            
            'getInfo' => array(
                'packageid' => array('name' => 'packageid', 'type' => 'int', 'desc' => '套餐ID'),
            ),
            
            'getListByCourse' => array(
                'courseid' => array('name' => 'courseid', 'type' => 'int', 'desc' => '课程ID'),
            ),
            
        );
	}
    /**
     * 套餐列表
     * @desc 用于获取套餐列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].id  
     * @return string info[].name 标题 
     * @return string info[].thumb 封面
     * @return string info[].price 价格
     * @return string info[].nums 课程数
     * @return array info[].teacher 讲师列表
     * @return string info[].teacher[].id 讲师ID
     * @return string info[].teacher[].user_nickname 讲师昵称
     * @return string info[].teacher[].avatar 头像
     * @return string msg 提示信息
     */
	public function getList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $gradeid = \App\checkNull($this->gradeid);
        $p = \App\checkNull($this->p);
        

        $domain = new Domain_Package();
        $where=[
            'gradeid'=>$gradeid,
        ];
		$list = $domain->getList($p,$where);
        
        $rs['info']=$list;
		
        return $rs;
	}

    /**
     * 套餐详情
     * @desc 用于获取套餐详情
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].id  
     * @return string info[].name 标题 
     * @return string info[].thumb 封面
     * @return string info[].price 价格
     * @return string info[].nums 课程数
     * @return string info[].isbuy 套餐是否购买0否1是
     * @return string info[].ismaterial 套餐是否含有教材
     * @return string info[].views 学习人数
     * @return string info[].iscart 是否加入购物车0否1是
     * @return array info[].courses 课程列表
     * @return string info[].courses[].isbuy 课程是否购买0否1是
     * @return string info[].courses[].ismaterial 是否含有教材0否1是
     * @return string msg 提示信息
     */
	public function getInfo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $packageid = \App\checkNull($this->packageid);
        
        // if($uid>0){
            $checkToken=\App\checkToken($uid,$token);
            if($checkToken==700){
                $rs['code'] = $checkToken;
                $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
                return $rs;
            }
        // }
        

        $domain = new Domain_Package();
		$res = $domain->getInfo($uid,$packageid);
		
        return $res;
	}
    
    /**
     * 含某课程的套餐列表(联报课程)
     * @desc 用于获取含有某课程的套餐列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].id  
     * @return string info[].name 标题 
     * @return string info[].thumb 封面
     * @return string info[].price 价格
     * @return string info[].isbuy 套餐是否购买0否1是
     * @return string info[].ismaterial 套餐是否含有教材
     * @return array info[].courses 课程列表
     * @return string info[].courses[].isbuy 课程是否购买0否1是
     * @return string info[].courses[].ismaterial 是否含有教材0否1是
     * @return string msg 提示信息
     */
	public function getListByCourse() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid = \App\checkNull($this->uid);
        $token = \App\checkNull($this->token);
        $courseid = \App\checkNull($this->courseid);
        
        $checkToken=\App\checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
            return $rs;
        }
        
        if($courseid<1){
            $rs['code'] = 1002;
            $rs['msg'] = \PhalApi\T('信息错误');
            return $rs;
        }
        
        $domain = new Domain_Package();
        $where=[
            'courseids like ?'=>'%['.$courseid.']%',
        ];
		$list = $domain->getListByCourse($uid,$where);
        
        $rs['info']=$list;
		
        return $rs;
	}
    
    
}
