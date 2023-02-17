$(function(){


    /*********************************套餐页面 */
    //点击切换套餐介绍
    $('.detail_tc .bcontent .nav .js').click(function(){
        $('.detail_tc .bcontent .nav .bhkc').removeClass('b_fff');
        $('.detail_tc .bcontent .nav .bhkc').addClass('b_f6f6');

        $(this).removeClass('b_f6f6');
        $(this).addClass('b_fff');

        
        $('.detail_tc .bcontent .tip').removeClass('none');
        $('.detail_tc .bcontent .detail').removeClass('none');
        $('.detail_tc .bcontent .classlist').addClass('none');

    })

    $('.detail_tc .bcontent .nav .bhkc').click(function(){
        $('.detail_tc .bcontent .nav .js').removeClass('b_fff');
        $('.detail_tc .bcontent .nav .js').addClass('b_f6f6');

        $(this).removeClass('b_f6f6');
        $(this).addClass('b_fff');

        $('.detail_tc .bcontent .tip').addClass('none');
        $('.detail_tc .bcontent .detail').addClass('none');
        $('.detail_tc .bcontent .classlist').removeClass('none');
    })


    /*********************************课程页面 */

    //点击学习章节
    $('.detail_kc .area2 .zj .lix').click(function(){
        var lessonid = $(this).data('id');
        var isenter = $(this).data('isenter');
        var type = $(this).data('type');
        var url = $(this).data('url');
        var islive = $(this).data('islive');

        if(isenter!=1){
            if(ifbuy==0 && paytype==2){
                layer.prompt({title: '输入密码', formType: 1}, function(pass, index){
                    layer.close(index);
                    $.ajax({
                        url:__SITEURL__+'/api/?s=Course.CheckPass',
                        type:'POST',
                        data:{uid:userinfoj.id,token:userinfoj.token,courseid:courseid,pass:pass},
                        //dataType:'json',
                        error:function(e){
                            layer.msg('网路错误');
                        },
                        success:function(data){
                            layer.msg(data.data.msg);
                            if(data.data.code == 0){
                                ifbuy = 1;
                                goStudyClass(type,lessonid,url,islive);
                            }
                        }
                    })
                });
            }else{
                layer.msg('您暂时还不能学习');
            }
        }else{
            goStudyClass(type,lessonid,url,islive);
        }

    })


    function goStudyClass(type,lessonid,url,islive){

        if(type <4){ //页面学习
            location.href="/student/detail/classstudy?lessonid="+lessonid
        }else{ //需要进直播间
            //location.href=
            $.ajax({
                url:__SITEURL__+'/api/?s=Live.CheckLive',
                type:'POST',
                data:{uid:userinfoj.id,token:userinfoj.token,liveuid:liveuid,courseid:courseid,lessonid:lessonid},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
                    if(data.data.code == 0){
                        if(islive == 2){
                            window.open("/student/liveing/liveback?courseid="+courseid+"&lessonid="+lessonid);
                        }else{
                            window.open("/student/liveing/index?courseid="+courseid+"&lessonid="+lessonid);
                        }
                        
                    }else{
                        layer.msg(data.data.msg);
                    }
                }
            });
            
        }
    }

    //课程章节上拉
    $('.detail_kc .bcontent .area2 .li .up').each(function(){
        var _this = $(this);

        _this.click(function(){
            _this.addClass('none');
            _this.parent().find('.down').removeClass('none');
            _this.parent().parent().find('.zj').addClass('none');
        })
    })
    
    //课程章节下拉
    $('.detail_kc .bcontent .area2 .li .down').each(function(){
        var _this = $(this);

        _this.click(function(){
            _this.addClass('none');
            _this.parent().find('.up').removeClass('none');
            _this.parent().parent().find('.zj').removeClass('none');
        })
    })


    //选择星级
    $('.common_zhe .table .content span img').each(function(i){
        var _this = $(this);
        _this.click(function(){
            $('.common_zhe .table .content span img').attr('src','../../static/student/images/detail/star_no.png');
            star = i+1;
            for(var y=0;y<=i;y++){
                $('.common_zhe .table .content span img').eq(y).attr('src','../../static/student/images/detail/star_yes.png');
            }
            
        })

    })


    //点击切换介绍
    $('.detail_kc .bcontent .nav .js').click(function(){
        $('.detail_kc .bcontent .nav .kcmu').removeClass('b_fff');


        $(this).addClass('b_fff');

        
        $('.detail_kc .bcontent .area1').removeClass('none');
        $('.detail_kc .bcontent .area2').addClass('none');
        $('.detail_kc .bcontent .area3').addClass('none');

    })

    $('.detail_kc .bcontent .nav .kcmu').click(function(){
        $('.detail_kc .bcontent .nav .js').removeClass('b_fff');
        $('.detail_kc .bcontent .nav .pj').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_kc .bcontent .area1').addClass('none');
        $('.detail_kc .bcontent .area2').removeClass('none');
        $('.detail_kc .bcontent .area3').addClass('none');

    })

    $('.detail_kc .bcontent .nav .pj').click(function(){
        $('.detail_kc .bcontent .nav .js').removeClass('b_fff');
        $('.detail_kc .bcontent .nav .kcmu').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_kc .bcontent .area1').addClass('none');
        $('.detail_kc .bcontent .area2').addClass('none');
        $('.detail_kc .bcontent .area3').removeClass('none');

    })


    /*************************直播页面*/

    //点击切换介绍
    $('.detail_live .bcontent .nav .js').click(function(){
        $('.detail_live .bcontent .nav .pj').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_live .bcontent .area1').removeClass('none');
        $('.detail_live .bcontent .area3').addClass('none');

    })

    $('.detail_live .bcontent .nav .pj').click(function(){
        $('.detail_live .bcontent .nav .js').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_live .bcontent .area1').addClass('none');
        $('.detail_live .bcontent .area3').removeClass('none');

    })

    //点击进入直播间
    $('.detail_live .top .enterlive').click(function(){

        $.ajax({
            url:__SITEURL__+'/api/?s=Live.CheckLive',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,liveuid:liveuid,courseid:courseid},
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                if(data.data.code == 0){
                    if(paytype == 2 && ifbuy==0){
                        
                        layer.prompt({title: '输入密码', formType: 1}, function(pass, index){
                            layer.close(index);
                            $.ajax({
                                url:__SITEURL__+'/api/?s=Course.CheckPass',
                                type:'POST',
                                data:{uid:userinfoj.id,token:userinfoj.token,courseid:courseid,pass:pass},
                                //dataType:'json',
                                error:function(e){
                                    layer.msg('网路错误');
                                },
                                success:function(data){
                                    console.log(3333);
                                    layer.msg(data.data.msg);
                                    if(data.data.code == 0){
                                        ifbuy = 1;
                                        //window.location.reload();
                                        setTimeout(function(){
                                            window.open("/student/liveing/index?courseid="+courseid); 
                                        },1500)
                                    }
                                }
                            })
                        });
                    }else{
                        window.open("/student/liveing/index?courseid="+courseid); 
                    }

                }else{
                    layer.msg(data.data.msg);
                }
            }
        });
    })

    /*************************内容页面 */

    //点击切换介绍
    $('.detail_substance .bcontent .nav .js').click(function(){
        $('.detail_substance .bcontent .nav .pj').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_substance .bcontent .area1').removeClass('none');
        $('.detail_substance .bcontent .area3').addClass('none');

    })

    $('.detail_substance .bcontent .nav .pj').click(function(){
        $('.detail_substance .bcontent .nav .js').removeClass('b_fff');

        $(this).addClass('b_fff');

        
        $('.detail_substance .bcontent .area1').addClass('none');
        $('.detail_substance .bcontent .area3').removeClass('none');

    })




    //点击查看更多
    $('.bcontent .commentlist').on('click','.look_more',function(){
    
        var p = page+1;
        page = page+1;


        $.ajax({
            url:__SITEURL__+'/api/?s=Comment.GetList',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,courseid:courseid,p:p},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                var list = data.data.info[0].list;

                var html = '';
                for(var i=0;i<list.length;i++){

                    var star = '';
                    if(list[i].star == 0){
                        star = '<img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">';
                    }else if(list[i].star == 1){
                        star =  '<img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">';
                    }else if(list[i].star == 2){
                        star =  '<img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">';
                    }else if(list[i].star == 3){
                        star =  '<img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">';
                    }else if(list[i].star == 4){
                        star =  '<img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_no.png">';
                    }else if(list[i].star == 5){
                        star =  '<img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">\
                                <img class="starimg" src="../../static/student/images/detail/star_yes.png">';
                    }


                    html+='<div class="li">\
                            <img class="avatar" src="'+list[i].avatar+'">\
                            <div class="mess">\
                                <div class="name">'+list[i].user_nickname+'</div>\
                                <div class="star">'+star+'</div>\
                                <div class="comon">'+list[i].content+'</div>\
                            </div>\
                            <div class="date">'+list[i].add_time+'</div>\
                        </div>';
                }
                $('.bcontent .commentlist').append(html);

                $('.look_more').remove();
                if(list.length >=20){
                    $('.bcontent .commentlist').append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
                }
            }
        });
    })



    //点击弹出密码框
    $('.tanWp').click(function(){

        layer.prompt({title: '输入密码', formType: 1}, function(pass, index){
            layer.close(index);
            $.ajax({
                url:__SITEURL__+'/api/?s=Course.CheckPass',
                type:'POST',
                data:{uid:userinfoj.id,token:userinfoj.token,courseid:courseid,pass:pass},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
                    layer.msg(data.data.msg);
                    if(data.data.code == 0){
                        setTimeout(function(){
                            location.href="/student/detail/substancestudy?courseid="+courseid;
                        },1500)
                    }
                }
            })
        });
        //courseid
    })





    /*********购买课程、套餐 */
    $('.goBuy').click(function(){
        if(ismaterial == 1){
            $('.common_addr').removeClass('none');
        }else{
            window.open("/student/detail/buy?courseid="+courseid+"&type="+type+"&method="+method+"&totalmoney="+totalmoney+"&name="+name+"&ismaterial=0");  
        }
        
    })


    /****购买页面 */
    $('.detail_buy_content li').click(function(){
        $('.detail_buy_content_text_heng').addClass('none');
        $(this).find('.detail_buy_content_text_heng').removeClass('none');
        var payid = $(this).data('id');
        if(payid == 1){ //支付宝支付

            window.open("/student/detail/payment?courseid="+courseid+"&type="+type+"&method="+method+"&ismaterial="+ismaterial+"&payid="+payid);
        }else if(payid==2){ //微信支付
            $.ajax({
                url:'/student/detail/payment',
                type:'POST',
                data:{uid:userinfoj.id,token:userinfoj.token,payid:2,method:method,courseid:courseid,type:type,ismaterial:ismaterial},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){

                    if(data.code==0){
                        layer.msg(data.msg);
                    }else{
                        $('.detail_buy_content_tips').removeClass('none');
                        $('.detail_buy_content_tips_img').removeClass('none');
                        $('.detail_buy_content_tips_img').attr('src',data.data);
                    }

                }
            })

        }


       

    })


})