/*客户端socket.io接收与发送*/
//连接socket服务器
//连接状态设置为成功
var enterChat = 0;
////////////////////////////////////////////////////////////////////////////////////
var Socket = {
    nodejsInit: function (data) {
        socket.emit('conn', data);
        // socket.emit('conn', {uid:data.userinfo.id,roomnum: data.userinfo.roomnum,nickname: data.userinfo.user_nicename,stream:data.userinfo.stream,equipment: 'pc',token:data.userinfo.token});
        
        var enterChat_time=setInterval(function () {
            if (enterChat != 1) {
                /* socket.emit('conn', {uid:data.userinfo.id,roomnum: data.userinfo.roomnum,nickname: data.userinfo.user_nicename,stream:data.userinfo.stream,equipment: 'pc',token:data.userinfo.token});*/
                var msg={ct:'聊天服务器未连接，请刷新'};
                JsInterface.systemNot(msg);
            }else{
                clearInterval(enterChat_time);
            }
        }, 2000);
        
    },
    //==========node改====================emitData===========================================
    emitData: function (event, msg) {
        msg= typeof msg == 'object'?JSON.stringify(msg):msg;
        socket.emit(event, msg);
    }
    //==========node改====================emitData===========================================
}


/*客户端广播接收broadcasting*/

socket.on('broadcastingListen', function (data) {
	//for(var i in data){
	for(i=0;i<data.length;i++){
		if(i!='remove'){
            JsInterface.chatFromSocket(data[i]);		
		}

	} 

});
socket.on('heartbeat', function (data) {
    socket.emit("heartbeat","heartbeat");
});
//==========node改====================conn===========================================
socket.on('conn', function (data) {  
		if(data[0]=='ok'){
			enterChat = 1;
		}
});
//==========node改====================conn===========================================


