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
namespace TencentCloud\Ecm\V20190719\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 实例可用区及对应的实例创建数目及运营商的组合；
 *
 * @method string getZone() 获取创建实例的可用区。
 * @method void setZone(string $Zone) 设置创建实例的可用区。
 * @method integer getInstanceCount() 获取在当前可用区欲创建的实例数目。
 * @method void setInstanceCount(integer $InstanceCount) 设置在当前可用区欲创建的实例数目。
 * @method string getISP() 获取运营商。
 * @method void setISP(string $ISP) 设置运营商。
 */
class ZoneInstanceCountISP extends AbstractModel
{
    /**
     * @var string 创建实例的可用区。
     */
    public $Zone;

    /**
     * @var integer 在当前可用区欲创建的实例数目。
     */
    public $InstanceCount;

    /**
     * @var string 运营商。
     */
    public $ISP;

    /**
     * @param string $Zone 创建实例的可用区。
     * @param integer $InstanceCount 在当前可用区欲创建的实例数目。
     * @param string $ISP 运营商。
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
        if (array_key_exists("Zone",$param) and $param["Zone"] !== null) {
            $this->Zone = $param["Zone"];
        }

        if (array_key_exists("InstanceCount",$param) and $param["InstanceCount"] !== null) {
            $this->InstanceCount = $param["InstanceCount"];
        }

        if (array_key_exists("ISP",$param) and $param["ISP"] !== null) {
            $this->ISP = $param["ISP"];
        }
    }
}
