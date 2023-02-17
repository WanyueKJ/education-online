Wind.css('layer');
Wind.use('layer');
$(function(){
    $(".t_login_b_l li").click(function(){
        var _this=$(this);
        if(_this.hasClass('on')){
            return !1;
        }
        _this.siblings().removeClass('on');
        _this.addClass('on');
        $('.t_login_b_l .t_login_tab_bd').hide().eq(_this.index()).show();
    });
    
    $('.js_l_forget').click(function(){
        $('.t_login_b_l').hide();
        $('.t_login_b_f').show();
    })
    
    $('.js_l_login').click(function(){
        $('.t_login_b_f').hide();
        $('.t_login_b_l').show();
    })
    
    var issubmit=1;
    var time_m=null;
    var time_f=null;
    
    function setTimer(timer,obj){
        var time_t=60;
        timer=window.setInterval(function() {
            if (time_t > 0) {
                var i = time_t--+"s 重新获取";
                obj.addClass("waiting").text(i);
            } else {
                window.clearInterval(timer);
                timer = null;
                obj.removeClass("waiting").text('获取验证码');
            }
        },1e3);
    }
    function getCode(mobile,type,timer,obj){
        
        if(!issubmit){
            return !1;
        }
        
        if(obj.hasClass('waiting')){
            return !1;
        }
        
        
        $.ajax({
			url:'/teacher/login/getCode',
			data:{mobile:mobile,type:type},
			type:'POST',
			dataType:'json',
			success:function(data){
				issubmit=1;
				if(data.code==0){
                    layer.msg(data.msg);
					return !1;
					
				}
                
                layer.msg(data.msg);
                setTimer(timer,obj);
                return !1;
			},
			error:function(){
				issubmit=1;
				layer.msg("请重试");
				return !1;
			}
			
		})
    }
    
    $("#js_m_getcode").on("click",function(){
        var _this=$(this);
        
        var type=0;
        var timer=time_m;
        var mobile=$('#js_m_name').val();
        getCode(mobile,type,timer,_this);
    })
    
    $("#js_f_getcode").on("click",function(){
        
        var _this=$(this);
        
        var type=1;
        var timer=time_f;
        var mobile=$('#js_f_name').val();
        getCode(mobile,type,timer,_this);
    })

	
	$(".js_login_l").on("click",function(){
		if(!issubmit){
			return !1;
		}

		var name = $('#js_l_name').val();
		var pass = $('#js_l_pass').val();

		if(name==''){
			layer.msg("请输入手机号");
			return !1;
		}
		if(pass==''){
			layer.msg("请输入密码");
			return !1;
		}
		issubmit=0;
		$.ajax({
			url:'/teacher/login/loginbypass',
			data:{name:name,pass:pass},
			type:'POST',
			dataType:'json',
			success:function(data){
				issubmit=1;
				if(data.code==0){
                    layer.msg(data.msg);
					return !1;
					
				}
                
                layer.msg(data.msg,{},function(){
                    location.href='/teacher/index/index';
                });
                
                return !1;
			},
			error:function(){
				issubmit=1;
				layer.msg("请重试");
				return !1;
			}
			
		})
	})
    
    $(".js_login_m").on("click",function(){
		if(!issubmit){
			return !1;
		}

		var name = $('#js_m_name').val();
		var code = $('#js_m_code').val();

		if(name==''){
			layer.msg("请输入手机号");
			return !1;
		}
		if(code==''){
			layer.msg("请输入验证码");
			return !1;
		}
		issubmit=0;
		$.ajax({
			url:'/teacher/login/loginbycode',
			data:{name:name,code:code},
			type:'POST',
			dataType:'json',
			success:function(data){
				issubmit=1;
				if(data.code==0){
                    layer.msg(data.msg);
					return !1;
					
				}
                
                layer.msg(data.msg,{},function(){
                    location.href='/teacher/index/index';
                });
                
                return !1;
			},
			error:function(){
				issubmit=1;
				layer.msg("请重试");
				return !1;
			}
			
		})
	})
    
    $(".js_login_f").on("click",function(){
		if(!issubmit){
			return !1;
		}

		var name = $('#js_f_name').val();
		var code = $('#js_f_code').val();
		var pass = $('#js_f_pass').val();

		if(name==''){
			layer.msg("请输入手机号");
			return !1;
		}
		if(code==''){
			layer.msg("请输入验证码");
			return !1;
		}
        
        if(pass==''){
			layer.msg("请输入密码");
			return !1;
		}
		issubmit=0;
		$.ajax({
			url:'/teacher/login/forget',
			data:{name:name,code:code,pass:pass},
			type:'POST',
			dataType:'json',
			success:function(data){
				issubmit=1;
				if(data.code==0){
                    layer.msg(data.msg);
					return !1;
					
				}
                
                layer.msg(data.msg,{},function(){
                    $('#js_l_login').click();
                    $('.t_login_b_l li')[0].click();
                });
                
                return !1;
			},
			error:function(){
				issubmit=1;
				layer.msg("请重试");
				return !1;
			}
			
		})
	})
})