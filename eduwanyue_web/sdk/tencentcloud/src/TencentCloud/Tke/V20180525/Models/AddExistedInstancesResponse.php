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
namespace TencentCloud\Tke\V20180525\Models;
use TencentCloud\Common\AbstractModel;

/**
 * AddExistedInstances返回参数结构体
 *
 * @method array getFailedInstanceIds() 获取失败的节点ID
 * @method void setFailedInstanceIds(array $FailedInstanceIds) 设置失败的节点ID
 * @method array getSuccInstanceIds() 获取成功的节点ID
 * @method void setSuccInstanceIds(array $SuccInstanceIds) 设置成功的节点ID
 * @method array getTimeoutInstanceIds() 获取超时未返回出来节点的ID(可能失败，也可能成功)
 * @method void setTimeoutInstanceIds(array $TimeoutInstanceIds) 设置超时未返回出来节点的ID(可能失败，也可能成功)
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */
class AddExistedInstancesResponse extends AbstractModel
{
    /**
     * @var array 失败的节点ID
     */
    public $FailedInstanceIds;

    /**
     * @var array 成功的节点ID
     */
    public $SuccInstanceIds;

    /**
     * @var array 超时未返回出来节点的ID(可能失败，也可能成功)
     */
    public $TimeoutInstanceIds;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param array $FailedInstanceIds 失败的节点ID
     * @param array $SuccInstanceIds 成功的节点ID
     * @param array $TimeoutInstanceIds 超时未返回出来节点的ID(可能失败，也可能成功)
     * @param string $RequestId 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("FailedInstanceIds",$param) and $param["FailedInstanceIds"] !== null) {
            $this->FailedInstanceIds = $param["FailedInstanceIds"];
        }

        if (array_key_exists("SuccInstanceIds",$param) and $param["SuccInstanceIds"] !== null) {
            $this->SuccInstanceIds = $param["SuccInstanceIds"];
        }

        if (array_key_exists("TimeoutInstanceIds",$param) and $param["TimeoutInstanceIds"] !== null) {
            $this->TimeoutInstanceIds = $param["TimeoutInstanceIds"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
