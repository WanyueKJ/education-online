<?php
namespace App\Domain;

use App\Model\Home as Model_Home;

class Home {

    /* 轮播 */
	public function getSilide($id='7') {

        $key='getSlide_'.$id;
		$list=\App\getcaches($key);
		if(!$list){
            $model = new Model_Home();
			$list= $model->getSilide($id);
			\App\setcaches($key,$list);
			
		}
        foreach($list as $k=>$v){
            $v['image']=\App\get_upload_path($v['image']);
            $list[$k]=$v;
        }

		return $list;
	}
	
	
}
