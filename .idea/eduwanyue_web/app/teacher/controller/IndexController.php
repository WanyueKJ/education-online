<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/21 11:22
// +----------------------------------------------------------------------
namespace app\teacher\controller;

use app\admin\model\CourseModel;
use cmf\controller\TeacherBaseController;
use think\Db;
/**
 * 首页
 */
class IndexController extends TeacherBaseController {
    
	public function index() {
        $cur='index';
        $this->assign('cur',$cur);
        
        $uid=session('teacher.id');
        if (!$uid) {
            $uid = $_SESSION['teacher']['id'] ?? 0;
        }
        
        $live_nums=CourseModel::where([['uid|tutoruid','=',$uid],['sort','>=',2]])->count();
        
        $this->assign('live_nums',$live_nums);
        
        $list=Db::name('portal_category_post c')
                ->leftJoin('portal_post p','c.post_id=p.id')
                ->field('p.id,p.post_title')
                ->where([['c.status','=',1],['c.category_id','=',1],['p.post_status','=',1]])
                ->order('id desc')
                ->limit(0,10)
                ->select();
                
        $this->assign('list',$list);
        
        return $this->fetch();
    }
}


