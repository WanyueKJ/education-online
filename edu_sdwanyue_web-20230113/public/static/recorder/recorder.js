(function(exports){
    //公共方法
    var Util = {
        //初始化
        init: function(){
            navigator.getUserMedia = navigator.getUserMedia ||
                                     navigator.webkitGetUserMedia ||
                                     navigator.mozGetUserMedia ||
                                     navigator.msGetUserMedia;

            window.AudioContext = window.AudioContext ||
                                  window.webkitAudioContext;
        },
        //日志
        log: function(){
            console.log.apply(console,arguments);
        }
    };
    //构造函数
    var Recorder = function(config){

        var _this = this;
        config = config || {}; //初始化配置对象
        config.sampleRate = config.sampleRate || 44100; //采样频率，默认为44100Hz(标准MP3采样率)
        config.bitRate = config.bitRate || 128; //比特率，默认为128kbps(标准MP3质量)

        Util.init();

        if(navigator.getUserMedia){
            navigator.getUserMedia({
                audio: true //配置对象
            },
            function(stream){ //成功回调
                var context = new AudioContext(),
                    microphone = context.createMediaStreamSource(stream), //媒体流音频源
                    processor = context.createScriptProcessor(16384,1,1), //js音频处理器
                    successCallback, errorCallback;

                config.sampleRate = context.sampleRate;

                processor.onaudioprocess = function(event){
                    //监听音频录制过程
                    var array = event.inputBuffer.getChannelData(0);
                    realTimeWorker.postMessage({ cmd: 'encode', buf: array });
                };

                var realTimeWorker = new Worker('/static/recorder/worker.js'); //开启后台线程
                realTimeWorker.onmessage = function(e){ //主线程监听后台线程，实时通信
                    switch(e.data.cmd){
                        case 'init':
                            Util.log('初始化成功');
                            if(config.success){
                                config.success();
                            }
                            break;
                        case 'end':
                            if(successCallback){
                                var blob = new Blob(e.data.buf, { type: 'audio/mp3' });
                                successCallback(blob);
                                Util.log('MP3大小：' + blob.size + '%cB', 'color:#0000EE');
                            }
                            break;
                        case 'error':
                            Util.log('错误信息：' + e.data.error);
                            if(errorCallback){
                                errorCallback(e.data.error);
                            }
                            break;
                        default:
                            Util.log('未知信息：' + e.data);
                    }
                };
                //接口列表
                //开始录音
                _this.start = function(){
                    if(processor && microphone){
                        microphone.connect(processor);
                        processor.connect(context.destination);
                        Util.log('开始录音');
                    }
                };
                //结束录音
                _this.stop = function(){
                    if(processor && microphone){
                        microphone.disconnect();
                        processor.disconnect();
                        Util.log('录音结束');
                    }
                };
                //获取blob格式录音文件
                _this.getBlob = function(onSuccess, onError){
                    successCallback = onSuccess;
                    errorCallback = onError;
                    realTimeWorker.postMessage({ cmd: 'finish' });
                };

                realTimeWorker.postMessage({
                    cmd: 'init',
                    config: {
                        sampleRate: config.sampleRate,
                        bitRate: config.bitRate
                    }
                });
            },
            function(error){ //失败回调
                var msg;
                switch(error.code || error.name){
                    case 'PermissionDeniedError':
                    case 'PERMISSION_DENIED':
                    case 'NotAllowedError':
                        msg = '用户拒绝访问麦克风';
                        break;
                    case 'NOT_SUPPORTED_ERROR':
                    case 'NotSupportedError':
                        msg = '浏览器不支持麦克风';
                        break;
                    case 'MANDATORY_UNSATISFIED_ERROR':
                    case 'MandatoryUnsatisfiedError':
                        msg = '找不到麦克风设备';
                        break;
                    default:
                        msg = '无法打开麦克风，异常信息:' + (error.code || error.name);
                        break;
                }
                Util.log(msg);
                if(config.error){
                    config.error(msg);
                }
            });
        }else{
            Util.log('当前浏览器不支持录音功能');
            if(config.fix){
                config.fix('当前浏览器不支持录音功能');
            }
        }

    };
    //模块接口
    exports.Recorder = Recorder;
})(window);
