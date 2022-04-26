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
namespace TencentCloud\Gse\V20191112\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeInstances请求参数结构体
 *
 * @method string getFleetId() 获取服务部署ID
 * @method void setFleetId(string $FleetId) 设置服务部署ID
 * @method string getInstanceId() 获取实例ID
 * @method void setInstanceId(string $InstanceId) 设置实例ID
 * @method integer getOffset() 获取结果返回最大数量
 * @method void setOffset(integer $Offset) 设置结果返回最大数量
 * @method integer getLimit() 获取返回结果偏移
 * @method void setLimit(integer $Limit) 设置返回结果偏移
 */
class DescribeInstancesRequest extends AbstractModel
{
    /**
     * @var string 服务部署ID
     */
    public $FleetId;

    /**
     * @var string 实例ID
     */
    public $InstanceId;

    /**
     * @var integer 结果返回最大数量
     */
    public $Offset;

    /**
     * @var integer 返回结果偏移
     */
    public $Limit;

    /**
     * @param string $FleetId 服务部署ID
     * @param string $InstanceId 实例ID
     * @param integer $Offset 结果返回最大数量
     * @param integer $Limit 返回结果偏移
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
        if (array_key_exists("FleetId",$param) and $param["FleetId"] !== null) {
            $this->FleetId = $param["FleetId"];
        }

        if (array_key_exists("InstanceId",$param) and $param["InstanceId"] !== null) {
            $this->InstanceId = $param["InstanceId"];
        }

        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }
    }
}
