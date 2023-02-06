<?php
namespace App\Domain;

use App\Model\Comment as Model_Comment;
use App\Model\Course as Model_Course;

class Comment {

    /* 发布评论 */
	public function add($data) {
        
        $rs = array('code' => 0, 'msg' => \PhalApi\T('评价成功'), 'info' => array());
        
        $Model_Course = new Model_Course();
        
        /* 课程信息 */
        $where=[
            'id'=>$data['courseid'],
        ];
        $info=$Model_Course->getDetail($where);
        if(!$info){
            $rs['code'] = 1004;
            $rs['msg'] = \PhalApi\T('信息错误');
            return $rs;
        }
        $data['sort']=$info['sort'];
        
        /* 评论时的学习进度 */
        $where2=[
            'uid'=>$data['uid'],
            'courseid'=>$data['courseid'],
        ];
        $payinfo=$Model_Course->getBuy($where2);
        if($payinfo){
            $data['lessons']=$payinfo['lessons'];
        }
        
        $model = new Model_Comment();
        $res= $model->add($data);
        
        if($res){
            /* 更新星级、评论数 */
            $where=[
                'id'=>$data['courseid'],
            ];
            
            $Model_Course->upStar($where,$data['star'],1);
        }
        
        if(!$res){
            $rs['code'] = 1002;
			$rs['msg'] = \PhalApi\T('提交失败，请重试');
			return $rs;
        }

		return $rs;
	}
    
    
    /* 评论列表 */
	public function getList($p=1,$where=[],$order='id desc') {
        
        $rs=[
            'star'=>'0',
            'list'=>[],
        ];
        
        $Model_Course = new Model_Course();
        /* 课程信息 */
        $where2=[
            'id'=>$where['courseid'],
        ];
        $info=$Model_Course->getDetail($where2);
        if(!$info){
            return $rs;
        }
        
        $rs['star']=$info['star'];
        
        $model = new Model_Comment();
        $list= $model->getList($where,$order,$p);
        foreach($list as $k=>$v){
            $userinfo=\App\getUserInfo($v['uid']);
            
            $v['user_nickname']=$userinfo['user_nickname'];
            $v['avatar']=$userinfo['avatar'];
            $v['add_time']=date('Y-m-d',$v['addtime']);
            $des="";
            if((int)$v['sort']===1){
                $des="学习{$v['lessons']}个课时评价";
            }
            $v['des']=$des;
            unset($v['lessons']);
            
            $list[$k]=$v;
        }
        
        $rs['list']=$list;

		return $rs;
	}
	
	
}
