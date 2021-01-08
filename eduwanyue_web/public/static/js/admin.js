layui.use(['layer', 'form', 'laydate'], function() {
    var layer = layui.layer,
        form = layui.form,
        $ = layui.$,
        laydate = layui.laydate;
        
    //全局ajax处理
    $.ajaxSetup({
        complete: function(jqXHR) {},
        data: {},
        error: function(jqXHR, textStatus, errorThrown) {
            //请求失败处理
        }
    });

    if ($.browser && $.browser.msie) {
        //ie 都不缓存
        $.ajaxSetup({
            cache: false
        });
    }

    //不支持placeholder浏览器下对placeholder进行处理
    if (document.createElement('input').placeholder !== '') {
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur().parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
        });
    }


    // 所有加了dialog类名的a链接，自动弹出它的href
    if ($('a.js-dialog').length) {
        $('.js-dialog').on('click', function(e) {
            e.preventDefault();
            var $this = $(this);
            layer.open({
                title: $this.prop('title'),
                offset: '100px',
                fixed: false,
                content: "$(this).prop('href')",
                cancel: function(index, layero) {
                    $this.focus();
                    layer.close(index)
                    return true;
                }

            });

        }).attr('role', 'button');
    }


    // 所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
    var ajaxForm_list = $('form.js-ajax-form');
    if (ajaxForm_list.length) {
        var $btn;
        $('button.js-ajax-submit').on('click', function(e) {
            console.log('js-ajax-submit');
            var btn = $(this),
                form = btn.parents('form.js-ajax-form');
            $btn = btn;
            if (btn.data("loading")) {
                return;
            }
            //批量操作 判断选项
            if (btn.data('subcheck')) {
                btn.parent().find('span').remove();
                if (form.find('input.js-check:checked').length) {
                    btn.data('subcheck', false);
                } else {
                    $('<span class="tips_error">请至少选择一项</span>').appendTo(btn.parent()).fadeIn('fast');
                    return false;
                }
            }


            var msg = btn.data('msg');
            if (msg) {
                layer.confirm(btn.data('msg'), {
                    icon: 0,
                    title: '提示',
                    fixed: false,
                    offset: '100px'
                }, function(index) {
                    btn.data('msg', false);
                    btn.click();

                    layer.close(index);
                }, function(index) {
                    layer.close(index);
                });

                return false;
            }

            //ie处理placeholder提交问题
            if ($.browser && $.browser.msie) {
                form.find('[placeholder]').each(function() {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            }
        });

        ajaxForm_list.each(function() {
            $(this).validate({
                //是否在获取焦点时验证
                //onfocusout : false,
                //是否在敲击键盘时验证
                //onkeyup : false,
                //当鼠标点击时验证
                //onclick : false,
                //给未通过验证的元素加效果,闪烁等
                highlight: function(element, errorClass, validClass) {
                    if (element.type === "radio") {
                        this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                    } else {
                        var $element = $(element);
                        $element.addClass(errorClass).removeClass(validClass);
                        $element.parent().addClass("has-error"); //bootstrap3表单
                        $element.parents('.control-group').addClass("error"); //bootstrap2表单

                    }
                },
                unhighlight: function(element, errorClass, validClass) {
                    if (element.type === "radio") {
                        this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                    } else {
                        var $element = $(element);
                        $element.removeClass(errorClass).addClass(validClass);
                        $element.parent().removeClass("has-error"); //bootstrap3表单
                        $element.parents('.control-group').removeClass("error"); //bootstrap2表单
                    }
                },
                showErrors: function(errorMap, errorArr) {
                    var i, elements, error;
                    for (i = 0; this.errorList[i]; i++) {
                        error = this.errorList[i];
                        if (this.settings.highlight) {
                            this.settings.highlight.call(this, error.element, this.settings.errorClass, this.settings.validClass);
                        }
                        //this.showLabel( error.element, error.message );
                    }
                    if (this.errorList.length) {
                        //this.toShow = this.toShow.add( this.containers );
                    }
                    if (this.settings.success) {
                        for (i = 0; this.successList[i]; i++) {
                            //this.showLabel( this.successList[ i ] );
                        }
                    }
                    if (this.settings.unhighlight) {
                        for (i = 0, elements = this.validElements(); elements[i]; i++) {
                            this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, this.settings.validClass);
                        }
                    }
                    this.toHide = this.toHide.not(this.toShow);
                    this.hideErrors();
                    this.addWrapper(this.toShow).show();
                },
                submitHandler: function(form) {
                    var $form = $(form);
                    $form.ajaxSubmit({
                        url: $btn.data('action') ? $btn.data('action') : $form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                        dataType: 'json',
                        beforeSubmit: function(arr, $form, options) {

                            $btn.data("loading", true);
                            var text = $btn.text();

                            //按钮文案、状态修改
                            $btn.text(text + '...').prop('disabled', true).addClass('disabled');
                        },
                        success: function(data, statusText, xhr, $form) {

                            function _refresh() {
                                if (data.url) {
                                    //返回带跳转地址
                                    if ($('.js-close').length) {
		                                var index = parent.layer.getFrameIndex(window.name);
		                                parent.layer.close(index); //再执行关闭
                                    } else {
                                    window.location.href = data.url;
                                    } 
                                } else {
                                    if (data.code == 1) {
                                        //刷新当前页
                                        reloadPage(window);
                                    }
                                }
                            }

                            var text = $btn.text();

                            //按钮文案、状态修改
                            $btn.removeClass('disabled').prop('disabled', false).text(text.replace('...', '')).parent().find('span').remove();
                            if (data.code == 1) {
                                if ($btn.data('success')) {
                                    var successCallback = $btn.data('success');
                                    window[successCallback](data, statusText, xhr, $form);
                                    return;
                                }

                                layer.msg(data.msg, { time: 1200, fixed: false, offset: '100px' }, function() {
                                    if ($btn.data('refresh') == undefined || $btn.data('refresh')) {

                                        if ($btn.data('success_refresh')) {
                                            var successRefreshCallback = $btn.data('success_refresh');
                                            window[successRefreshCallback](data, statusText, xhr, $form);
                                            return;
                                        } else {
                                            _refresh();
                                        }

                                    }
                                });
                                $(window).focus();
                            } else if (data.code == 0) {
                                var $verify_img = $form.find(".verify_img");
                                if ($verify_img.length) {
                                    $verify_img.attr("src", $verify_img.attr("src") + "&refresh=" + Math.random());
                                }

                                var $verify_input = $form.find("[name='verify']");
                                $verify_input.val("");

                                //$('<span class="tips_error">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('fast');
                                $btn.removeProp('disabled').removeClass('disabled');

                                layer.msg(data.msg, { time: 1200, fixed: false, offset: '100px' }, function() {
                                    _refresh();
                                });
                                $(window).focus();
                            }


                        },
                        error: function(xhr, e, statusText) {

                            layer.confirm(statusText, {
                                time: 1200,
                                icon: 0,
                                title: '提示信息',
                                fixed: false,
                                offset: '100px'
                            }, function(index) {
                                reloadPage(window);

                                layer.close(index);
                            }, function(index) {
                                reloadPage(window);

                                layer.close(index);
                            });

                        },
                        complete: function() {
                            $btn.data("loading", false);
                        }
                    });
                }
            });
        });

    }

    //dialog弹窗内的关闭方法
    $('#js-dialog-close').on('click', function(e) {
        e.preventDefault();
        try {
            layer.close(layer.index);
        } catch (err) {
            layer.closeAll();
        };
    });

    //所有的删除操作，删除数据后刷新页面
    if ($('a.js-ajax-delete').length) {

        $('.js-ajax-delete').on('click', function(e) {
            e.preventDefault();
            var that = this,
                $this = $(this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');

            layer.open({
                title: '提示信息',
                offset: '100px',
                icon: 3,
                fixed: false,
                move: true,
                content: msg ? msg : '确定要删除吗？',
                btn: ['确定', '关闭'],
                yes: function(index, layero) {
                    $.getJSON(href).done(function(data) {
                        if (data.code == '1') {
                            layer.msg(data.msg, { offset: '100px', time: 1200 }, function() {
                                if (refresh == undefined || refresh) {
                                    if (data.url) {
                                        //返回带跳转地址
                                        window.location.href = data.url;
                                    } else {
                                        //刷新当前页
                                        reloadPage(window);
                                    }
                                }
                            });

                        } else if (data.code == '0') {
                            layer.confirm(data.msg, { offset: '100px', title: data.msg }, function(index) {
                                layer.close(index);
                                return true;
                            });
                        }
                    });
                },
                btn2: function(index, layero) {
                    layer.close(index)
                    return false;
                },
                cancel: function(index, layero) {
                    layer.close(index)
                    return false;
                },
                end: function() {
                    that.focus(); //关闭时让触发弹窗的元素获取焦点
                    return true;
                }
            });
        });

    }

    if ($('.js-ajax-dialog-btn').length) {

        $('.js-ajax-dialog-btn').on('click', function(e) {
            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');
            if (!msg) {
                msg = "您确定要进行此操作吗？";
            }
            layer.open({
                title: '提示信息',
                icon: 3,
                fixed: false,
                move: true,
                offset: '100px',
                content: msg,
                btn: ['确定', '关闭'],
                yes: function(index, layero) {
                    $.ajax({
                        url: href,
                        type: 'post',
                        success: function(data) {
                            if (data.code == 1) {
                                layer.msg(data.msg, { offset: '100px', time: 1200 }, function() {
                                    if (refresh == undefined || refresh) {
                                        if (data.url) {
                                            //返回带跳转地址
                                            window.location.href = data.url;
                                        } else {
                                            //刷新当前页
                                            reloadPage(window);
                                        }
                                    }

                                });
                            } else if (data.code == 0) {

                                layer.confirm(data.msg, { offset: '100px', title: data.msg }, function(index) {
                                    layer.close(index);
                                });
                            }
                        }
                    })
                    layer.close(index); //如果设定了yes回调，需进行手工关闭
                },
                btn2: function(index, layero) {
                    layer.close(index)
                    return false;
                },
                cancel: function(index, layero) {
                    layer.close(index)
                    return false;
                },
                end: function() {
                    $_this.focus(); //关闭时让触发弹窗的元素获取焦点
                    return true;
                }
            });
        });


    }

    if ($('a.js-ajax-btn').length) {
        $('.js-ajax-btn').on('click', function(e) {
            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                msg = $this.data('msg');
            refresh = $this.data('refresh');
            href = href ? href : $this.attr('href');
            refresh = refresh == undefined ? 1 : refresh;


            $.getJSON(href).done(function(data) {
                if (data.code == 1) {
                    layer.msg(data.msg, { offset: '100px' }, function() {
                        if (data.url) {
                            location.href = data.url;
                            return;
                        }

                        if (refresh || refresh == undefined) {
                            reloadPage(window);
                        }
                    });
                } else if (data.code == 0) {

                    layer.confirm(data.msg, { offset: '100px', title: data.msg }, function(index) {
                        if (data.url) {
                            location.href = data.url;
                        }
                        layer.close(index);
                    });
                }
            });

        });
    }

    /*复选框全选(支持多个，纵横双控全选)。
     *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
     *说明：
     *	"js-check"的"data-xid"对应其左侧"js-check-all"的"data-checklist"；
     *	"js-check"的"data-yid"对应其上方"js-check-all"的"data-checklist"；
     *	全选框的"data-direction"代表其控制的全选方向(x或y)；
     *	"js-check-wrap"同一块全选操作区域的父标签class，多个调用考虑
     */

    if ($('.js-check-wrap').length) {
        var check_all = $('input.js-check-all'),
            check_items;

        //分组各纵横项
        var check_all_direction = check_all.data('direction');
        check_items = $('input.js-check[data-' + check_all_direction + 'id="' + check_all.data('checklist') + '"]').not(":disabled");

        //点击全选框
        form.on('checkbox(js-check-all)', function(data) {

            var check_wrap = check_all.parents('.js-check-wrap'); //当前操作区域所有复选框的父标签（重用考虑）

            if (data.elem.checked) {
                //全选状态
                check_items.prop('checked', true);

                //所有项都被选中
                if (check_wrap.find('input.js-check').length === check_wrap.find('input.js-check:checked').length) {
                    check_wrap.find(check_all).prop('checked', true);
                }

            } else {
                //非全选状态
                check_items.removeProp('checked');

                check_wrap.find(check_all).removeProp('checked');

                //另一方向的全选框取消全选状态
                var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
                check_wrap.find($('input.js-check-all[data-direction="' + direction_invert + '"]')).removeProp('checked');
            }
            form.render('checkbox')
        });

        //点击非全选时判断是否全部勾选
        form.on('checkbox(js-check)', function(data) {
            if (data.elem.checked) {

                if (check_items.filter(':checked').length === check_items.length) {
                    //已选择和未选择的复选框数相等
                    $.each(check_all, function() {
                        $(this).prop('checked', true);
                    });
                    // check_all.prop('checked', true);
                }

            } else {
                check_all.removeProp('checked');
            }
            form.render('checkbox')
        });
    }

    //日期选择器
    var dateInput = $("input.js-date");
    if (dateInput.length) {
        lay('input.js-date').each(function() {
            laydate.render({
                elem: this,
                type: 'date',
                format: 'yyyy-MM-dd',
                theme: 'grid'
            });
        });
    }

    //日期+时间选择器
    var dateTimeInput = $("input.js-datetime");
    if (dateTimeInput.length) {
        lay('input.js-datetime').each(function() {
            laydate.render({
                elem: this,
                type: 'datetime',
                format: 'yyyy-MM-dd HH:mm',
                theme: 'grid'
            });
        });
    }

    var yearInput = $("input.js-year");
    if (yearInput.length) {
        lay('input.js-year').each(function() {
            laydate.render({
                elem: this,
                type: 'year',
                format: 'yyyy',
                theme: 'grid'
            });
        });
    }

    // bootstrap年选择器
    var bootstrapYearInput = $("input.js-bootstrap-year")
    if (bootstrapYearInput.length) {
        lay('input.js-bootstrap-year').each(function() {
            laydate.render({
                elem: this,
                type: 'year',
                format: 'yyyy'
            });
        });
    }

    // bootstrap日期选择器
    var bootstrapDateInput = $("input.js-bootstrap-date")
    if (bootstrapDateInput.length) {
        lay('input.js-bootstrap-date').each(function() {
            laydate.render({
                elem: this,
                type: 'date',
                format: 'yyyy-MM-dd'
            });
        });
    }

    // bootstrap日期选择器日期+时间选择器
    var bootstrapDateTimeInput = $("input.js-bootstrap-datetime");
    if (bootstrapDateTimeInput.length) {
        lay('input.js-bootstrap-datetime').each(function() {
            laydate.render({
                elem: this,
                type: 'datetime',
                format: 'yyyy-MM-dd HH:mm'
            });
        });
    }

    //tab
    var tabs_nav = $('ul.js-tabs-nav');
    if (tabs_nav.length) {
        Wind.use('tabs', function () {
            tabs_nav.tabs('.js-tabs-content > div');
        });
    }

    //地址联动
    var $js_address_select = $('.js-address-select');
    if ($js_address_select.length > 0) {
        $('.js-address-province-select,.js-address-city-select').change(function () {
            var $this                   = $(this);
            var id                      = $this.val();
            var $child_area_select;
            var $this_js_address_select = $this.parents('.js-address-select');
            if ($this.is('.js-address-province-select')) {
                $child_area_select = $this_js_address_select.find('.js-address-city-select');
                $this_js_address_select.find('.js-address-district-select').hide();
            } else {
                $child_area_select = $this_js_address_select.find('.js-address-district-select');
            }

            var empty_option = '<option class="js-address-empty-option" value="">' + $child_area_select.find('.js-address-empty-option').text() + '</option>';
            $child_area_select.html(empty_option);

            var child_area_html = $this.data('childarea' + id);
            if (child_area_html) {
                $child_area_select.show();
                $child_area_select.html(child_area_html);
                return;
            }

            $.ajax({
                url: $this_js_address_select.data('url'),
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.code == 1) {
                        if (data.data.areas.length > 0) {
                            var html = [empty_option];

                            $.each(data.data.areas, function (i, area) {
                                var area_html = '<option value="[id]">[name]</option>';
                                area_html     = area_html.replace('[name]', area.name);
                                area_html     = area_html.replace('[id]', area.id);
                                html.push(area_html);
                            });
                            html = html.join('', html);
                            $this.data('childarea' + id, html);
                            $child_area_select.html(html);
                            $child_area_select.show();
                        } else {
                            $child_area_select.hide();

                        }
                    }
                },
                error: function () {

                },
                complete: function () {

                }
            });
        });

    }
    //地址联动end

    var public = {
        confirm: function() {

        }, //重新刷新页面，使用location.reload()有可能导致重新提交
        reloadPage: function(win) {
            var location = win.location;
            location.href = location.pathname + location.search;
        },
        /**
         * 页面跳转
         * @param url 要打开的页面地址
         */
        redirect: function(url) {
            location.href = url;
        },
        /**
         * 读取cookie
         * @param name
         * @returns
         */
        getCookie: function(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        },
        /*设置cookie*/
        setCookie: function(name, value, options) {
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            var path = options.path ? '; path=' + options.path : '';
            var domain = options.domain ? '; domain=' + options.domain : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        },
        /**
         * 打开iframe式的窗口对话框
         * @param url
         * @param title
         * @param options
         */
        openIframeDialog: function(url, title, options) {
            var params = {
                type: 2,
                offset: '100px',
                title: title,
                shadeClose: true,
                anim: 0,
                fixed: false,
                shade: [0.8, '#393D49'],
                shadeClose: true,
                area: ['1000px', '600px'],
                move: true,
                fixed: false,
                content: url,
                btn: ['关闭'],
                yes: function(index, layero) {
                    //do something
                    layer.close(index); //如果设定了yes回调，需进行手工关闭
                }
            };
            console.log('999');
            params = options ? $.extend(params, options) : params;
            layer.open(params);
        },
        /**
         * 打开地图对话框
         *
         * @param url
         * @param title
         * @param options
         * @param callback
         */
        openMapDialog: function(url, title, options, callback) {
            var params = {
                title: title,
                offset: '100px',
                shade: [0.8, '#333333'],
                shadeClose: true,
                width: "95%",
                height: 400,
                yes: function() {
                    if (callback) {
                        var d = this.iframe.contentWindow;
                        var lng = $("#lng_input", d.document).val();
                        var lat = $("#lat_input", d.document).val();
                        var address = {};
                        address.address = $("#address_input", d.document).val();
                        address.province = $("#province_input", d.document).val();
                        address.city = $("#city_input", d.document).val();
                        address.district = $("#district_input", d.document).val();
                        callback.apply(this, [lng, lat, address]);
                    }
                }
            };
            params = options ? $.extend(params, options) : params;
            layer.open(url, params);

        },
        /**
         * 查看图片对话框
         * @param img 图片地址
         */
        imagePreviewDialog: function(img) {

            layer.photos({
                photos: {
                    "title": "", //相册标题
                    "id": 'image_preview', //相册id
                    "start": 0, //初始显示的图片序号，默认0
                    "data": [ //相册包含的图片，数组格式
                        {
                            "alt": "",
                            "pid": 666, //图片id
                            "src": img, //原图地址
                            "thumb": img //缩略图地址
                        }
                    ]
                } //格式见API文档手册页
                ,
                anim: 5, //0-6的选择，指定弹出图片动画类型，默认随机
                shadeClose: true,
                offset: '100px',
                fixed: false,
                // skin: 'layui-layer-nobg',
                shade: [0.5, '#000000'],
                shadeClose: true,
            })

        },
        openIframeLayer: function openIframeLayer(url, title, options) {
            var params = {
                type: 2,
                offset: '100px', //坐标
                title: title,
                fixed: false,
                move: '.layui-layer-title',
                shadeClose: true,
                anim: 0,
                shade: [0.3, '#000000'],
                shadeClose: true,
                area: ['100%', '100%'],
                move: true,
                fixed: false,
                content: url,
                yes: function(index, layero) {
                    // do something
                    // 获取iframe层的body
                    var body = layer.getChildFrame('body', index);
                    // 找到隐藏的提交按钮模拟点击提交
                    body.find('#js-ajax-submit').click();
                },
                btn2: function(index, layero) {
                    layer.close(index)
                    return false;
                }
            };
            console.log('openIframeLayer');
            params = options ? $.extend(params, options) : params;
            layer.open(params);
        },
        artdialogAlert: function(msg) {
            layer.open({
                id: new Date().getTime(),
                icon: 2,
                fixed: false,
                offset: '100px',
                move: '.layui-layer-title',
                fixed: false,
                shade: [0.8, '#393D49'],
                shadeClose: true,
                opacity: 0,
                content: msg,
                yes: function() {
                    return true;
                }
            });
        }
    };
    window.admin = public;
});

/**
 * 打开文件上传对话框
 * @param dialog_title 对话框标题
 * @param callback 回调方法，参数有（当前dialog对象，选择的文件数组，你设置的extra_params）
 * @param extra_params 额外参数，object
 * @param multi 是否可以多选
 * @param filetype 文件类型，image,video,audio,file
 * @param app  应用名，CMF的应用名
 */
function openUploadDialog(dialog_title, callback, extra_params, multi, filetype, app) {
    Wind.css('artDialog');
    multi      = multi ? 1 : 0;
    filetype   = filetype ? filetype : 'image';
    app        = app ? app : GV.APP;
    var params = '&multi=' + multi + '&filetype=' + filetype + '&app=' + app;
    Wind.use("artDialog", "iframeTools", function () {
        art.dialog.open(GV.ROOT + 'user/Asset/webuploader?' + params, {
            title: dialog_title,
            id: new Date().getTime(),
            width: '600px',
            height: '350px',
            lock: true,
            fixed: true,
            background: "#CCCCCC",
            opacity: 0,
            ok: function () {
                if (typeof callback == 'function') {
                    var iframewindow = this.iframe.contentWindow;
                    var files        = iframewindow.get_selected_files();
                    console.log(files);
                    if (files && files.length > 0) {
                        callback.apply(this, [this, files, extra_params]);
                    } else {
                        return false;
                    }

                }
            },
            cancel: true
        });
    });
}

/**
 * 单个文件上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF的应用名
 */
function uploadOne(dialog_title, input_selector, filetype, extra_params, app) {
    filetype = filetype ? filetype : 'file';
    openUploadDialog(dialog_title, function (dialog, files) {
        $(input_selector).val(files[0].filepath);
        $(input_selector + '-preview').attr('href', files[0].preview_url);

        $(input_selector + '-name').val(files[0].name);
        $(input_selector + '-name-text').text(files[0].name);


    }, extra_params, 0, filetype, app);
}

/**
 * 单个图片上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF的应用名
 */
function uploadOneImage(dialog_title, input_selector, extra_params, app) {
    openUploadDialog(dialog_title, function (dialog, files) {
        $(input_selector).val(files[0].filepath);
        $(input_selector + '-preview').attr('src', files[0].preview_url);

        $(input_selector + '-name').val(files[0].name);
        $(input_selector + '-name-text').text(files[0].name);

    }, extra_params, 0, 'image', app);
}

/**
 * 多图上传
 * @param dialog_title 上传对话框标题
 * @param container_selector 图片容器
 * @param item_tpl_wrapper_id 单个图片html模板容器id
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF 的应用名
 */
function uploadMultiImage(dialog_title, container_selector, item_tpl_wrapper_id, extra_params, app) {
    openUploadDialog(dialog_title, function (dialog, files) {
        var tpl  = $('#' + item_tpl_wrapper_id).html();
        var html = '';
        $.each(files, function (i, item) {
            var itemtpl = tpl;
            itemtpl     = itemtpl.replace(/\{id\}/g, item.id);
            itemtpl     = itemtpl.replace(/\{url\}/g, item.url);
            itemtpl     = itemtpl.replace(/\{preview_url\}/g, item.preview_url);
            itemtpl     = itemtpl.replace(/\{filepath\}/g, item.filepath);
            itemtpl     = itemtpl.replace(/\{name\}/g, item.name);
            html += itemtpl;
        });
        $(container_selector).append(html);

    }, extra_params, 1, 'image', app);
}

/**
 * 多文件上传
 * @param dialog_title 上传对话框标题
 * @param container_selector 图片容器
 * @param item_tpl_wrapper_id 单个图片html模板容器id
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF 的应用名
 */
function uploadMultiFile(dialog_title, container_selector, item_tpl_wrapper_id, filetype, extra_params, app) {
    filetype = filetype ? filetype : 'file';
    openUploadDialog(dialog_title, function (dialog, files) {
        var tpl  = $('#' + item_tpl_wrapper_id).html();
        var html = '';
        $.each(files, function (i, item) {
            var itemtpl = tpl;
            itemtpl     = itemtpl.replace(/\{id\}/g, item.id);
            itemtpl     = itemtpl.replace(/\{url\}/g, item.url);
            itemtpl     = itemtpl.replace(/\{preview_url\}/g, item.preview_url);
            itemtpl     = itemtpl.replace(/\{filepath\}/g, item.filepath);
            itemtpl     = itemtpl.replace(/\{name\}/g, item.name);
            html += itemtpl;
        });
        $(container_selector).append(html);

    }, extra_params, 1, filetype, app);
}