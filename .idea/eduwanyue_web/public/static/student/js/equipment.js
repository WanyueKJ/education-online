var selectedMicrophoneId=null;
var selectedCameraId=null;
var Agorastream = null;
var audioLevelTime = null;
var check_index = null;

function getDevices() {
    AgoraRTC.getDevices(function (devices) {
        // console.log(devices);
    
        var options = {audiooutput: '', audioinput: '', videoinput: ''};
        for (var i = 0; i < devices.length; i++) {
            var device = devices[i];
            //console.log(device);
            var label=device.label;
            if(label==''){
                label="设备" + i;
            }
            options[device.kind] = options[device.kind] + '<option value="'+device.deviceId+'">'+label+'</option>';
        }
        //return ;
        $("select[name='audioinput']").html(options.audioinput);
        $("select[name='videoinput']").html(options.videoinput);

        checkDevice();
    });
};


function checkDevice() {
    clearCheck();
    
    var uid = Math.floor(Math.random() * 10000);
    selectedMicrophoneId = $("select[name='audioinput']").val();
    selectedCameraId = $("select[name='videoinput']").val();
    
    // console.log(selectedMicrophoneId);
    // console.log(selectedCameraId);
    if(!selectedMicrophoneId || !selectedCameraId || selectedMicrophoneId=='' || selectedCameraId==''){
        layer.msg('请检查麦克风和摄像头是否正常2');
        return !1;
    }

    Agorastream = AgoraRTC.createStream({
        streamID: uid,
        audio: true,
        microphoneId: selectedMicrophoneId,
        video: true,
        cameraId: selectedCameraId,
        screen: false
    });
    Agorastream.init(function () {
        console.log("init local stream success");
        Agorastream.play("device_video",{fit: "contain"},function(errState){
            
        });
        
        audioLevelTime=setInterval(function() {
            var audioLevel = Agorastream.getAudioLevel();
            
            var audio_Level=audioLevel * 12;
            $('#device_audio li').each(function(){
                var _that=$(this);
                var index=_that.index();
                var nums=index+1;
                if(nums<audio_Level){
                    _that.addClass('on');
                }else{
                    _that.removeClass('on');
                }
            })
    
        }, 100)
        
    }, function (err) {
        layer.msg('请检查麦克风和摄像头是否正常');
        //console.error("init local Agorastream failed ", err);
    })
};
function clearCheck(){
    $('#device_video').html('');
    Agorastream && Agorastream.close();
    audioLevelTime && clearInterval(audioLevelTime);
    audioLevelTime=null;
}
function closeCheck(){
    layer.close(check_index);
    clearCheck();
}
function stratCheck(){
    check_index=layer.open({
        type: 1,
        title:false,
        closeBtn:0,
        area: ['520px', '520px'], //宽高
        content: $('.checkDevices')
    });
    getDevices();
    $("select[name='audioinput'], select[name='videoinput']").change(function () {
        checkDevice();
    });
}
(function(){
    Wind.css('layer');
    Wind.use('layer');
    
    $(".js_check_camera").click(function(){
        var _that=$(this);
        if(_that.hasClass('device_btn_no')){
            $('.device_top li').eq(0).removeClass('on ok').addClass('no');
            $('.js_point_camera').addClass('no');
            $('.js_txt_camera').text('异常');
        }else{
            $('.device_top li').eq(0).removeClass('on no').addClass('ok');
            $('.js_point_camera').removeClass('no');
            $('.js_txt_camera').text('正常');
        }
        $('.device_top li').eq(0).find('.device_t').text('摄像头');
        $('.device_top li').eq(1).find('.device_t').text('麦克风检测中');
        
        $('.device_top li').eq(1).removeClass('ok no').addClass('on');
        $('.device_bd').hide().eq(1).show();
    })
    
    $(".js_check_mic").click(function(){
        var _that=$(this);
        if(_that.hasClass('device_btn_no')){
            $('.device_top li').eq(1).removeClass('on ok').addClass('no');
            $('.js_point_mic').addClass('no');
            $('.js_txt_mic').text('异常');
        }else{
            $('.device_top li').eq(1).removeClass('on no').addClass('ok');
            $('.js_point_mic').removeClass('no');
            $('.js_txt_mic').text('正常');
        }
        $('.device_top li').eq(1).find('.device_t').text('麦克风');
        
        $('.device_top li').eq(2).removeClass('ok no').addClass('on');
        $('.device_bd').hide().eq(2).show();
        
        clearCheck();
    })
    
    $(".js_check_reset").click(function(){
        
        $('.device_top li').eq(0).find('.device_t').text('摄像头检测中');
        
        $('.device_top li').eq(0).removeClass('ok no').addClass('on');
        $('.device_bd').hide().eq(0).show();
        
        getDevices();
    })
    
    $(".js_check_ok").click(function(){
        closeCheck();
    })
})()





