var schedule = require("node-schedule");
var request  = require('request');
var config   = require('./config.js');
var site=config['WEBSITE'];


function FormatNowDate(){
	var mDate = new Date();
	var Y = mDate.getFullYear();
	var M = mDate.getMonth()+1;
	var D = mDate.getDate();
	var H = mDate.getHours();
	var i = mDate.getMinutes();
	var s = mDate.getSeconds();
	return Y +'-' + M + '-' + D + ' ' + H + ':' + i + ':' + s;
}

//定时处理课程直播
var rule2 = new schedule.RecurrenceRule();

var times2 = [];

for(var i=0; i<60; i++){
    times2.push(i);
}

rule2.second = times2;
// console.log(times);

var j2 = schedule.scheduleJob(rule2, function(){
    // time=FormatNowDate();
    // console.log("执行任务:"+time);
    upCourseLive();
});


function upCourseLive(){
    // var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/appapi/Handelcourse/upCourseLive",function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
    });
}

//定时处理课时直播
var rule3 = new schedule.RecurrenceRule();
var times3 = [];
for(var i=0; i<60; i++){
    times3.push(i);
}
rule3.second = times3;
// console.log(times);
var j3 = schedule.scheduleJob(rule3, function(){
    // time=FormatNowDate();
    // console.log("执行任务:"+time);
    upLessonLive();
});

function upLessonLive(){
    // var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/appapi/Handelcourse/upLessonLive",function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
    });
}

//定时处理课程推送
var rule4 = new schedule.RecurrenceRule();
var times4 = [0,30];

rule4.second = times4;
// console.log(times);
var j4 = schedule.scheduleJob(rule4, function(){
    // time=FormatNowDate();
    // console.log("执行任务:"+time);
    sendCourse();
});

function sendCourse(){
    // var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/appapi/Handelcourse/sendCourse",function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
    });
}