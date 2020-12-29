<?php

/* 文章管理 */
namespace app\home\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalPostModel;
use think\Db;

class ArticleController extends HomebaseController{


    public function detail() {
        
        $id      = $this->request->param('id', 0, 'intval');
        $list=Db::name('portal_category_post c')
                ->leftJoin('portal_post p','c.post_id=p.id')
                ->field('p.id,p.post_title')
                ->where([['c.status','=',1],['c.category_id','=',1],['p.post_status','=',1]])
                ->order('id desc')
                ->select()
                ->toArray();
        
        foreach($list as $k=>$v){
            if($id==0 && $k==0){
                $id=$v['id'];
            }
            
            $ison=0;
            if($v['id']==$id){
                $ison=1;
            }
            $v['ison']=$ison;
            $list[$k]=$v;
        }
                
        $this->assign('list',$list);
    
        $portalPostModel = new PortalPostModel();
        
        
        $article        = $portalPostModel->where(['id'=>$id])->where('post_status','=',1)->find();
        
        if(!$article){
            $article=[
                'post_title'=>'',
                'post_content'=>'',
            ];
        }
        
        $this->assign('page', $article);

        return $this->fetch();
	}	
	

}