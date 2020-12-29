# php-qiniu

[七牛云存储](http://qiniu.com)非官方SDK，采用[PSR规范](https://github.com/hfcorriez/fig-standards)，支持[Composer](http://getcomposer.org)安装

`开发中...`

# 安装

添加 `"qiniu/qiniu": "*"` 到 [`composer.json`](http://getcomposer.org).

```
composer.phar install
```

# 引导

- [基本用法](#基本用法)
- [上传](#上传)
  - [上传文件](#上传文件)
  - [上传字符串](#上传字符串)
- [基本操作](#基本操作)
  - [查看文件](#查看文件)
  - [复制文件](#复制文件)
  - [移动文件](#移动文件)
  - [删除文件](#删除文件)
  - [筛选文件](#筛选文件)
- [批量操作](#批量操作)
- [图片查看](#图片查看)
  - [查看图片信息](#查看图片信息)
  - [查看图片Exif](#查看图片exif)
- [图片生成](#图片生成)
  - [缩略图生成](#缩略图生成)
  - [高级图片处理](#高级图片处理)

# 使用

## 基本用法

```php
require dirname(__DIR__) . '/vendor/autoload.php';

$client = \Qiniu\Qiniu::create(array(
    'access_key' => '<!>',
    'secret_key' => '<!>',
    'bucket'     => '<!>'
));

// 查看文件状态
$res = $client->stat('jobs.jpg');

print_r($res);
```

输出

```
Qiniu\Result Object
(
    [error] =>
    [data] => Array
            (
                [fsize] => 2425435
                [hash] => Fqng6_0hUF8Q-POxmWNi8JD9VRmy
                [mimeType] => image/jpeg
                [putTime] => 13707100228731119
                [url] => http://php-sdk.qiniudn.com/jobs.jpg
            )
    [debug] => Array
            (
                [log] => BDT;BDT;LBD
                [id] => zxwAAMj4OP6_7yET
            )
)
```

推荐用法

```php
if ($res->ok()) {
    // 成功上传或者操作

    // 获取返回的数据
    $data = $res->data; // Or $res->toArray()

    //做一些事情
} else {
    // 失败，获取失败信息
    $res->error;

    // 七牛的Debug头信息
    $res->debug;
}
```

## 上传

### 上传文件

```php
$res = $client->uploadFile('/home/hfcorriez/Code/jobs.jpg', 'jobs.jpg');

/*
$res->data:

Array
(
    [key] => 4276
    [hash] => FpLJ7yTujtJ85yU6QLA79ZaR3kKg
    [url] => http://php-sdk.qiniudn.com/jobs.jpg
)
*/
```

### 上传字符串

```php
$res = $client->upload('I am Qiniu SDK', 'readme.txt');

/*
$res->data:

Array
(
    [key] => 4276
    [hash] => FpLJ7yTujtJ85yU6QLA79ZaR3kKg
    [url] => http://php-sdk.qiniudn.com/readme.txt
)
*/
```

## 文件操作

### 查看文件

```php
// 查看文件状态
$res = $client->stat('jobs.jpg');

/*
$res->data:

[data] => Array
(
    [fsize] => 2425435
    [hash] => Fqng6_0hUF8Q-POxmWNi8JD9VRmy
    [mimeType] => image/jpeg
    [putTime] => 13787406554413228
    [url] => http://php-sdk.qiniudn.com/jobs.jpg
)
*/
```

### 复制文件

```php
$res = $client->copy('jobs.jpg', 'jobs.jpg.new');

/*
$res->data:

[data] => Array
(
    [url] => http://php-sdk.qiniudn.com/jobs.jpg.new
)
*/
```

### 移动文件

```php
$res = $client->move('jobs.jpg.new', 'jobs.jpg');

/*
$res->data:

[data] => Array
(
    [url] => http://php-sdk.qiniudn.com/jobs.jpg
)
*/
```

### 删除文件

```php
$res = $client->delete('jobs.jpg');

/*
$res->data:

[data] => Array
(
    [url] => http://php-sdk.qiniudn.com/jobs.jpg
)
*/
```

### 筛选文件

```php
$res = $client->ls();

/*
$res->data:

Array
(
    [items] => Array
    (
        [0] => Array
        (
            [fsize] => 2425435
            [putTime] => 13787406554413228
            [key] => jobs.jpg
            [hash] => Fqng6_0hUF8Q-POxmWNi8JD9VRmy
            [mimeType] => image/jpeg
        )
    )
)
*/
```

## 批量操作

```php
$res = $client->batch()
    ->stat('jobs.jpg')
    ->copy('jobs.jpg', 'jobs.jpg.new')
    ->move('jobs.jpg.new', 'jobs.jpg.old')
    ->delete('jobs.jpg.old')
    ->exec();

/*
$res:

Qiniu\Result Object
(
    [error] =>
    [data] => Array
        (
            [0] => Qiniu\Result Object
                (
                    [error] =>
                    [data] => Array
                        (
                            [fsize] => 2425435
                            [hash] => Fqng6_0hUF8Q-POxmWNi8JD9VRmy
                            [mimeType] => image/jpeg
                            [putTime] => 13787430682676023
                            [url] => http://php-sdk.qiniudn.com/jobs.jpg
                        )

                    [response] =>
                    [debug] => Array
                        (
                            [log] => MQ;MC/404;RS.mcd
                            [id] => u00AAFaxXKXYfiIT
                        )

                )

            [1] => Qiniu\Result Object
                (
                    [error] =>
                    [data] => Array
                        (
                            [url] => http://php-sdk.qiniudn.com/jobs.jpg
                        )

                    [response] =>
                    [debug] => Array
                        (
                            [log] => MQ;MC/404;RS.mcd
                            [id] => u00AAFaxXKXYfiIT
                        )

                )

            [2] => Qiniu\Result Object
                (
                    [error] =>
                    [data] => Array
                        (
                            [url] => http://php-sdk.qiniudn.com/jobs.jpg.new
                        )

                    [response] =>
                    [debug] => Array
                        (
                            [log] => MQ;MC/404;RS.mcd
                            [id] => u00AAFaxXKXYfiIT
                        )

                )

            [3] => Qiniu\Result Object
                (
                    [error] =>
                    [data] => Array
                        (
                            [url] => http://php-sdk.qiniudn.com/jobs.jpg.old
                        )

                    [response] =>
                    [debug] => Array
                        (
                            [log] => MQ;MC/404;RS.mcd
                            [id] => u00AAFaxXKXYfiIT
                        )

                )

        )
        ...
 )
*/
```

## 图片查看

### 查看图片信息

查看图片的信息，信息参数介绍参见[七牛官方文档](http://docs.qiniu.com/api/v6/image-process.html#imageInfo)

```php
$res = $client->imageInfo('qn.jpeg');

/*
$res->data:

Array
(
    [format] => jpeg
    [width] => 640
    [height] => 423
    [colorModel] => ycbcr
)
*/
```

### 查看图片Exif

EXIF (Exchangeable image file format),是专门为数码相机的照片设定的可交换图像文件格式。详情参见[EXIF](http://zh.wikipedia.org/wiki/EXIF)

```php
$res = $client->exif('qn.jpeg');

/*
$res->data:

Array
(
    [ApertureValue] => Array
        (
            [val] => 2.53 EV (f/2.4)
            [type] => 5
        )

    [BrightnessValue] => Array
        (
            [val] => 5.31 EV (135.85 cd/m^2)
            [type] => 10
        )
    ...
)
*/
```

## 图片生成

### 缩略图生成

参数列表请参照[七牛官方文档](http://docs.qiniu.com/api/v6/image-process.html#imageMogr)

```php
$url = $client->imageView('jobs.jpg', array("mode" => 1, "width" => 100, "height" => 100, "q" => 50, "format" => 'png'));

/*
$url:

http://php-sdk.qiniudn.com/jobs.jpg?imageView/1/w/100/h/100/q/50/format/png
*/
```

### 高级图片处理

包括(缩略、裁剪、旋转、转化)，参数列表请参照[七牛官方文档](http://docs.qiniu.com/api/v6/image-process.html#imageMogr)

```php
$res = $client->imageMogr('jobs.jpg', array(
    "thumbnail" => '!50p',
    "gravity"   => 'NorthWest',
    "quality"   => 50,
    "rotate"    => -50,
    "format"    => 'gif'
));

/*
$url:

http://php-sdk.qiniudn.com/jobs.jpg?imageMogr/v2/auto-orient/thumbnail/!50p/gravity/NorthWest/quality/50/rotate/-50/format/gif
*/
```

# License

(The MIT License)

Copyright (c) 2012 hfcorriez &lt;hfcorriez@gmail.com&gt;

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.