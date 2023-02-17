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
