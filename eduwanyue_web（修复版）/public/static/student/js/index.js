$(function(){   

    window.name == ""; //判断是首次加载还是刷新

    if(window.name == "" || window.name !=isBackLog){
      //判断需不需要展示登录
      if(isBackLog != 0){
        $('.common_login').removeClass('none');
      }
      window.name = isBackLog; 
    }
    //首页轮播图
    var swiper = new Swiper('.swiper-container', {
        autoplay:true,
        loop:true,
      pagination: {
        el: '.swiper-pagination',
      },
    });

})