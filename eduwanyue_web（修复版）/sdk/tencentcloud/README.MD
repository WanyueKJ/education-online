# 简介
欢迎使用腾讯云开发者工具套件（SDK）3.0，SDK3.0是云 API3.0 平台的配套工具。目前已经支持cvm、vpc、cbs等产品，后续所有的云服务产品都会接入进来。新版SDK实现了统一化，具有各个语言版本的SDK使用方法相同，接口调用方式相同，统一的错误码和返回包格式这些优点。
为方便 PHP 开发者调试和接入腾讯云产品 API，这里向您介绍适用于 PHP 的腾讯云开发工具包，并提供首次使用开发工具包的简单示例。让您快速获取腾讯云 PHP SDK 并开始调用。
# 依赖环境
1. PHP 5.6.33 版本及以上
2. 从腾讯云控制台 开通相应产品
3. 获取 SecretID、SecretKey 以及调用地址（endpoint），endpoint 一般形式为*.tencentcloudapi.com，如CVM 的调用地址为 cvm.tencentcloudapi.com，具体参考各产品说明。

# 获取安装
安装 PHP SDK 前，先获取安全凭证。在第一次使用云API之前，用户首先需要在腾讯云控制台上申请安全凭证，安全凭证包括 SecretID 和 SecretKey， SecretID 是用于标识 API 调用者的身份，SecretKey是用于加密签名字符串和服务器端验证签名字符串的密钥。SecretKey 必须严格保管，避免泄露。
## 通过 Composer 安装
通过 Composer 获取安装是使用 PHP SDK 的推荐方法，Composer 是 PHP 的依赖管理工具，支持您项目所需的依赖项，并将其安装到项目中。关于 Composer 详细可参考 Composer 官网 。
1. 安装Composer：
    windows环境请访问[Composer官网](https://getcomposer.org/download/)下载安装包安装。
    
    unix环境在命令行中执行以下命令安装。
    > curl -sS https://getcomposer.org/installer | php
2. 在 composer.json 的 require 结构体中加入依赖。**注意这里的版本号只是举例,您可以在composer仓库上看到最新的版本号。**：
    > "tencentcloud/tencentcloud-sdk-php": "3.0.94"
3. 运行 composer install 下载安装 PHP SDK。
4. 添加以下引用代码，引用方法可参考示例。
    > require 'vendor/autoload.php';

## 通过源码包安装
1. 前往 [Github 代码托管地址](https://github.com/tencentcloud/tencentcloud-sdk-php) 克隆源码。注意：必须使用`git clone`的方式，直接下载源码包将不会包含必要的依赖文件。
2. 解压源码包到您项目合适的位置。
3. 添加以下引用代码，引用方法可参考示例。
    > require_once '../TCloudAutoLoader.php';
# 示例
以查询可用区接口为例:
```php
<?php
require_once '../../../TCloudAutoLoader.php';
// 导入对应产品模块的client
use TencentCloud\Cvm\V20170312\CvmClient;
// 导入要请求接口对应的Request类
use TencentCloud\Cvm\V20170312\Models\DescribeZonesRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
try {
    // 实例化一个证书对象，入参需要传入腾讯云账户secretId，secretKey
    $cred = new Credential("secretId", "secretKey");

    // # 实例化要请求产品(以cvm为例)的client对象
    $client = new CvmClient($cred, "ap-guangzhou");

    // 实例化一个请求对象
    $req = new DescribeZonesRequest();

    // 通过client对象调用想要访问的接口，需要传入请求对象
    $resp = $client->DescribeZones($req);

    print_r($resp->toJsonString());
}
catch(TencentCloudSDKException $e) {
    echo $e;
}
```

## 更多示例

您可以在[github](https://github.com/tencentcloud/tencentcloud-sdk-php)中examples目录下找到更详细的示例。

# 相关配置

## 代理

如果是有代理的环境下，需要设置系统环境变量 `https_proxy` ，否则可能无法正常调用，抛出连接超时的异常。

或者使用 GuzzleHttp 代理配置:
```php
$cred = new Credential("secretId", "secretKey");

$httpProfile = new HttpProfile();
$httpProfile->setProxy('https://ip:port');

$clientProfile = new ClientProfile();
$clientProfile->setHttpProfile($httpProfile);

$client = new OcrClient($cred, 'ap-beijing', $this->clientProfile);
```

## 证书问题

如果你的 PHP 环境证书有问题，可能会遇到报错，类似于 `cURL error 60: See http://curl.haxx.se/libcurl/c/libcurl-errors.html`，请尝试按如下步骤解决：

1. 到 [https://curl.haxx.se/ca/cacert.pem](https://curl.haxx.se/ca/cacert.pem) 下载证书文件 `cacert.pem`，将其保存到 PHP 安装路径下。
2. 编辑 `php.ini` 文件，删除 `curl.cainfo` 配置项前的分号注释符（;），值设置为保存的证书文件 `cacert.pem` 的绝对路径。
3. 重启依赖 PHP 的服务。

## php_curl 扩展

此 SDK 依赖的 GuzzleHttp 需要开启 php_curl 扩展，查看环境上的 php.ini 环境确认是否已启用，例如在 Linux 环境下，PHP 7.1 版本，托管在 apache 下的服务，可以打开 /etc/php/7.1/apache2/php.ini 中查看 extension=php_curl.dll 配置项是否已被注释，请删除此项配置前的注释符并重启 apache。

# 常见问题

## Web 访问异常

命令行下执行正常，但是放在 Web 服务器执行则报错：

cURL error 0: The cURL request was retried 3 times and did not succeed. The most likely reason for the failure is that cURL was unable to rewind the body of the request and subsequent retries resulted in the same error. Turn on the debug option to see what went wrong. See https://bugs.php.net/bug.php?id=47204 for more information. (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)

此问题出现情况不一。可以运行 `php -r "echo sys_get_temp_dir();"` 打印系统默认临时目录绝对路径，然后在 `php.ini` 配置 `sys_temp_dir` 为这个值尝试是否能解决。

# 旧版SDK
新版SDK兼容旧版SDK。旧版本的SDK存放于QcloudApi目录，但不再维护更新，推荐使用新版SDK。
