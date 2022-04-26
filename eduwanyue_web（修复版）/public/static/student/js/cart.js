$(function(){   




    //点击选中
    $('.cart_content .selectno').click(function(){
        var _this = $(this);
        var money = parseInt(_this.data('money'));
        var cartid = _this.data('cartid');
        var isselect = _this.data('isselect');

        $.ajax({
            url:__SITEURL__+'/api/?s=Cart.Update',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartid,isselect:isselect},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    // selectnum = selectnum+1;
                    // totalmoney = totalmoney+money;
            
                    // $('.cart_content .bottomcont .selectnum').html(selectnum);
                    // $('.cart_content .bottomcont .totalmoney').html(totalmoney);


                    // _this.data('isselect',0)
                    // _this.attr("class","selected");
                    // _this.attr("src","../../static/student/images/cart/selected.png");

                    // if(selectnum == totalnum){
                    //     $('.cart_content .selectall').attr('src','../../static/student/images/cart/selected.png');
                    //     isAllSelect = 1;
                    // }
                    location.reload();

                }
            }
        });
    })

    //点击取消选中
    $('.cart_content .selected').click(function(){
        var _this = $(this);
        var money = parseInt(_this.data('money'));
        var cartid = _this.data('cartid');
        var isselect = _this.data('isselect');
        
        $.ajax({
            url:__SITEURL__+'/api/?s=Cart.Update',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartid,isselect:isselect},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    // selectnum = selectnum-1;
                    // totalmoney = totalmoney-money;
            
                    // $('.cart_content .bottomcont .selectnum').html(selectnum);
                    // $('.cart_content .bottomcont .totalmoney').html(totalmoney);

                    // _this.data('isselect',1);
                    // _this.attr("class","selectno");
                    // _this.attr("src","../../static/student/images/cart/selectno.png");

                    // $('.cart_content .selectall').attr('src','../../static/student/images/cart/allselect.png');
                    // isAllSelect = 0;
                    location.reload();
                }
            }
        });
    })
    //点击全选
    $('.cart_content .selectall').click(function(){

        if(totalnum==0){
            return ;
        }

        if(isAllSelect == 1){ //取消全选
            $.ajax({
                url:__SITEURL__+'/api/?s=Cart.Update',
                type:'POST',
                data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartids,isselect:0},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
            
                    layer.msg(data.data.msg);
                    if(data.data.code == 0){
                        setTimeout(function(){
                            location.reload();
                        },1500)
                    }
                }
            });
        }else{

            $.ajax({
                url:__SITEURL__+'/api/?s=Cart.Update',
                type:'POST',
                data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartids,isselect:1},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
            
                    layer.msg(data.data.msg);
                    if(data.data.code == 0){
                        setTimeout(function(){
                            location.reload();
                        },1500)
                    }
                }
            });
        }
    }) 

    //点击删除某个课程
    $('.cart_content .content .li .delete').click(function(){
        var cartid = $(this).data('cartid');
        $.ajax({
            url:__SITEURL__+'/api/?s=Cart.Del',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartid},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    setTimeout(function(){
                        location.reload();
                    },1500)
                }
            }
        });
    })

    //点击全部删除课程
    $('.cart_content .bottomcont .delete').click(function(){


        var cartids = '';
        $('.cart_content .content .li .selected').each(function(){
            cartids+=$(this).data('cartid')+',';
        })

        if(cartids ==''){
            layer.msg('至少选中一个删除');
            return ;
        }


        cartids = cartids.substring(0,cartids.length-1);
        $.ajax({
            url:__SITEURL__+'/api/?s=Cart.Del',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,cartid:cartids},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    setTimeout(function(){
                        location.reload();
                    },1500)
                }
            }
        });
    })


    //点击结算
    $('.cart_content .bottomcont .paymoney').click(function(){

        if(ismaterial == 1){
            $('.common_addr').removeClass('none');
        }else{
            window.open("/student/detail/buy?&method="+method+"&totalmoney="+totalmoney+"&ismaterial="+ismaterial);
        }
       
    }) 


    
})



