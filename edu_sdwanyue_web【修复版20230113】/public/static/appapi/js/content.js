$(function(){
	if(per>0){
        var h=$('.page_content').height();
        var h_n=h*per*0.01;
        $('.page_content').height(h_n);
    }
})