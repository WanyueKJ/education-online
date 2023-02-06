<?php


namespace app\student\controller;

use cmf\controller\StudentBaseController;
//use app\student\model\NewsModel;
use think\Db;
/**
 * 年级新闻资讯
 * Class PressController
 * @package app\student\controller
 */
class PressController extends StudentBaseController
{
    public function index()
    {
        $NewsModel = Db::name('news');
        $newsList = $NewsModel
            ->where([
                ['gradeid', '=', session('student.gradeid') ?? 0],
            ])
            ->paginate();

        foreach ($newsList as $key => $value) {
            $newsList[$key]['thumb'] = get_upload_path($value['thumb']);
            $newsList[$key]['add_time'] = date('Y-m-d H:i:s', $value['addtime']);
        }

        $this->assign([
            'newsList' => $newsList,
			'navid'      => 19,
        ]);

        return $this->fetch();
    }

    public function read()
    {
        $id = input('id/d', 0);
        if ($id <= 0) {
            return $this->error('参数错误');
        }

        $NewsModel =  Db::name('news');
        $info = $NewsModel->where([
            ['id', '=', $id]
        ])->find();
        $info['thumb'] = get_upload_path($info['thumb']);
        $info['add_time'] = date('Y-m-d H:i:s', $info['addtime']);

        $this->assign([
            'info' => $info,
			'navid'      => 19,
        ]);

        return $this->fetch();
    }
}
