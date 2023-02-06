$(function(){   
    Wind.css('layer');
    Wind.use('layer');
    
    var dateInput = $("input.js-date");
    if (dateInput.length) {
        Wind.use('datePicker', function () {
            dateInput.datePicker();
        });
    }
    
    $(".js_logout").click(function(){
        $.ajax({
            url:'/teacher/login/logout',
            type:'POST',
            data:{},
            dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.msg,{},function(){
                    location.href='/teacher/login/index';
                })
            }
        });
    })
})