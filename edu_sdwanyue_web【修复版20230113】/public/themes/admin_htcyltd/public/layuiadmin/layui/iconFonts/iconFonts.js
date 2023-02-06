/**
 * iconFonts 字体图标选择
 * User: jackhhy
 * Date: 2019/7/4-11:09
 * Link: https://gitee.com/luckygyl/iconFonts
 */
layui.define(['laypage', 'form'], function (exports) {
    "use strict";

    var IconFonts =function () {
            this.v = '1.0.beta';
        }, _MOD = 'IconFonts',
        _this = this,
        $ = layui.jquery,
        laypage = layui.laypage,
        form = layui.form,
        BODY = 'body',
        TIPS = '请选择图标';

    /**
     * 渲染组件
     * @param options
     * @returns {IconFonts}
     */
    IconFonts.prototype.render = function(options){
        var opts = options,
            // DOM选择器
            elem = opts.elem,
            // 数据类型：fontClass/layui_icon
            type = opts.type == null ? 'fontClass' : opts.type,
            // 是否分页：true/false
            page = opts.page,
            // 每页显示数量
            limit = limit == null ? 12 : opts.limit,
            // 是否开启搜索：true/false
            search = opts.search == null ? true : opts.search,
            // 点击回调
            click = opts.click,
            // json数据
            data = {},
            // 唯一标识
            tmp = new Date().getTime(),

            TITLE = 'layui-select-title',
            TITLE_ID = 'layui-select-title-' + tmp,
            ICON_BODY = 'layui-iconfonts-' + tmp,
            PICKER_BODY = 'layui-iconfonts-body-' + tmp,
            PAGE_ID = 'layui-iconfonts-page-' + tmp,
            LIST_BOX = 'layui-iconfonts-list-box',
            selected = 'layui-form-selected',
            unselect = 'layui-unselect';

        //layui.link("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");//font-awesome图标css
        //layui.link('https://www.layuicdn.com/layui-v2.5.4/css/layui.css');//layui_css


        var a = {
            init: function () {
                data = common.getData[type]();
                a.hideElem().createSelect().createBody().toggleSelect();
                common.loadCss();
                return a;
            },
            /**
             * 隐藏elem
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            hideElem: function () {
                $(elem).hide();
                return a;
            },

            /**
             *  绘制select下拉选择框
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            createSelect: function () {
                if (type=="fontClass"){
                    var str=  '<i class="fa fa-home"></i>' ;

                } else {
                    var str= '<i class="layui-icon">&#xe617;</i>';
                }
                var selectHtml = '<div class="layui-iconfonts layui-unselect layui-form-select" id="'+ ICON_BODY +'">' +
                    '<div class="'+ TITLE +'" id="'+ TITLE_ID +'">' +
                    '<div class="layui-iconfonts-item">'+
                    '<span class="layui-iconfonts-icon layui-unselect">' +
                    str+
                    '</span>'+
                    '<i class="layui-edge"></i>' +
                    '</div>'+
                    '</div>' +
                    '<div class="layui-anim layui-anim-upbit" style="">' +
                    '123' +
                    '</div>';
                $(elem).after(selectHtml);
                return a;
            },

            /**
             * 展开/折叠下拉框
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            toggleSelect: function () {
                var item = '#' + TITLE_ID + ' .layui-iconfonts-item,#' + TITLE_ID + ' .layui-iconfonts-item .layui-edge';
                a.event('click', item, function (e) {
                   // console.log('xxxx');
                    var $icon = $('#' + ICON_BODY);
                    if ($icon.hasClass(selected)) {
                        $icon.removeClass(selected).addClass(unselect);
                    } else {
                        $icon.addClass(selected).removeClass(unselect);
                    }
                    e.stopPropagation();
                });
                return a;
            },


            /**
             * 绘制主体部分
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            createBody: function () {
                // 获取数据
                var searchHtml = '';

                if (search) {
                    searchHtml = '<div class="layui-iconfonts-search">' +
                        '<input class="layui-input" placeholder="'+TIPS+'">' +
                        '<i class="fa fa-search"></i>' +
                        '</div>';
                }

                // 组合dom
                var bodyHtml = '<div class="layui-iconfonts-body" id="'+ PICKER_BODY +'">' +
                    searchHtml +
                    '<div class="'+ LIST_BOX +'"></div> '+
                    '</div>';
                $('#' + ICON_BODY).find('.layui-anim').eq(0).html(bodyHtml);
                a.search().createList().check().page();

                return a;
            },

            /**
             * 绘制图标列表
             * @param text 模糊查询关键字
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            createList: function (text) {
                var d = data,
                    l = d.length,
                    pageHtml = '',
                    listHtml = $('<div class="layui-iconfonts-list">')//'<div class="layui-iconfonts-list">';
                // 计算分页数据
                var _limit = limit, // 每页显示数量
                    _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1), // 总计多少页
                    _id = PAGE_ID;

                // 图标列表
                var icons = [];

                for (var i = 0; i < l; i++) {
                    var obj = d[i];

                    // 判断是否模糊查询
                    if (text && obj.indexOf(text) === -1) {
                        continue;
                    }
                    // 每个图标dom
                    var icon = '<div class="layui-iconfonts-icon-item" title="'+ obj +'">';
                   // console.log(isFontClass);
                    if (type=='fontClass'){
                        icon += '<i class="fa fa-'+ obj +'"  aria-hidden="true" style="margin-top: 13px;"></i>';
                    } else {
                        icon += '<i class="layui-icon '+ obj +'"></i>';
                    }
                    icon += '</div>';
                    icons.push(icon);
                }

                /**
                 * 查询出图标后再分页
                 * @type {number}
                 */
                l = icons.length;
                _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1);
                for (var i = 0; i < _pages; i++) {
                    // 按limit分块
                    var lm = $('<div class="layui-iconfonts-icon-limit" id="layui-iconfonts-icon-limit-'+ (i+1) +'">');

                    for (var j = i * _limit; j < (i+1) * _limit && j < l; j++) {
                        lm.append(icons[j]);
                    }

                    listHtml.append(lm);
                }

                /**
                 * 无数据
                 */
                if (l === 0) {
                    listHtml.append('<p class="layui-iconfonts-tips">无数据</p>');
                }

                /**
                 * 判断是否分页
                 */
                if (page){
                    $('#' + PICKER_BODY).addClass('layui-iconfonts-body-page');
                    pageHtml = '<div class="layui-iconfonts-page" id="'+ PAGE_ID +'">' +
                        '<div class="layui-iconfonts-page-count">' +
                        '<span id="'+ PAGE_ID +'-current">1</span>/' +
                        '<span id="'+ PAGE_ID +'-pages">'+ _pages +'</span>' +
                        ' (<span id="'+ PAGE_ID +'-length">'+ l +'</span>)' +
                        '</div>' +
                        '<div class="layui-iconfonts-page-operate">' +
                        '<i class="layui-icon" id="'+ PAGE_ID +'-prev" data-index="0" prev>&#xe603;</i> ' +
                        '<i class="layui-icon" id="'+ PAGE_ID +'-next" data-index="2" next>&#xe602;</i> ' +
                        '</div>' +
                        '</div>';
                }


                $('#' + ICON_BODY).find('.layui-anim').find('.' + LIST_BOX).html('').append(listHtml).append(pageHtml);
                return a;
            },
            /**
             * 数据分页
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            page: function () {
                var icon = '#' + PAGE_ID + ' .layui-iconfonts-page-operate .layui-icon';

                $(icon).unbind('click');
                a.event('click', icon, function (e) {
                    var elem = e.currentTarget,
                        total = parseInt($('#' +PAGE_ID + '-pages').html()),
                        isPrev = $(elem).attr('prev') !== undefined,
                        // 按钮上标的页码
                        index = parseInt($(elem).attr('data-index')),
                        $cur = $('#' +PAGE_ID + '-current'),
                        // 点击时正在显示的页码
                        current = parseInt($cur.html());

                    // 分页数据
                    if (isPrev && current > 1) {
                        current=current-1;
                        $(icon + '[prev]').attr('data-index', current);
                    } else if (!isPrev && current < total){
                        current=current+1;
                        $(icon + '[next]').attr('data-index', current);
                    }
                    $cur.html(current);

                    // 图标数据
                    $('.layui-iconfonts-icon-limit').hide();
                    $('#layui-iconfonts-icon-limit-' + current).show();
                    e.stopPropagation();
                });
                return a;
            },


            /**
             * 搜索
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            search: function () {
                var item = '#' + PICKER_BODY + ' .layui-iconfonts-search .layui-input';
                a.event('input propertychange', item, function (e) {
                    var elem = e.target,
                        t = $(elem).val();
                    a.createList(t);
                });
                a.event('click', item, function (e) {
                    e.stopPropagation();
                });
                return a;
            },


            /**
             * 点击选中图标
             * @returns {{init: (function()), createList: (function(*=)), search: (function()), toggleSelect: (function()), createBody: (function()), page: (function()), check: (function()), hideElem: (function()), createSelect: (function()), event: event}}
             */
            check: function () {
                var item = '#' + PICKER_BODY + ' .layui-iconfonts-icon-item';
                a.event('click', item, function (e) {
                       var icon = '';
                    if (type=='fontClass') {
                        var el = $(e.currentTarget).find('.fa');
                        var clsArr = el.attr('class').split(/[\s\n]/),
                            cls = clsArr[1],
                            icon =cls;
                        $('#' + TITLE_ID).find('.layui-iconfonts-item .fa').html('').attr('class', clsArr.join(' '));
                       // console.log(cls);
                    } else {
                        var el = $(e.currentTarget).find('.layui-icon');
                        var clsArr = el.attr('class').split(/[\s\n]/),
                            cls = clsArr[1],
                            icon = cls;
                        //console.log(cls);
                        $('#' + TITLE_ID).find('.layui-iconfonts-item .layui-icon').html('').attr('class', clsArr.join(' '));
                    }

                    $('#' + ICON_BODY).removeClass(selected).addClass(unselect);
                    $(elem).attr('value', icon);
                    if ($(elem).parents(".layui-form").length > 0) {
       var form_filter = $(elem).parents(".layui-form").attr("lay-filter");
              if (form_filter) {
                             var id = $(elem).attr("id")
                                        var oo = {}
                                                   oo[id] = icon;
                                                              form.val(form_filter, oo);
              }
                    }


                // 回调
                    if (click) {
                        click({
                            icon: icon
                        });
                    }

                });
                return a;
            },
            event: function (evt, el, fn) {
                $(BODY).on(evt, el, fn);
            }
        };

        var common = {
            /**
             * 加载样式表
             */
            loadCss: function () {
                var css = '.layui-iconfonts {max-width: 280px;}.layui-iconfonts .layui-anim{display:none;position:absolute;left:0;top:42px;padding:5px 0;z-index:899;min-width:100%;border:1px solid #d2d2d2;max-height:300px;overflow-y:auto;background-color:#fff;border-radius:2px;box-shadow:0 2px 4px rgba(0,0,0,.12);box-sizing:border-box;}.layui-iconfonts-item{border:1px solid #e6e6e6;width:90px;height:38px;border-radius:4px;cursor:pointer;position:relative;}.layui-iconfonts-icon{border-right:1px solid #e6e6e6;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;width:60px;height:100%;float:left;text-align:center;background:#fff;transition:all .3s;}.layui-iconfonts-icon i{line-height:38px;font-size:18px;}.layui-iconfonts-item > .layui-edge{left:70px;}.layui-iconfonts-item:hover{border-color:#D2D2D2!important;}.layui-iconfonts-item:hover .layui-iconfonts-icon{border-color:#D2D2D2!important;}.layui-iconfonts.layui-form-selected .layui-anim{display:block;}.layui-iconfonts-body{padding:6px;}.layui-iconfonts .layui-iconfonts-list{background-color:#fff;border:1px solid #ccc;border-radius:4px;}.layui-iconfonts .layui-iconfonts-icon-item{display:inline-block;width:21.1%;line-height:36px;text-align:center;cursor:pointer;vertical-align:top;height:36px;margin:4px;border:1px solid #ddd;border-radius:2px;transition:300ms;}.layui-iconfonts .layui-iconfonts-icon-item i.layui-icon{font-size:17px;}.layui-iconfonts .layui-iconfonts-icon-item:hover{background-color:#eee;border-color:#ccc;-webkit-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;-moz-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;box-shadow:0 0 2px #aaa,0 0 2px #fff inset;text-shadow:0 0 1px #fff;}.layui-iconfonts-search{position:relative;margin:0 0 6px 0;border:1px solid #e6e6e6;border-radius:2px;transition:300ms;}.layui-iconfonts-search:hover{border-color:#D2D2D2!important;}.layui-iconfonts-search .layui-input{cursor:text;display:inline-block;width:86%;border:none;padding-right:0;margin-top:1px;}.layui-iconfonts-search .layui-icon{position:absolute;top:11px;right:4%;}.layui-iconfonts-tips{text-align:center;padding:8px 0;cursor:not-allowed;}.layui-iconfonts-page{margin-top:6px;margin-bottom:-6px;font-size:12px;padding:0 2px;}.layui-iconfonts-page-count{display:inline-block;}.layui-iconfonts-page-operate{display:inline-block;float:right;cursor:default;}.layui-iconfonts-page-operate .layui-icon{font-size:12px;cursor:pointer;}.layui-iconfonts-body-page .layui-iconfonts-icon-limit{display:none;}.layui-iconfonts-body-page .layui-iconfonts-icon-limit:first-child{display:block;}';
                $('head').append('<style rel="stylesheet">'+css+'</style>');
            },
            /**
             * 获取数据
             */
            getData: {
                fontClass: function () {
                   var arr=[
                       "address-book",
                       "address-book-o",
                       "address-card",
                       "address-card-o",
                       "bandcamp",
                       "bath",
                       "bathtub",
                       "drivers-license",
                       "drivers-license-o",
                       "eercast",
                       "envelope-open",
                       "envelope-open-o",
                       "etsy",
                       "free-code-camp",
                       "grav",
                       "handshake-o",
                       "id-badge",
                       "id-card",
                       "id-card-o",
                       "imdb",
                       "linode",
                       "meetup",
                       "microchip",
                       "podcast",
                       "quora",
                       "ravelry",
                       "s15",
                       "shower",
                       "snowflake-o",
                       "superpowers",
                       "telegram",
                       "thermometer",
                       "thermometer-0",
                       "thermometer-1",
                       "thermometer-2",
                       "thermometer-3",
                       "thermometer-4",
                       "thermometer-empty",
                       "thermometer-full",
                       "thermometer-half",
                       "thermometer-quarter",
                       "thermometer-three-quarters",
                       "times-rectangle",
                       "times-rectangle-o",
                       "user-circle",
                       "user-circle-o",
                       "user-o",
                       "vcard",
                       "vcard-o",
                       "window-close",
                       "window-close-o",
                       "window-maximize",
                       "window-minimize",
                       "window-restore",
                       "wpexplorer",
                       "address-book",
                       "address-book-o",
                       "address-card",
                       "address-card-o",
                       "adjust",
                       "american-sign-language-interpreting",
                       "anchor",
                       "archive",
                       "area-chart",
                       "arrows",
                       "arrows-h",
                       "arrows-v",
                       "asl-interpreting",
                       "assistive-listening-systems",
                       "asterisk",
                       "at",
                       "audio-description",
                       "automobile",
                       "balance-scale",
                       "ban",
                       "bank",
                       "bar-chart",
                       "bar-chart-o",
                       "barcode",
                       "bars",
                       "bath",
                       "bathtub",
                       "battery",
                       "battery-0",
                       "battery-1",
                       "battery-2",
                       "battery-3",
                       "battery-4",
                       "battery-empty",
                       "battery-full",
                       "battery-half",
                       "battery-quarter",
                       "battery-three-quarters",
                       "bed",
                       "beer",
                       "bell",
                       "bell-o",
                       "bell-slash",
                       "bell-slash-o",
                       "bicycle",
                       "binoculars",
                       "birthday-cake",
                       "blind",
                       "bluetooth",
                       "bluetooth-b",
                       "bolt",
                       "bomb",
                       "book",
                       "bookmark",
                       "bookmark-o",
                       "braille",
                       "briefcase",
                       "bug",
                       "building",
                       "building-o",
                       "bullhorn",
                       "bullseye",
                       "bus",
                       "cab",
                       "calculator",
                       "calendar",
                       "calendar-check-o",
                       "calendar-minus-o",
                       "calendar-o",
                       "calendar-plus-o",
                       "calendar-times-o",
                       "camera",
                       "camera-retro",
                       "car",
                       "caret-square-o-down",
                       "caret-square-o-left",
                       "caret-square-o-right",
                       "caret-square-o-up",
                       "cart-arrow-down",
                       "cart-plus",
                       "cc",
                       "certificate",
                       "check",
                       "check-circle",
                       "check-circle-o",
                       "check-square",
                       "check-square-o",
                       "child",
                       "circle",
                       "circle-o",
                       "circle-o-notch",
                       "circle-thin",
                       "clock-o",
                       "clone",
                       "close",
                       "cloud",
                       "cloud-download",
                       "cloud-upload",
                       "code",
                       "code-fork",
                       "coffee",
                       "cog",
                       "cogs",
                       "comment",
                       "comment-o",
                       "commenting",
                       "commenting-o",
                       "comments",
                       "comments-o",
                       "compass",
                       "copyright",
                       "creative-commons",
                       "credit-card",
                       "credit-card-alt",
                       "crop",
                       "crosshairs",
                       "cube",
                       "cubes",
                       "cutlery",
                       "dashboard",
                       "database",
                       "deaf",
                       "deafness",
                       "desktop",
                       "diamond",
                       "dot-circle-o",
                       "download",
                       "drivers-license",
                       "drivers-license-o",
                       "edit",
                       "ellipsis-h",
                       "ellipsis-v",
                       "envelope",
                       "envelope-o",
                       "envelope-open",
                       "envelope-open-o",
                       "envelope-square",
                       "eraser",
                       "exchange",
                       "exclamation",
                       "exclamation-circle",
                       "exclamation-triangle",
                       "external-link",
                       "external-link-square",
                       "eye",
                       "eye-slash",
                       "eyedropper",
                       "fax",
                       "feed",
                       "female",
                       "fighter-jet",
                       "file-archive-o",
                       "file-audio-o",
                       "file-code-o",
                       "file-excel-o",
                       "file-image-o",
                       "file-movie-o",
                       "file-pdf-o",
                       "file-photo-o",
                       "file-picture-o",
                       "file-powerpoint-o",
                       "file-sound-o",
                       "file-video-o",
                       "file-word-o",
                       "file-zip-o",
                       "film",
                       "filter",
                       "fire",
                       "fire-extinguisher",
                       "flag",
                       "flag-checkered",
                       "flag-o",
                       "flash",
                       "flask",
                       "folder",
                       "folder-o",
                       "folder-open",
                       "folder-open-o",
                       "frown-o",
                       "futbol-o",
                       "gamepad",
                       "gavel",
                       "gear",
                       "gears",
                       "gift",
                       "glass",
                       "globe",
                       "graduation-cap",
                       "group",
                       "hand-grab-o",
                       "hand-lizard-o",
                       "hand-paper-o",
                       "hand-peace-o",
                       "hand-pointer-o",
                       "hand-rock-o",
                       "hand-scissors-o",
                       "hand-spock-o",
                       "hand-stop-o",
                       "handshake-o",
                       "hard-of-hearing",
                       "hashtag",
                       "hdd-o",
                       "headphones",
                       "heart",
                       "heart-o",
                       "heartbeat",
                       "history",
                       "home",
                       "hotel",
                       "hourglass",
                       "hourglass-1",
                       "hourglass-2",
                       "hourglass-3",
                       "hourglass-end",
                       "hourglass-half",
                       "hourglass-o",
                       "hourglass-start",
                       "i-cursor",
                       "id-badge",
                       "id-card",
                       "id-card-o",
                       "image",
                       "inbox",
                       "industry",
                       "info",
                       "info-circle",
                       "institution",
                       "key",
                       "keyboard-o",
                       "language",
                       "laptop",
                       "leaf",
                       "legal",
                       "lemon-o",
                       "level-down",
                       "level-up",
                       "life-bouy",
                       "life-buoy",
                       "life-ring",
                       "life-saver",
                       "lightbulb-o",
                       "line-chart",
                       "location-arrow",
                       "lock",
                       "low-vision",
                       "magic",
                       "magnet",
                       "mail-forward",
                       "mail-reply",
                       "mail-reply-all",
                       "male",
                       "map",
                       "map-marker",
                       "map-o",
                       "map-pin",
                       "map-signs",
                       "meh-o",
                       "microchip",
                       "microphone",
                       "microphone-slash",
                       "minus",
                       "minus-circle",
                       "minus-square",
                       "minus-square-o",
                       "mobile",
                       "mobile-phone",
                       "money",
                       "moon-o",
                       "mortar-board",
                       "motorcycle",
                       "mouse-pointer",
                       "music",
                       "navicon",
                       "newspaper-o",
                       "object-group",
                       "object-ungroup",
                       "paint-brush",
                       "paper-plane",
                       "paper-plane-o",
                       "paw",
                       "pencil",
                       "pencil-square",
                       "pencil-square-o",
                       "percent",
                       "phone",
                       "phone-square",
                       "photo",
                       "picture-o",
                       "pie-chart",
                       "plane",
                       "plug",
                       "plus",
                       "plus-circle",
                       "plus-square",
                       "plus-square-o",
                       "podcast",
                       "power-off",
                       "print",
                       "puzzle-piece",
                       "qrcode",
                       "question",
                       "question-circle",
                       "question-circle-o",
                       "quote-left",
                       "quote-right",
                       "random",
                       "recycle",
                       "refresh",
                       "registered",
                       "remove",
                       "reorder",
                       "reply",
                       "reply-all",
                       "retweet",
                       "road",
                       "rocket",
                       "rss",
                       "rss-square",
                       "s15",
                       "search",
                       "search-minus",
                       "search-plus",
                       "send",
                       "send-o",
                       "server",
                       "share",
                       "share-alt",
                       "share-alt-square",
                       "share-square",
                       "share-square-o",
                       "shield",
                       "ship",
                       "shopping-bag",
                       "shopping-basket",
                       "shopping-cart",
                       "shower",
                       "sign-in",
                       "sign-language",
                       "sign-out",
                       "signal",
                       "signing",
                       "sitemap",
                       "sliders",
                       "smile-o",
                       "snowflake-o",
                       "soccer-ball-o",
                       "sort",
                       "sort-alpha-asc",
                       "sort-alpha-desc",
                       "sort-amount-asc",
                       "sort-amount-desc",
                       "sort-asc",
                       "sort-desc",
                       "sort-down",
                       "sort-numeric-asc",
                       "sort-numeric-desc",
                       "sort-up",
                       "space-shuttle",
                       "spinner",
                       "spoon",
                       "square",
                       "square-o",
                       "star",
                       "star-half",
                       "star-half-empty",
                       "star-half-full",
                       "star-half-o",
                       "star-o",
                       "sticky-note",
                       "sticky-note-o",
                       "street-view",
                       "suitcase",
                       "sun-o",
                       "support",
                       "tablet",
                       "tachometer",
                       "tag",
                       "tags",
                       "tasks",
                       "taxi",
                       "television",
                       "terminal",
                       "thermometer",
                       "thermometer-0",
                       "thermometer-1",
                       "thermometer-2",
                       "thermometer-3",
                       "thermometer-4",
                       "thermometer-empty",
                       "thermometer-full",
                       "thermometer-half",
                       "thermometer-quarter",
                       "thermometer-three-quarters",
                       "thumb-tack",
                       "thumbs-down",
                       "thumbs-o-down",
                       "thumbs-o-up",
                       "thumbs-up",
                       "ticket",
                       "times",
                       "times-circle",
                       "times-circle-o",
                       "times-rectangle",
                       "times-rectangle-o",
                       "tint",
                       "toggle-down",
                       "toggle-left",
                       "toggle-off",
                       "toggle-on",
                       "toggle-right",
                       "toggle-up",
                       "trademark",
                       "trash",
                       "trash-o",
                       "tree",
                       "trophy",
                       "truck",
                       "tty",
                       "tv",
                       "umbrella",
                       "universal-access",
                       "university",
                       "unlock",
                       "unlock-alt",
                       "unsorted",
                       "upload",
                       "user",
                       "user-circle",
                       "user-circle-o",
                       "user-o",
                       "user-plus",
                       "user-secret",
                       "user-times",
                       "users",
                       "vcard",
                       "vcard-o",
                       "video-camera",
                       "volume-control-phone",
                       "volume-down",
                       "volume-off",
                       "volume-up",
                       "warning",
                       "wheelchair",
                       "wheelchair-alt",
                       "wifi",
                       "window-close",
                       "window-close-o",
                       "window-maximize",
                       "window-minimize",
                       "window-restore",
                       "wrench",
                       "american-sign-language-interpreting",
                       "asl-interpreting",
                       "assistive-listening-systems",
                       "audio-description",
                       "blind",
                       "braille",
                       "cc",
                       "deaf",
                       "deafness",
                       "hard-of-hearing",
                       "low-vision",
                       "question-circle-o",
                       "sign-language",
                       "signing",
                       "tty",
                       "universal-access",
                       "volume-control-phone",
                       "wheelchair",
                       "wheelchair-alt",
                       "hand-grab-o",
                       "hand-lizard-o",
                       "hand-o-down",
                       "hand-o-left",
                       "hand-o-right",
                       "hand-o-up",
                       "hand-paper-o",
                       "hand-peace-o",
                       "hand-pointer-o",
                       "hand-rock-o",
                       "hand-scissors-o",
                       "hand-spock-o",
                       "hand-stop-o",
                       "thumbs-down",
                       "thumbs-o-down",
                       "thumbs-o-up",
                       "thumbs-up",
                       "ambulance",
                       "automobile",
                       "bicycle",
                       "bus",
                       "cab",
                       "car",
                       "fighter-jet",
                       "motorcycle",
                       "plane",
                       "rocket",
                       "ship",
                       "space-shuttle",
                       "subway",
                       "taxi",
                       "train",
                       "truck",
                       "wheelchair",
                       "wheelchair-alt",
                       "genderless",
                       "intersex",
                       "mars",
                       "mars-double",
                       "mars-stroke",
                       "mars-stroke-h",
                       "mars-stroke-v",
                       "mercury",
                       "neuter",
                       "transgender",
                       "transgender-alt",
                       "venus",
                       "venus-double",
                       "venus-mars",
                       "file",
                       "file-archive-o",
                       "file-audio-o",
                       "file-code-o",
                       "file-excel-o",
                       "file-image-o",
                       "file-movie-o",
                       "file-o",
                       "file-pdf-o",
                       "file-photo-o",
                       "file-picture-o",
                       "file-powerpoint-o",
                       "file-sound-o",
                       "file-text",
                       "file-text-o",
                       "file-video-o",
                       "file-word-o",
                       "file-zip-o",
                       "circle-o-notch",
                       "cog",
                       "gear",
                       "refresh",
                       "spinner",
                       "check-square",
                       "check-square-o",
                       "circle",
                       "circle-o",
                       "dot-circle-o",
                       "minus-square",
                       "minus-square-o",
                       "plus-square",
                       "plus-square-o",
                       "square",
                       "square-o",
                       "cc-amex",
                       "cc-diners-club",
                       "cc-discover",
                       "cc-jcb",
                       "cc-mastercard",
                       "cc-paypal",
                       "cc-stripe",
                       "cc-visa",
                       "credit-card",
                       "credit-card-alt",
                       "google-wallet",
                       "paypal",
                       "area-chart",
                       "bar-chart",
                       "bar-chart-o",
                       "line-chart",
                       "pie-chart",
                       "bitcoin",
                       "btc",
                       "cny",
                       "dollar",
                       "eur",
                       "euro",
                       "gbp",
                       "gg",
                       "gg-circle",
                       "ils",
                       "inr",
                       "jpy",
                       "krw",
                       "money",
                       "rmb",
                       "rouble",
                       "rub",
                       "ruble",
                       "rupee",
                       "shekel",
                       "sheqel",
                       "try",
                       "turkish-lira",
                       "usd",
                       "won",
                       "yen",
                       "align-center",
                       "align-justify",
                       "align-left",
                       "align-right",
                       "bold",
                       "chain",
                       "chain-broken",
                       "clipboard",
                       "columns",
                       "copy",
                       "cut",
                       "dedent",
                       "eraser",
                       "file",
                       "file-o",
                       "file-text",
                       "file-text-o",
                       "files-o",
                       "floppy-o",
                       "font",
                       "header",
                       "indent",
                       "italic",
                       "link",
                       "list",
                       "list-alt",
                       "list-ol",
                       "list-ul",
                       "outdent",
                       "paperclip",
                       "paragraph",
                       "paste",
                       "repeat",
                       "rotate-left",
                       "rotate-right",
                       "save",
                       "scissors",
                       "strikethrough",
                       "subscript",
                       "superscript",
                       "table",
                       "text-height",
                       "text-width",
                       "th",
                       "th-large",
                       "th-list",
                       "underline",
                       "undo",
                       "unlink",
                       "angle-double-down",
                       "angle-double-left",
                       "angle-double-right",
                       "angle-double-up",
                       "angle-down",
                       "angle-left",
                       "angle-right",
                       "angle-up",
                       "arrow-circle-down",
                       "arrow-circle-left",
                       "arrow-circle-o-down",
                       "arrow-circle-o-left",
                       "arrow-circle-o-right",
                       "arrow-circle-o-up",
                       "arrow-circle-right",
                       "arrow-circle-up",
                       "arrow-down",
                       "arrow-left",
                       "arrow-right",
                       "arrow-up",
                       "arrows",
                       "arrows-alt",
                       "arrows-h",
                       "arrows-v",
                       "caret-down",
                       "caret-left",
                       "caret-right",
                       "caret-square-o-down",
                       "caret-square-o-left",
                       "caret-square-o-right",
                       "caret-square-o-up",
                       "caret-up",
                       "chevron-circle-down",
                       "chevron-circle-left",
                       "chevron-circle-right",
                       "chevron-circle-up",
                       "chevron-down",
                       "chevron-left",
                       "chevron-right",
                       "chevron-up",
                       "exchange",
                       "hand-o-down",
                       "hand-o-left",
                       "hand-o-right",
                       "hand-o-up",
                       "long-arrow-down",
                       "long-arrow-left",
                       "long-arrow-right",
                       "long-arrow-up",
                       "toggle-down",
                       "toggle-left",
                       "toggle-right",
                       "toggle-up",
                       "arrows-alt",
                       "backward",
                       "compress",
                       "eject",
                       "expand",
                       "fast-backward",
                       "fast-forward",
                       "forward",
                       "pause",
                       "pause-circle",
                       "pause-circle-o",
                       "play",
                       "play-circle",
                       "play-circle-o",
                       "random",
                       "step-backward",
                       "step-forward",
                       "stop",
                       "stop-circle",
                       "stop-circle-o",
                       "youtube-play",
                       "500px",
                       "adn",
                       "amazon",
                       "android",
                       "angellist",
                       "apple",
                       "bandcamp",
                       "behance",
                       "behance-square",
                       "bitbucket",
                       "bitbucket-square",
                       "bitcoin",
                       "black-tie",
                       "bluetooth",
                       "bluetooth-b",
                       "btc",
                       "buysellads",
                       "cc-amex",
                       "cc-diners-club",
                       "cc-discover",
                       "cc-jcb",
                       "cc-mastercard",
                       "cc-paypal",
                       "cc-stripe",
                       "cc-visa",
                       "chrome",
                       "codepen",
                       "codiepie",
                       "connectdevelop",
                       "contao",
                       "css3",
                       "dashcube",
                       "delicious",
                       "deviantart",
                       "digg",
                       "dribbble",
                       "dropbox",
                       "drupal",
                       "edge",
                       "eercast",
                       "empire",
                       "envira",
                       "etsy",
                       "expeditedssl",
                       "fa",
                       "facebook",
                       "facebook-f",
                       "facebook-official",
                       "facebook-square",
                       "firefox",
                       "first-order",
                       "flickr",
                       "font-awesome",
                       "fonticons",
                       "fort-awesome",
                       "forumbee",
                       "foursquare",
                       "free-code-camp",
                       "ge",
                       "get-pocket",
                       "gg",
                       "gg-circle",
                       "git",
                       "git-square",
                       "github",
                       "github-alt",
                       "github-square",
                       "gitlab",
                       "gittip",
                       "glide",
                       "glide-g",
                       "google",
                       "google-plus",
                       "google-plus-circle",
                       "google-plus-official",
                       "google-plus-square",
                       "google-wallet",
                       "gratipay",
                       "grav",
                       "hacker-news",
                       "houzz",
                       "html5",
                       "imdb",
                       "instagram",
                       "internet-explorer",
                       "ioxhost",
                       "joomla",
                       "jsfiddle",
                       "lastfm",
                       "lastfm-square",
                       "leanpub",
                       "linkedin",
                       "linkedin-square",
                       "linode",
                       "linux",
                       "maxcdn",
                       "meanpath",
                       "medium",
                       "meetup",
                       "mixcloud",
                       "modx",
                       "odnoklassniki",
                       "odnoklassniki-square",
                       "opencart",
                       "openid",
                       "opera",
                       "optin-monster",
                       "pagelines",
                       "paypal",
                       "pied-piper",
                       "pied-piper-alt",
                       "pied-piper-pp",
                       "pinterest",
                       "pinterest-p",
                       "pinterest-square",
                       "product-hunt",
                       "qq",
                       "quora",
                       "ra",
                       "ravelry",
                       "rebel",
                       "reddit",
                       "reddit-alien",
                       "reddit-square",
                       "renren",
                       "resistance",
                       "safari",
                       "scribd",
                       "sellsy",
                       "share-alt",
                       "share-alt-square",
                       "shirtsinbulk",
                       "simplybuilt",
                       "skyatlas",
                       "skype",
                       "slack",
                       "slideshare",
                       "snapchat",
                       "snapchat-ghost",
                       "snapchat-square",
                       "soundcloud",
                       "spotify",
                       "stack-exchange",
                       "stack-overflow",
                       "steam",
                       "steam-square",
                       "stumbleupon",
                       "stumbleupon-circle",
                       "superpowers",
                       "telegram",
                       "tencent-weibo",
                       "themeisle",
                       "trello",
                       "tripadvisor",
                       "tumblr",
                       "tumblr-square",
                       "twitch",
                       "twitter",
                       "twitter-square",
                       "usb",
                       "viacoin",
                       "viadeo",
                       "viadeo-square",
                       "vimeo",
                       "vimeo-square",
                       "vine",
                       "vk",
                       "wechat",
                       "weibo",
                       "weixin",
                       "whatsapp",
                       "wikipedia-w",
                       "windows",
                       "wordpress",
                       "wpbeginner",
                       "wpexplorer",
                       "wpforms",
                       "xing",
                       "xing-square",
                       "y-combinator",
                       "y-combinator-square",
                       "yahoo",
                       "yc",
                       "yc-square",
                       "yelp",
                       "yoast",
                       "youtube",
                       "youtube-play",
                       "youtube-square",
                       "ambulance",
                       "h-square",
                       "heart",
                       "heart-o",
                       "heartbeat",
                       "hospital-o",
                       "medkit",
                       "plus-square",
                       "stethoscope",
                       "user-md",
                       "wheelchair",
                       "wheelchair-alt"
                   ];
                   return arr;
                },
                layui_icon: function () {
                    var arrs = ["layui-icon-rate-half","layui-icon-rate","layui-icon-rate-solid","layui-icon-cellphone","layui-icon-vercode","layui-icon-login-wechat","layui-icon-login-qq","layui-icon-login-weibo","layui-icon-password","layui-icon-username","layui-icon-refresh-3","layui-icon-auz","layui-icon-spread-left","layui-icon-shrink-right","layui-icon-snowflake","layui-icon-tips","layui-icon-note","layui-icon-home","layui-icon-senior","layui-icon-refresh","layui-icon-refresh-1","layui-icon-flag","layui-icon-theme","layui-icon-notice","layui-icon-website","layui-icon-console","layui-icon-face-surprised","layui-icon-set","layui-icon-template-1","layui-icon-app","layui-icon-template","layui-icon-praise","layui-icon-tread","layui-icon-male","layui-icon-female","layui-icon-camera","layui-icon-camera-fill","layui-icon-more","layui-icon-more-vertical","layui-icon-rmb","layui-icon-dollar","layui-icon-diamond","layui-icon-fire","layui-icon-return","layui-icon-location","layui-icon-read","layui-icon-survey","layui-icon-face-smile","layui-icon-face-cry","layui-icon-cart-simple","layui-icon-cart","layui-icon-next","layui-icon-prev","layui-icon-upload-drag","layui-icon-upload","layui-icon-download-circle","layui-icon-component","layui-icon-file-b","layui-icon-user","layui-icon-find-fill","layui-icon-loading","layui-icon-loading-1","layui-icon-add-1","layui-icon-play","layui-icon-pause","layui-icon-headset","layui-icon-video","layui-icon-voice","layui-icon-speaker","layui-icon-fonts-del","layui-icon-fonts-code","layui-icon-fonts-html","layui-icon-fonts-strong","layui-icon-unlink","layui-icon-picture","layui-icon-link","layui-icon-face-smile-b","layui-icon-align-left","layui-icon-align-right","layui-icon-align-center","layui-icon-fonts-u","layui-icon-fonts-i","layui-icon-tabs","layui-icon-radio","layui-icon-circle","layui-icon-edit","layui-icon-share","layui-icon-delete","layui-icon-form","layui-icon-cellphone-fine","layui-icon-dialogue","layui-icon-fonts-clear","layui-icon-layer","layui-icon-date","layui-icon-water","layui-icon-code-circle","layui-icon-carousel","layui-icon-prev-circle","layui-icon-layouts","layui-icon-util","layui-icon-templeate-1","layui-icon-upload-circle","layui-icon-tree","layui-icon-table","layui-icon-chart","layui-icon-chart-screen","layui-icon-engine","layui-icon-triangle-d","layui-icon-triangle-r","layui-icon-file","layui-icon-set-sm","layui-icon-add-circle","layui-icon-404","layui-icon-about","layui-icon-up","layui-icon-down","layui-icon-left","layui-icon-right","layui-icon-circle-dot","layui-icon-search","layui-icon-set-fill","layui-icon-group","layui-icon-friends","layui-icon-reply-fill","layui-icon-menu-fill","layui-icon-log","layui-icon-picture-fine","layui-icon-face-smile-fine","layui-icon-list","layui-icon-release","layui-icon-ok","layui-icon-help","layui-icon-chat","layui-icon-top","layui-icon-star","layui-icon-star-fill","layui-icon-close-fill","layui-icon-close","layui-icon-ok-circle","layui-icon-add-circle-fine"];
                    return arrs;
                }
            }
        };

        a.init();
        return new IconFonts();
    };

        /**
     * 默认选中图标
     * @param filter
     * @param iconName
     */
    IconFonts.prototype.checkIcon = function (filter, iconName,type){
        if (type=="fontClass"){
            var icon=".fa";
        } else {
            var icon=".layui-icon";
        }
        var p = $('*[lay-filter='+ filter +']').next().find('.layui-iconfonts-item '+icon+''),
            c = iconName;

        if (c.indexOf('fa') > -1){
            p.html('').attr('class', 'fa ' + c);
        } else {
            p.html('').attr('class', 'layui-icon ' + c);
        }
    };

    var ics = new IconFonts();
    exports(_MOD, ics);
});