
function uploadImg(){
    var file = $('#upload').find('#ware_file')[0].files[0];

    var formData = new FormData(); 
    formData.append('file', file);
    
    $.ajax({ 
        url: '/teacher/account/uploadImg',
        type: 'post', 
        data: formData, 
        dataType:'json',
        cache: false, 
        processData: false, 
        contentType: false, 
        success:function(data){
            if(data.code==0){
                layer.msg(data.msg);
                return !1;
            }
            layer.msg(data.msg,{},function(){
                location.reload();
            })
        },
        error:function(e){
            layer.msg("网络错误");
        }
    })
}
$(function(){   
    $('#js_m_avatar').click(function(){
        $('#upload').empty();
        var input = '<input type="file" id="ware_file" name="file" accept="image/*"/>';
        $('#upload').html(input);
        var index='ware_file';
        var iptt=document.getElementById(index);
        if(window.addEventListener) { // Mozilla, Netscape, Firefox
            iptt.addEventListener('change',function(){
                uploadImg();
            },false);
        }else{
            iptt.attachEvent('onchange',function(){
                uploadImg();
            });
        }
        $('#'+index).click();
    })
    
    $('#js_m_name').click(function(){
        layer.prompt({title: '请输入昵称', formType: 0}, function(pass, index){
            layer.close(index);
            $.ajax({ 
                url: '/teacher/account/upname',
                type: 'post', 
                data: {name:pass}, 
                dataType:'json',
                success:function(data){
                    if(data.code==0){
                        layer.msg(data.msg);
                        return !1;
                    }
                    layer.msg(data.msg,{},function(){
                        location.reload();
                    })
                },
                error:function(e){
                    layer.msg("网络错误");
                }
            })
        });
    })
})