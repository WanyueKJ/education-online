(function(){
    $('.node-p .l_bd_l_l_n span').click(function(){
        var _li=$(this).parents('.node-p');
        var id=_li.attr('id');
        if(_li.hasClass('node-close')){
            $('.child-of-node').hide();
            $('.node-p').removeClass('node-open').addClass('node-close');
            $('.child-of-'+id).show();
            _li.removeClass('node-close').addClass('node-open');
        }else{
            $('.child-of-node').hide();
            _li.removeClass('node-open').addClass('node-close');
        }
        
    })
})()