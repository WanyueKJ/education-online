<?php /*a:2:{s:76:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/admin/news/index.html";i:1645779015;s:73:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/public/header.html";i:1645779015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/icon.css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/",
            WEB_ROOT: "/",
            JS_ROOT: "static/js/",
            APP: '<?php echo app('request')->module(); ?>'/*当前应用名*/
        };
    </script>
    <script src="/themes/admin_htcyltd/public/assets/js/jquery-1.10.2.min.js"></script>
    <script src="/themes/admin_htcyltd/public/assets/js/bootstrap.min.js"></script>
    <script src="/static/js/jquery.validate/jquery.validate.js"></script>
    <script src="/static/js/ajaxForm.js"></script>
    <script src="/themes/admin_htcyltd/public/layuiadmin/layui/layui.js"></script>
    <script src="/themes/admin_htcyltd/public/assets/js/animation.js"></script>
    
    <?php if(APP_DEBUG): ?>
        <style>
            #think_page_trace_open {
                z-index: 9999;
            }
        </style>
    <?php endif; ?>
<link rel="stylesheet" href="/themes/admin_htcyltd/public/assets/css/animation.css">

<style>
    .table img{
        width:50px;
    }
    .layui-table[lay-size=lg] td, .layui-table[lay-size=lg] th{
        padding: 10px 10px;
    }
</style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">

        <div class="layui-page-header">
            <div class="layui-card">
                <div class="layui-page-header-content">
                    <div class="layui-card-body">
                        <div class="layui-ui-page-header-title" >
                            <span class="layui-badge-rim page-content">新闻列表</span>
                        </div>
                    </div>
                    <div class="layui-tab layui-tab-brief">
                        <ul class="layui-tab-title layui-nav" id="tabHeader">
                            <li class="layui-this layui-nav-item"><a href="<?php echo url('news/index'); ?>">列表</a></li>
                            <li class="layui-nav-item"><a href="<?php echo url('news/add'); ?>"><?php echo lang('ADD'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-page-content">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form" method="post" action="<?php echo url('news/index'); ?>">
                        <div class="layui-form-item layuiadmin-card-text" style="margin-bottom: 0;">

                            <div class="layui-inline">
                                <label class="layui-form-label">发布时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input js-bootstrap-date" name="start_time"
                                           value="<?php echo input('request.start_time'); ?>"
                                           autocomplete="off" placeholder="开始日期">
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input js-bootstrap-date" name="end_time"
                                           value="<?php echo input('request.end_time'); ?>"
                                           autocomplete="off" placeholder="结束日期">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">关键字</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="keyword" value="<?php echo input('request.keyword'); ?>" placeholder="请输入关键字">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input type="submit" class="layui-btn btn-primary" value="搜索" />
                                    <a class="layui-btn layui-btn-danger btn-danger" href="<?php echo url('news/index'); ?>">清空</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="layui-page-content">
            <div class="layui-card">
                <form method="post" class="layui-card-body js-ajax-form" action="<?php echo url('news/listOrder'); ?>">
                    <button class="layui-btn js-ajax-submit" type="submit"><?php echo lang('SORT'); ?></button>
                    <table class="layui-table treeTable" id="menus-table" lay-even lay-skin="nob" lay-size="lg">
                        <thead>
                        <tr>
                            <th><?php echo lang('SORT'); ?></th>
                            <th>ID</th>
                            <th>标题</th>
                            <th>封面</th>
                            <th>浏览次数</th>
                            <th>添加时间</th>
                            <th align="center"><?php echo lang('ACTIONS'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
                            <tr>
                                <td>
                                    <input name="list_orders[<?php echo $vo['id']; ?>]" class="layui-input" type="text" value="<?php echo $vo['list_order']; ?>">
                                </td>
                                <td><?php echo $vo['id']; ?></td>
                                <td><?php echo $vo['name']; ?></td>
                                <td><?php if($vo['thumb']): ?><img src="<?php echo $vo['thumb']; ?>"><?php endif; ?></td>
                                <td><?php echo $vo['views']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>

                                <td>

                                    <div class="new-divider new-divider-vertical"></div>

                                    <a class="layui-bo layui-bo-small layui-bo-checked" href="<?php echo url("news/edit",array("id"=>$vo["id"])); ?>"><?php echo lang('EDIT'); ?></a>
                                    <div class="new-divider new-divider-vertical"></div>
                                    <a class="layui-bo layui-bo-small layui-bo-close js-ajax-delete" href="<?php echo url('news/del',array('id'=>$vo['id'])); ?>" <?php if($vo['views'] > 0): ?>data-msg="确定要删除么？"<?php endif; ?>><?php echo lang('DELETE'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo $page; ?></div>

                </form>
            </div>
        </div>

    </div>

</div>
<script src="/static/js/admin.js"></script>
</body>
</html>