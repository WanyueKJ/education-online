<?php
namespace App\Domain;

use App\Model\Message as Model_Message;

class Message {

    /* 信息列表 */
	public function getList($where=[],$nums=20) {


        $model = new Model_Message();
        $list= $model->getList($where,$nums);
        foreach($list as $k=>$v){
            $v['addtime']=date('Y-m-d H:i:s',$v['addtime']);
            $list[$k]=$v;
        }

		return $list;
	}
	
	
}
