<?php

return [
    'level'=>['error'],
    // 日志记录方式，支持 file socket 或者自定义驱动类
    'type' => 'File',
    //日志保存目录
    'path' => '../logs/',
    //单个日志文件的大小限制，超过后会自动记录到第二个文件
    'file_size'     =>2097152,
    //日志的时间格式，默认是` c `
    'time_format'   =>'c'
];