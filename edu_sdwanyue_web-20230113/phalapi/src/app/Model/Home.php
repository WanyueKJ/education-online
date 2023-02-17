<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Home extends NotORM {
	/* 轮播 */
	public function getSilide($id) {
		
		$list=\PhalApi\DI()->notorm->slide_item
				->select('id,title,image,url')
                ->where('status=1 and slide_id=?',$id)
				->order('list_order asc')
				->fetchAll();

		return $list;
	}
	
	
}
