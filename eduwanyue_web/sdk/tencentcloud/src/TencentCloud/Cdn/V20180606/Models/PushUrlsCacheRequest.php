<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Cdn\V20180606\Models;
use TencentCloud\Common\AbstractModel;

/**
 * PushUrlsCache请求参数结构体
 *
 * @method array getUrls() 获取URL 列表，需要包含协议头部 http:// 或 https://
 * @method void setUrls(array $Urls) 设置URL 列表，需要包含协议头部 http:// 或 https://
 * @method string getUserAgent() 获取指定预热请求回源时 HTTP 请求的 User-Agent 头部
默认为 TencentCdn
 * @method void setUserAgent(string $UserAgent) 设置指定预热请求回源时 HTTP 请求的 User-Agent 头部
默认为 TencentCdn
 * @method string getArea() 获取预热生效区域
mainland：预热至境内节点
overseas：预热至境外节点
global：预热全球节点
不填充情况下，默认为 mainland， URL 中域名必须在对应区域启用了加速服务才能提交对应区域的预热任务
 * @method void setArea(string $Area) 设置预热生效区域
mainland：预热至境内节点
overseas：预热至境外节点
global：预热全球节点
不填充情况下，默认为 mainland， URL 中域名必须在对应区域启用了加速服务才能提交对应区域的预热任务
 */
class PushUrlsCacheRequest extends AbstractModel
{
    /**
     * @var array URL 列表，需要包含协议头部 http:// 或 https://
     */
    public $Urls;

    /**
     * @var string 指定预热请求回源时 HTTP 请求的 User-Agent 头部
默认为 TencentCdn
     */
    public $UserAgent;

    /**
     * @var string 预热生效区域
mainland：预热至境内节点
overseas：预热至境外节点
global：预热全球节点
不填充情况下，默认为 mainland， URL 中域名必须在对应区域启用了加速服务才能提交对应区域的预热任务
     */
    public $Area;

    /**
     * @param array $Urls URL 列表，需要包含协议头部 http:// 或 https://
     * @param string $UserAgent 指定预热请求回源时 HTTP 请求的 User-Agent 头部
默认为 TencentCdn
     * @param string $Area 预热生效区域
mainland：预热至境内节点
overseas：预热至境外节点
global：预热全球节点
不填充情况下，默认为 mainland， URL 中域名必须在对应区域启用了加速服务才能提交对应区域的预热任务
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Urls",$param) and $param["Urls"] !== null) {
            $this->Urls = $param["Urls"];
        }

        if (array_key_exists("UserAgent",$param) and $param["UserAgent"] !== null) {
            $this->UserAgent = $param["UserAgent"];
        }

        if (array_key_exists("Area",$param) and $param["Area"] !== null) {
            $this->Area = $param["Area"];
        }
    }
}
