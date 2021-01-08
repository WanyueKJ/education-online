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
namespace TencentCloud\Vpc\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateCustomerGateway请求参数结构体
 *
 * @method string getCustomerGatewayName() 获取对端网关名称，可任意命名，但不得超过60个字符。
 * @method void setCustomerGatewayName(string $CustomerGatewayName) 设置对端网关名称，可任意命名，但不得超过60个字符。
 * @method string getIpAddress() 获取对端网关公网IP。
 * @method void setIpAddress(string $IpAddress) 设置对端网关公网IP。
 */
class CreateCustomerGatewayRequest extends AbstractModel
{
    /**
     * @var string 对端网关名称，可任意命名，但不得超过60个字符。
     */
    public $CustomerGatewayName;

    /**
     * @var string 对端网关公网IP。
     */
    public $IpAddress;

    /**
     * @param string $CustomerGatewayName 对端网关名称，可任意命名，但不得超过60个字符。
     * @param string $IpAddress 对端网关公网IP。
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
        if (array_key_exists("CustomerGatewayName",$param) and $param["CustomerGatewayName"] !== null) {
            $this->CustomerGatewayName = $param["CustomerGatewayName"];
        }

        if (array_key_exists("IpAddress",$param) and $param["IpAddress"] !== null) {
            $this->IpAddress = $param["IpAddress"];
        }
    }
}
