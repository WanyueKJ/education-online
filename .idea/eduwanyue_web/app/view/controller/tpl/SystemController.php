<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 古董诗人 < 727324809@qq.com>
// +----------------------------------------------------------------------
namespace app\view\controller\tpl;

use cmf\controller\AdminBaseController;

class SystemController extends AdminBaseController {

	public function about() {
		return $this->fetch();
	}
	public function get() {
		return $this->fetch();
	}
	public function theme() {
		return $this->fetch();
	}
	public function more() {
		return $this->fetch();
	}

}
