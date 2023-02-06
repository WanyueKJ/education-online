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
namespace TencentCloud\Gaap\V20180529\Models;
use TencentCloud\Common\AbstractModel;

/**
 * RealServerBindSetReq
 *
 * @method string getRealServerId() 获取源站id
 * @method void setRealServerId(string $RealServerId) 设置源站id
 * @method integer getRealServerPort() 获取源站端口
 * @method void setRealServerPort(integer $RealServerPort) 设置源站端口
 * @method string getRealServerIP() 获取源站IP
 * @method void setRealServerIP(string $RealServerIP) 设置源站IP
 * @method integer getRealServerWeight() 获取源站权重
 * @method void setRealServerWeight(integer $RealServerWeight) 设置源站权重
 */
class RealServerBindSetReq extends AbstractModel
{
    /**
     * @var string 源站id
     */
    public $RealServerId;

    /**
     * @var integer 源站端口
     */
    public $RealServerPort;

    /**
     * @var string 源站IP
     */
    public $RealServerIP;

    /**
     * @var integer 源站权重
     */
    public $RealServerWeight;

    /**
     * @param string $RealServerId 源站id
     * @param integer $RealServerPort 源站端口
     * @param string $RealServerIP 源站IP
     * @param integer $RealServerWeight 源站权重
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
        if (array_key_exists("RealServerId",$param) and $param["RealServerId"] !== null) {
            $this->RealServerId = $param["RealServerId"];
        }

        if (array_key_exists("RealServerPort",$param) and $param["RealServerPort"] !== null) {
            $this->RealServerPort = $param["RealServerPort"];
        }

        if (array_key_exists("RealServerIP",$param) and $param["RealServerIP"] !== null) {
            $this->RealServerIP = $param["RealServerIP"];
        }

        if (array_key_exists("RealServerWeight",$param) and $param["RealServerWeight"] !== null) {
            $this->RealServerWeight = $param["RealServerWeight"];
        }
    }
}
