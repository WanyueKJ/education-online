<?php
namespace App\Domain;

use App\Model\Feedback as Model_Feedback;

class Feedback {

    /* 轮播 */
	public function add($data) {


        $model = new Model_Feedback();
        $list= $model->add($data);

		return $list;
	}
	
	
}
