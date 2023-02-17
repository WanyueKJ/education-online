$(function(){   

    //点击搜索课程
    $('#search').click(function(){
        var val = $('#searchval').val();
        if(val.length ==0){
            layer.msg('搜索不能为空');
            return ;
        }
        location.href="/student/lessionlist/index?keywords="+val;
    })
    
    //点击选择课程
    $('.common_garde .grade .list .right .li').click(function(){
        var _this = $(this);
        var id = _this.data('id');


        $.ajax({
            url:'/student/index/SetGrade',
            type:'POST',
            data:{id:id},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                var data = JSON.parse(data);
                if(data.code==0){
                    layer.msg(data.msg);
                    setTimeout(function(){
                        location.reload();
                    },1500)
                }else{
                    if(_this.hasClass('on')){
                        $('.common_garde').removeClass('block');
                    }else{
                        layer.msg(data.msg);
                    }
                    
                }

                
            }
        });
    })

    //登录  注册等系列操作


    //点击唤出登录界面
    $('.common_header_nav .log_or_reg').click(function(){
        $('.common_login').removeClass('none');
        $(".input1").val("13800000000");
        $(".input2").val("123456");
    })

    //点击关闭登录界面
    $('.common_login .imgx').click(function(){
        $('.common_login').addClass('none');
    })
    

    //切换登录方式
    var logintype = 0; //0 密码登录 1 验证码登录
    $('.common_login .content .info .type .logintype').each(function(i){
        var _this = $(this);

        _this.click(function(){

            if(i==0 && i!=logintype){
                logintype = 0;

                _this.parent().parent().find('.inputs').removeClass('none');
                _this.parent().parent().find('.inputs1').addClass('none');
                _this.find('.heng').removeClass('none');
                _this.find('.c969696').addClass('black');
                _this.parent().find('.logintype').eq(1).find('.c969696').removeClass('black');
                _this.parent().find('.logintype').eq(1).find('.heng').addClass('none');
            }

            if(i==1 && i!=logintype){
                logintype = 1;
                _this.parent().parent().find('.inputs1').removeClass('none');
                _this.parent().parent().find('.inputs').addClass('none');
                _this.find('.heng').removeClass('none');
                _this.find('.c969696').addClass('black');
                _this.parent().find('.logintype').eq(0).find('.c969696').removeClass('black');
                _this.parent().find('.logintype').eq(0).find('.heng').addClass('none');
            }
            
        })
    })


    //忘记密码
    $('.common_login .content .info .forget').click(function(){
        $('.common_login .content').eq(0).addClass('none');
        $('.common_login .content').eq(1).removeClass('none');
        $('.common_login .content').eq(2).addClass('none');
    })

    //忘记密码点击返回
    $('.common_login .content .close .imgfan').click(function(){
        $('.common_login .content').eq(0).removeClass('none');
        $('.common_login .content').eq(1).addClass('none');
        $('.common_login .content').eq(2).addClass('none');
    })


    //点击去注册
    $('.common_login .content .info .goreg').click(function(){
        $('.common_login .content').eq(0).addClass('none');
        $('.common_login .content').eq(1).addClass('none');
        $('.common_login .content').eq(2).removeClass('none');
    })

    //点击去登录
    $('.common_login .content .info .gologin').click(function(){
        $('.common_login .content').eq(0).removeClass('none');
        $('.common_login .content').eq(1).addClass('none');
        $('.common_login .content').eq(2).addClass('none');
    })




    //退出登录
    $('#logout').click(function(){
        
        $.ajax({
            url:'/student/Login/logout',
            type:'POST',
            data:{},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    setTimeout(function(){
                        location.href="/"
                    },1500)
                } 
            }
        });
    })

    //点击登录
    $('.common_login .login').click(function(){

        
        if(logintype == 0){ //密码登录
            var username = $('.common_login .input1').val();
            var pass = $('.common_login .input2').val();
    
            if(username.length <=0){
                layer.msg('请输入手机号');
                return ;
            }
    
            if(pass.length <=0){
                layer.msg('请输入密码');
                return ;
            }
    
            $.ajax({
                url:'/student/Login/loginbypass',
                type:'POST',
                data:{name:username,pass:pass},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
                    layer.msg(data.msg);
                    if(data.code == 1){
                        setTimeout(function(){
                            location.reload();
                        },1500)
                    } 
                }
            });
        }else{ //验证码登录
            var name = $('.common_login .input3').val();
            var code = $('.common_login .input4').val();
    
            if(name.length <=0){
                layer.msg('请输入手机号');
                return ;
            }
    
            if(code.length <=0){
                layer.msg('请输入验证码');
                return ;
            }
    
            $.ajax({
                url:'/student/Login/loginbycode',
                type:'POST',
                data:{name:name,code:code},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
                    layer.msg(data.msg);
                    if(data.code == 1){
                        setTimeout(function(){
                            location.reload();
                        },1500)
                    } 
                }
            });
        }


    })

    //点击获取登录验证码
    $('.common_login .getLoginCode').click(function(){

        var _this = $(this);
        var mobile = $('.common_login .input3').val();

        if(mobile.length <=0){
            layer.msg('请输入手机号');
            return ;
        }
        $.ajax({
            url:'/student/Login/getCode',
            type:'POST',
            data:{type:0,mobile:mobile},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    _this.find('a').html('重新获取');
                } 
            }
        });
        
    })

    //点击获取忘记密码验证码
    $('.common_login .getForgetCode').click(function(){

        var _this = $(this);
        var mobile = $('.common_login .input8').val();

        if(mobile.length <=0){
            layer.msg('请输入手机号');
            return ;
        }
        $.ajax({
            url:'/student/Login/getCode',
            type:'POST',
            data:{type:1,mobile:mobile},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    _this.find('a').html('重新获取');
                } 
            }
        });
        
    })
    

    //点击忘记密码修改
    $('.common_login .fortrue').click(function(){

        var name = $('.common_login .input8').val();
        var code = $('.common_login .input9').val();
        var pass = $('.common_login .input10').val();
        
        if(name.length <=0){
            layer.msg('请输入手机号');
            return ;
        }

        if(code.length <=0){
            layer.msg('请输入验证码');
            return ;
        }

        if(pass.length <=0){
            layer.msg('请输入密码');
            return ;
        }
        $.ajax({
            url:'/student/Login/forget',
            type:'POST',
            data:{name:name,code:code,pass:pass},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    //location.reload();
                    $('.common_login .content').eq(0).removeClass('none');
                    $('.common_login .content').eq(1).addClass('none');
                    $('.common_login .content').eq(2).addClass('none');
                } 
            }
        });
        
    })


    //点击获取注册验证码
    $('.common_login .getRegCode').click(function(){

        var _this = $(this);
        var mobile = $('.common_login .input5').val();

        if(mobile.length <=0){
            layer.msg('请输入手机号');
            return ;
        }
        $.ajax({
            url:'/student/Login/getCode',
            type:'POST',
            data:{type:2,mobile:mobile},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    _this.find('a').html('重新获取');
                } 
            }
        });
        
    })

    //点击注册
    $('.common_login .reg').click(function(){

        var name = $('.common_login .input5').val();
        var code = $('.common_login .input6').val();
        var pass = $('.common_login .input7').val();

        if(name.length <=0){
            layer.msg('请输入手机号');
            return ;
        }

        if(code.length <=0){
            layer.msg('请输入验证码');
            return ;
        }

        if(pass.length <=0){
            layer.msg('请输入密码');
            return ;
        }

        $.ajax({
            url:'/student/Login/reg',
            type:'POST',
            data:{name:name,code:code,pass:pass},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    setTimeout(function(){
                        location.reload();
                    },1500)
                } 
            }
        });
        

    })


    //微信登录
    $('.common_login .wx_img').click(function(){
        
        location.href="/student/Login/weixin"
    })

    //qq登录
    $('.common_login .qq_img').click(function(){

        location.href="/student/Login/qq"

    })


    //点击头像
    $('.common_header_nav .avatar').click(function(){

        var ishave= $('.common_header_nav .drop_down').hasClass('none');

        if(ishave){
            $('.common_header_nav .drop_down').removeClass('none');
        }else{
            $('.common_header_nav .drop_down').addClass('none');
        }
        
    })

    //点击打开选择学级框
    $('#gard').click(function(){

        $('.common_garde').addClass('block');

    })


    //点击添加地址
    $('.common_addr_content_sub').click(function(){
        var arr = $("#addr").serializeArray();
        var province = $(".pick-province").html();
        var city = $(".pick-city").html();
        var county = $(".pick-county").html();

        if(province == '省' || city == '市' || county == '区' || city == '请选择市' || county == '请选择县'){
            layer.msg('请选择完整地区');
            return;
        }

        if(arr[0].value == ''){
            layer.msg('请填写详细地址');
            return;
        }
        if(arr[1].value == ''){
            layer.msg('请填写收货人姓名');
            return;
        }
        if(arr[2].value == ''){
            layer.msg('请填写收货人手机号');
            return;
        }


        $.ajax({
            url:__SITEURL__+'/api/?s=Addr.SetAddr',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,name:arr[1].value,mobile:arr[2].value,province:province,city:city,area:county,addr:arr[0].value},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    setTimeout(function(){
                        window.open("/student/detail/buy?courseid="+courseid+"&type="+type+"&method="+method+"&totalmoney="+totalmoney+"&name="+name+"&ismaterial=1");
                    },1500)
                } 
            }
        });
    })

    //点击关闭地址填写框
    $('.common_addr_content_top_img').click(function(){
        $('.common_addr').addClass('none');

    })
    
    
})