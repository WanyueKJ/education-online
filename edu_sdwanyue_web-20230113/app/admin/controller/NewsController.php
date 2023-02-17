<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2021/03/06 09:08
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\CourseModel;
use app\admin\model\UsersModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * 新闻列表
 * @package app\admin\controller
 */
class NewsController extends AdminBaseController
{


    /*
     * 新闻列表
     * @return mixed
     */
    public function index()
    {
        $data = $this->request->param();
        $map  = [];

        $start_time = $data['start_time'] ?? '';

        if ($start_time != "") {
            $map[] = ['addtime', '>=', strtotime($start_time)];
        }

        $keyword = $data['keyword'] ?? '';
        if ($keyword != '') {
            $map[] = ['name', 'like', '%' . $keyword . '%'];
        }

        $list = db('news')->where($map)->order("id desc")->paginate(20, false, ['query' => input()]);

        $list->each(function ($v, $k) {
            $v['thumb']    = get_upload_path($v['thumb']);
            return $v;
        });

        $list->appends($data);
        // 获取分页显示
        $page = $list->render();

        $this->assign([
            'list'       => $list,
            'page'       => $page,
        ]);

        // 渲染模板输出
        return $this->fetch('index');
    }


    /**
     * 新闻添加
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('add');
    }


    /*
     * 新闻添加提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            $name = $data['name'];
            if ($name == '') {
                $this->error('请填写名称');
            }

            $thumb = $data['thumb'];
            if ($thumb == '') {
                $this->error('请上传封面');
            }

            $nowtime = time();

            $content = $data['content'] ?? '';
            if ($content == '') {
                $this->error('请编辑内容');
            }

            $data['addtime'] = $nowtime;

            $res = db('news')->insert($data);
            if (!$res) {
                $this->error("添加失败！");
            }

            $this->success("添加成功！", url("news/index"));
        }
    }


    /*
     * 新闻编辑
     * @return mixed
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        $data = db('news')->where('id', '=', $id)->find();
        if (!$data) {
            $this->error("信息错误");
        }

        $this->assign('data', $data);

        return $this->fetch('edit');
    }

    /*
     * 新闻编辑提交
     */
    public function editPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            $name = $data['name'];
            if ($name == '') {
                $this->error('请填写名称');
            }

            $thumb = $data['thumb'];
            if ($thumb == '') {
                $this->error('请上传封面');
            }

            $nowtime     = time();

            $content = $data['content'] ?? '';
            if ($content == '') {
                $this->error('请编辑内容');
            }

            $rs = DB::name('news')->update($data);

            if ($rs === false) {
                $this->error("保存失败！");
            }

            $this->success("保存成功！");
        }
    }

    /**
     * 排序
     */
    public function listOrder()
    {
        $model = DB::name('news');
        parent::listOrders($model);
        $this->success("排序更新成功！");
    }

    /*
     * 新闻删除
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $rs = db('news')->where('id', $id)->delete();
        if (!$rs) {
            $this->error("删除失败！");
        }

        $this->success("删除成功！");
    }


}