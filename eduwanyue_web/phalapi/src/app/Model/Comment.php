<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Comment extends NotORM {
	/* 新增 */
	public function add($data) {
		
		$list=\PhalApi\DI()->notorm->course_com
				->insert($data);

		return $list;
	}
	
	/* 评论列表 */
    public function getList($where,$order,$p) {
		
        if($p<1){
            $p=1;
        }
        $nums=40;
        $start=($p-1) * $nums;

        $list=\PhalApi\DI()->notorm->course_com
				->select('id,uid,sort,content,star,lessons,addtime')
                ->where($where)
                ->order($order)
                ->limit($start,$nums)
				->fetchAll();
        
        return $list;
	}
}
