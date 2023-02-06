<?php
namespace App\Domain;

use App\Model\Package as Model_Package;
use App\Domain\Course as Domain_Course;
use App\Domain\Cart as Domain_Cart;

class Package {
    
    /* 处理套餐课程ID */
    protected function handelCourseids($courseid_s){
        $courseid_a=[];
        $courseids_a=explode(',',$courseid_s);
        foreach($courseids_a as $k=>$v){
            $courseid_a[]=preg_replace('/\[|\]/','',$v);
        }
        
        return $courseid_a;
    }
    /* 更具课程ID 获取 课程信息 */
    protected function getCourseids($courseid_s){
        $course=[];
        
        $courseid_a=$this->handelCourseids($courseid_s);
        
        if(!$courseid_a){
            return $course;
        }
        
        $courseid_s=implode(',',$courseid_a);
        
        $where="id in ($courseid_s)";
        
        $Domain_Course = new Domain_Course();
        
        $course=$Domain_Course->getList(1,$where);
        return $course;
    }
    /* 列表 */
	public function getList($p=1,$where='',$order='list_order asc,id desc',$nums=0){
        
        $nowtime=time();
        
        $model = new Model_Package();
        
        $list=$model->getList($where,$order,$p,$nums);
        
        foreach($list as $k=>$v){
            //$v=$this->handelInfo($v);
            $v['thumb']=\App\get_upload_path($v['thumb']);
            $ismaterial='0';
            $teacher=[];
            $courses=$this->getCourseids($v['courseids']);
            foreach($courses as $k1=>$v1){
                $ishas=0;
                foreach($teacher as $k2=>$v2){
                    if($v2['id']==$v1['uid']){
                        $ishas=1;
                        break;
                    }
                }
                if($ishas==0){
                    $t_a=[
                        'id'=>$v1['uid'],
                        'user_nickname'=>$v1['user_nickname'],
                        'avatar'=>$v1['avatar'],
                    ];
                    
                    $teacher[]=$t_a;
                }
                
                if($v1['ismaterial']==1){
                    $ismaterial='1';
                }
                
            }
            
            $v['teacher']=$teacher;
            $v['ismaterial']=$ismaterial;
            
            unset($v['courseids']);
            
            $list[$k]=$v;
        }
        
        return $list;
    }


    /* 详情 */
	public function getInfo($uid,$packageid){
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $nowtime=time();
        
        $model = new Model_Package();
        $where=[
            'id'=>$packageid,
        ];
        $info=$model->getInfo($where);
        
        if(!$info){
            $rs['code'] = 1001;
			$rs['msg'] = \PhalApi\T('套餐不存在');
			return $rs;
        }

        $info['thumb']=\App\get_upload_path($info['thumb']);
        
        $ismaterial='0';
        $views='0';
        $courses=$this->getCourseids($info['courseids']);
        
        $Domain_Course = new Domain_Course();
        
        foreach($courses as $k=>$v){
            $isbuy='0';
            if($uid>0){
                $where2=[
                    'uid'=>$uid,
                    'courseid'=>$v['id'],
                ];
                $buyinfo=$Domain_Course->getBuy($where2);
                if($buyinfo && $buyinfo['status']==1){
                    $isbuy='1';
                }
            }
            
            $v['isbuy']=$isbuy;
            
            if($v['ismaterial']==1){
                $ismaterial='1';
            }
            
            $views+=$v['views'];
            
            $courses[$k]=$v;
        }
            
        $info['courses']=$courses;
        $info['ismaterial']=$ismaterial;
        $info['views']=(string)$views;
        
        $isbuy='0';
        if($uid>0){
            $where2=[
                'uid'=>$uid,
                'packageid'=>$packageid,
            ];
            $buyinfo=$this->getBuy($where2);
            if($buyinfo && $buyinfo['status']==1){
                $isbuy='1';
            }
        }
        
        $info['isbuy']=$isbuy;
        
        /* 是否购物车 */
        $iscart='0';
        $Domain_Cart = new Domain_Cart();
        $where4=[
            'uid'=>$uid,
            'type'=>1,
            'typeid'=>$packageid,
        ];
        $isexist=$Domain_Cart->getNums($where4);
        if($isexist){
            $iscart='1';
        }
        $info['iscart']=$iscart;
        
        unset($info['courseids']);
        unset($info['list_order']);
        unset($info['addtime']);
        //unset($info['content']);
        
        $rs['info'][]=$info;
        
        return $rs;
    }
    
    /* 未处理详情 */
	public function getInfod($where){
        if(!$where){
            return 0;
        }
        
        $model = new Model_Package();
        $info=$model->getInfo($where);
        
        if($info){
            
            $ismaterial='0';
            $courseid_a=$this->handelCourseids($info['courseids']);
        
            if($courseid_a){
                $courseid_s=implode(',',$courseid_a);
        
                $where="id in ($courseid_s) and ismaterial=1";
                
                $Domain_Course = new Domain_Course();
                
                $course=$Domain_Course->getList(1,$where);
                
                if($course){
                    $ismaterial='1';
                }
            }
            
            
            $info['ismaterial']=$ismaterial;
        }
        
        return $info;
    }
    
    /* 获取购买记录 */
    public function getBuy($where){
		
        if(!$where){
            return 0;
        }
        
        $model = new Model_Package();
        $info=$model->getBuy($where);

        
        return $info;
    }
    
	/* 列表 */
	public function getListByCourse($uid,$where=''){
        $p=1;
        $order='list_order asc,id desc';
        $nums=100;
        $nowtime=time();
        
        $model = new Model_Package();
        
        $list=$model->getList($where,$order,$p,$nums);
        
        foreach($list as $k=>$v){
            //$v=$this->handelInfo($v);
            $v['thumb']=\App\get_upload_path($v['thumb']);
            
            $ismaterial='0';
            $courses=$this->getCourseids($v['courseids']);
            
            $Domain_Course = new Domain_Course();
            
            foreach($courses as $k1=>$v1){
                $isbuy='0';
                if($uid>0){
                    $where2=[
                        'uid'=>$uid,
                        'courseid'=>$v1['id'],
                    ];
                    $buyinfo=$Domain_Course->getBuy($where2);
                    if($buyinfo && $buyinfo['status']==1){
                        $isbuy='1';
                    }
                }
                
                $v1['isbuy']=$isbuy;
                
                if($v1['ismaterial']==1){
                    $ismaterial='1';
                }
                
                $courses[$k1]=$v1;
            }
                
            $v['courses']=$courses;
            $v['ismaterial']=$ismaterial;
            
            unset($v['courseids']);
            
            $isbuy='0';
            $where2=[
                'uid'=>$uid,
                'packageid'=>$v['id'],
            ];
            $buyinfo=$this->getBuy($where2);
            if($buyinfo && $buyinfo['status']==1){
                $isbuy='1';
            }

            
            $v['isbuy']=$isbuy;
        
            
            $list[$k]=$v;
        }
        
        return $list;
    }
	
}
