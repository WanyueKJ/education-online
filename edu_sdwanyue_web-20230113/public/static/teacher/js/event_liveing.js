/* socket 回调处理 */
var JsInterface={
	chatFromSocket: function(data) {
        if(data=='stopplay'){
            return !1;
        }
		var data = JSON.parse(data);
		var msgObject = data.msg[0];
		// var msgtype = msgObject.msgtype;
		var msgaction = msgObject.action;
		var msgmethod = msgObject._method_;
		if(msgmethod=='SendMsg'){ //聊天信息
			this.sendMsg(msgObject);
		}else if(msgmethod=='SystemNot'){ //系统信息
			this.systemNot(msgObject);
		}else if(msgmethod=='StartEndLive'){ //开关播
			//this.showEndRecommend(msgObject);
		}else if(msgmethod=='disconnect'){ //关播
			this.disconnect(msgObject);
		}else if(msgmethod=='changeMode'){ //切换模式
			this.changeMode(msgObject);
		}else if(msgmethod=='ShareScreen'){ //共享屏幕
			this.ShareScreen(msgObject);
		}else if(msgmethod=='setPPT'){ //PPT图片通知
			this.setPPT(msgObject);
		}else if(msgmethod=='roomShutup'){ //禁言
			this.roomShutup(msgObject);
		}
	},
	/*超管关闭房间*/
	superStopRoom:function()
	{
		setTimeout("window.location.href='/'",5000) //秒后执行
		layer.alert("该直播间涉嫌违规，已被停播", 
		{
			skin: 'layui-layer-molv' //样式类名
			,closeBtn: 0,
			shift: 5,
			icon:2
		}, function()
		{
			window.location.href="./";
		});
	},
	sendMsg:function(data){
		var msgaction = data.action;
		var _method_ = data._method_;
		if(msgaction==0){
			this.enterRoom(data);
		}else if(msgaction==1){
            this.sendChat(data);
		}
	},
	sendChat: function(data){
		chat.appendMsg(data);
	},
	enterRoom:function(data){
        Student.getNums();
	},
	disconnect:function(data){
        Student.getNums();
	},

	systemNot:function(data){

	},
    
    changeMode:function(data){
		var action = data.action;
		var _method_ = data._method_;
        if(_DATA.roominfo.user_type==1){
            return !1;
        }
        
        if(_DATA.roominfo.livetype!=5){
            return !1;
        }
		if(action==0){
            Live.showCamera();
        }
        
        if(action==1){
            Live.showWare();
        }
        
        if(action==2){
           
        }

	},
    ShareScreen:function(data){
		var action = data.action;
		var _method_ = data._method_;
		if(_DATA.roominfo.user_type==1){
            return !1;
        }
        
        if(action==0){
            Live.showScreen();
        }
        
        if(action==1){
            
        }

	},
    setPPT:function(data){
		var action = data.action;
		var _method_ = data._method_;
        if(_DATA.roominfo.user_type==1){
            return !1;
        }
		if(action==0){
            /* 添加 */
			var pptdata={
                id:data.pptid,
                thumb:data.thumb
            };
            ppt.addppts(pptdata);
		}
        
        if(action==1){
            /* 删除 */
            ppt.delppts(data.pptid);
		}
        
        if(action==2){
            var activeIndex=data.index;
            ppt.slideChange(activeIndex);
        }

	},
    roomShutup:function(data){
		var action = data.action;
		var _method_ = data._method_;
        
        _DATA.roominfo.isshup=action;
        
		if(action==0){
            $(":checkbox[id='stopspeak']").prop("checked",false);
		}
        
        if(action==1){
            $(":checkbox[id='stopspeak']").prop("checked",true);
		}


	},
}



