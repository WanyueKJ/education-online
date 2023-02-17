# 七牛扩展
PhalApi 2.x扩展类库，基于Qiniu的七牛CDN扩展。

## 安装和配置
修改项目下的composer.json文件，并添加：  
```
    "phalapi/qiniu":"dev-master"
```
然后执行```composer update```。  

安装成功后，添加以下配置到/path/to/phalapi/config/app.php文件：  
```php
    /**
     * 七牛相关配置
     */
    'Qiniu' =>  array(
        //统一的key
        'access_key' => '*****',
        'secret_key' => '****',
        //自定义配置的空间
        'space_bucket' => '自定义配置的空间',
        'space_host' => 'http://XXXXX.qiniudn.com', // 如果有配置此项，则优先使用此域名
        'preffix' => '', // 上传文件名前缀
        //'upload_url' => '', // 提示：incorrect region, please use up-z2.qiniup.com，请配置此域名
    ),
```
并根据自己的情况修改填充。 

## 注册
在/path/to/phalapi/config/di.php文件中，注册：  
```php
$di->qiniu = function() {
        return new \PhalApi\Qiniu\Lite();
};
```

## 使用
第一种使用方式：使用七牛上传本地文件：
```php
$url = \PhalApi\DI()->qiniu->uploadFile($file);
```

第二种使用方式：或者，直接使用已经提供的默认上传接口。在composer.json中追加配置：
```
"autoload": {
    "psr-4": {
        "Qiniu\\": "vendor/phalapi/qiniu/src/qiniu"
    }
}
```
打开在线接口文档列表，即可看到七牛的接口。  

