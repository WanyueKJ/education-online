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
namespace TencentCloud\Tsf\V20180326\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DeployGroup请求参数结构体
 *
 * @method string getGroupId() 获取部署组ID
 * @method void setGroupId(string $GroupId) 设置部署组ID
 * @method string getPkgId() 获取程序包ID
 * @method void setPkgId(string $PkgId) 设置程序包ID
 * @method string getStartupParameters() 获取部署组启动参数
 * @method void setStartupParameters(string $StartupParameters) 设置部署组启动参数
 */
class DeployGroupRequest extends AbstractModel
{
    /**
     * @var string 部署组ID
     */
    public $GroupId;

    /**
     * @var string 程序包ID
     */
    public $PkgId;

    /**
     * @var string 部署组启动参数
     */
    public $StartupParameters;

    /**
     * @param string $GroupId 部署组ID
     * @param string $PkgId 程序包ID
     * @param string $StartupParameters 部署组启动参数
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
        if (array_key_exists("GroupId",$param) and $param["GroupId"] !== null) {
            $this->GroupId = $param["GroupId"];
        }

        if (array_key_exists("PkgId",$param) and $param["PkgId"] !== null) {
            $this->PkgId = $param["PkgId"];
        }

        if (array_key_exists("StartupParameters",$param) and $param["StartupParameters"] !== null) {
            $this->StartupParameters = $param["StartupParameters"];
        }
    }
}
