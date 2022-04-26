<?php /*a:1:{s:77:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/admin/index/index.html";i:1645779015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta charset="utf-8">
    <title><?php echo $configpub['site_name']; ?> <?php echo lang('ADMIN_CENTER'); ?></title>
    <meta name="description" content="This is page-header (.page-header &gt; h1)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link href="/static/font-awesome/css/font-awesome.min.css?page=index" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/icon.css">
    <script>
        //全局变量
        var GV = {
            HOST: "<?php echo (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] !== '')?$_SERVER['HTTP_HOST']:''); ?>",
            ROOT: "/",
            WEB_ROOT: "/",
            JS_ROOT: "static/js/"
        };
    </script>



    <?php $submenus=$menus; function getsubmenu($submenus){ if(!(empty($submenus) || (($submenus instanceof \think\Collection || $submenus instanceof \think\Paginator ) && $submenus->isEmpty()))): foreach($submenus as $menu){ ?>
        <li data-name="<?php echo (isset($menu['icon']) && ($menu['icon'] !== '')?$menu['icon']:'component'); ?>" class="layui-nav-item">
            <?php 
                $menu_name=lang($menu['lang']);
                $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
             if(empty($menu['items'])){ ?>
            <a lay-href="<?php echo $menu['url']; ?>" lay-tips="<?php echo $menu_name; ?>" lay-direction="2">
                <i class="layui-icon <?php echo (isset($menu['icon']) && ($menu['icon'] !== '')?$menu['icon']:'component'); ?>"></i>
                <cite><?php echo $menu_name; ?></cite>
            </a>
            <?php }else{ ?>
            <a href="javascript:;" lay-tips="<?php echo $menu_name; ?>" lay-direction="2">
                <i class="layui-icon <?php echo (isset($menu['icon']) && ($menu['icon'] !== '')?$menu['icon']:'component'); ?>"></i>
                <cite><?php echo $menu_name; ?></cite>
            </a>
            <dl class="layui-nav-child">
                <?php getsubmenu1($menu['items']) ?>
            </dl>
            <?php } ?>
        </li>
        <?php } ?>
    <?php endif; } function getsubmenu1($submenus){ foreach($submenus as $menu){ 
        $menu_name=lang($menu['lang']);
        $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
     if(empty($menu['items'])){ ?>
    <dd data-name="<?php echo $menu_name; ?>">
        <a lay-href="<?php echo $menu['url']; ?>" id="<?php echo $menu['id']; ?>" title="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></a>
    </dd>
    <?php }else{ ?>
    <dd data-name="<?php echo $menu_name; ?>">
        <a href="javascript:;" id="<?php echo $menu['id']; ?>" title="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></a>
        <dl class="layui-nav-child">
            <?php getsubmenu2($menu['items']) ?>
        </dl>
    </dd>
    <?php } }} function getsubmenu2($submenus){foreach($submenus as $menu){ 
        $menu_name=lang($menu['lang']);
        $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
     ?>
    <dd data-name="<?php echo $menu_name; ?>"><a lay-href="<?php echo $menu['url']; ?>" id="<?php echo $menu['id']; ?>" title="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></a></dd>
    <?php }} ?>

    <style>
        .layui-side-menu .layui-nav .layui-nav-item a{
            padding-left: 40px;
            padding-right: 0;
        }
        .layui-side-menu .layui-nav{
            width: 170px;
        }
        .layui-side-menu .layui-side-scroll{
            width: 170px;

        }
        .layui-layout-admin .layui-logo{
            width: 170px;
        }

    </style>

</head>
<body class="layui-layout-body">
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="/" target="_blank" title="<?php echo lang('WEBSITE_HOME_PAGE'); ?>">
                        <i class="layui-icon layui-icon-website"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                <li class="layui-nav-item" lay-tips="回收站" lay-direction="3" lay-unselect>
                    <?php if(cmf_auth_check(cmf_get_current_admin_id(),'admin/RecycleBin/index')): ?>
                        <a lay-href="<?php echo url('admin/RecycleBin/index'); ?>" layadmin-event="index_recycle" lay-text="回收站">
                            <i class="layui-icon layui-icon-delete"></i>
                        </a>
                    <?php endif; ?>
                </li>
                <li class="layui-nav-item" lay-tips="<?php echo lang('ADMIN_MENU_INDEX'); ?>" lay-direction="3" lay-unselect>
                    <?php if(APP_DEBUG): ?>
                        <a lay-href="<?php echo url('admin/Menu/index'); ?>" layadmin-event="index_menu" lay-text="<?php echo lang('ADMIN_MENU_INDEX'); ?>">
                            <i class="icon-icon layui-icon-md-crop"></i>
                        </a>
                    <?php endif; ?>
                </li>
                <li class="layui-nav-item" lay-tips="<?php echo lang('ADMIN_SETTING_CLEARCACHE'); ?>" lay-direction="3" lay-unselect>
                    <?php if(cmf_auth_check(cmf_get_current_admin_id(),'admin/Setting/clearcache')): ?>
                        <a href="javascript:admin.openIframeLayer('<?php echo url('admin/Setting/clearcache'); ?>','清除缓存',{skin: 'layer-ext',area:['300px','100px'],end:function(){location.reload();}});" layadmin-event="<?php echo lang('ADMIN_SETTING_CLEARCACHE'); ?>" lay-text="<?php echo lang('ADMIN_SETTING_CLEARCACHE'); ?>">
                            <i class="layui-icon layui-icon-align-left"></i>
                        </a>
                    <?php endif; ?>
                </li>
                <li class="layui-nav-item" lay-tips="资源管理" lay-direction="3" lay-unselect>
                    <?php if(cmf_auth_check(cmf_get_current_admin_id(),'user/AdminAsset/index')): ?>
                        <a lay-href="<?php echo url('user/AdminAsset/index'); ?>" layadmin-event="userAdminAssetindex" lay-text="资源管理"">
                        <i class="layui-icon layui-icon-file"></i>
                        </a>
                    <?php endif; ?>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect style="margin-right:10px;">
                    <a href="javascript:;">
                        <?php if(isset($admin['avatar']) && $admin['avatar']): ?>
                            <img class="nav-user-photo" width="30" height="30" src="<?php echo cmf_get_user_avatar_url($admin['avatar']); ?>" alt="<?php echo $admin['user_login']; ?>" class="layui-circle">
                            <?php else: ?>
                            <img class="layui-circle" width="30" height="30" src="/themes/admin_htcyltd/public/layuiadmin/layui/images/tx.jpg" alt="<?php echo (isset($admin['user_login']) && ($admin['user_login'] !== '')?$admin['user_login']:''); ?>">
                        <?php endif; ?>
                        <cite><?php echo lang('WELCOME_USER',array('user_nickname' => empty($admin['user_nickname'] )? $admin['user_login'] : $admin['user_nickname'])); ?></cite>
                    </a>
                    <dl style="min-width:auto;" class="layui-nav-child">
                        <dd><a lay-href="<?php echo url('setting/site'); ?>"><?php echo lang('ADMIN_SETTING_SITE'); ?></a></dd>
                        <dd><a lay-href="<?php echo url('user/userinfo'); ?>"><?php echo lang('ADMIN_USER_USERINFO'); ?></a></dd>
                        <dd><a lay-href="<?php echo url('setting/password'); ?>"><?php echo lang('ADMIN_SETTING_PASSWORD'); ?></a></dd>
                        <hr>
                        <dd style="text-align: center;"><a href="<?php echo url('Public/logout'); ?>"><?php echo lang('LOGOUT'); ?></a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect="">
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>
        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="<?php echo url('admin/Main/index'); ?>">
                    <a href="<?php echo url('admin/index/index'); ?>"><span><?php echo $configpub['site_name']; ?></span></a>
                </div>
                <ul class="layui-nav layui-nav-tree arrow3" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <?php echo getsubmenu($submenus); ?>
                </ul>
            </div>
        </div>
        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down layadmin-tabs-control-bg">
                <ul class="layui-nav layadmin-tabs-select " lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein ">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="<?php echo url('admin/Main/index'); ?>" lay-attr="<?php echo url('admin/Main/index'); ?>" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>
        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="<?php echo url('admin/Main/index'); ?>" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>
        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="/themes/admin_htcyltd/public/layuiadmin/layui/layui.js"></script>
<script src="/themes/admin_htcyltd/public/assets/js/jquery-1.10.2.min.js"></script>
<script src="/themes/admin_htcyltd/public/assets/js/bootstrap.min.js"></script>
<script src="/static/js/admin.js"></script>
<script src="/themes/admin_htcyltd/public/assets/simpleboot3/js/adminindex.js"></script>

<script>
    layui.config({
        base: '/themes/admin_htcyltd/public/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>

</body>
</html>