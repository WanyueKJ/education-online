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
namespace TencentCloud\Scf\V20180416\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 函数列表
 *
 * @method string getModTime() 获取修改时间
 * @method void setModTime(string $ModTime) 设置修改时间
 * @method string getAddTime() 获取创建时间
 * @method void setAddTime(string $AddTime) 设置创建时间
 * @method string getRuntime() 获取运行时
 * @method void setRuntime(string $Runtime) 设置运行时
 * @method string getFunctionName() 获取函数名称
 * @method void setFunctionName(string $FunctionName) 设置函数名称
 * @method string getFunctionId() 获取函数ID
 * @method void setFunctionId(string $FunctionId) 设置函数ID
 * @method string getNamespace() 获取命名空间
 * @method void setNamespace(string $Namespace) 设置命名空间
 * @method string getStatus() 获取函数状态
 * @method void setStatus(string $Status) 设置函数状态
 * @method string getStatusDesc() 获取函数状态详情
 * @method void setStatusDesc(string $StatusDesc) 设置函数状态详情
 * @method string getDescription() 获取函数描述
 * @method void setDescription(string $Description) 设置函数描述
 * @method array getTags() 获取函数标签
 * @method void setTags(array $Tags) 设置函数标签
 * @method string getType() 获取函数类型，取值为 HTTP 或者 Event
 * @method void setType(string $Type) 设置函数类型，取值为 HTTP 或者 Event
 */
class FunctionInfo extends AbstractModel
{
    /**
     * @var string 修改时间
     */
    public $ModTime;

    /**
     * @var string 创建时间
     */
    public $AddTime;

    /**
     * @var string 运行时
     */
    public $Runtime;

    /**
     * @var string 函数名称
     */
    public $FunctionName;

    /**
     * @var string 函数ID
     */
    public $FunctionId;

    /**
     * @var string 命名空间
     */
    public $Namespace;

    /**
     * @var string 函数状态
     */
    public $Status;

    /**
     * @var string 函数状态详情
     */
    public $StatusDesc;

    /**
     * @var string 函数描述
     */
    public $Description;

    /**
     * @var array 函数标签
     */
    public $Tags;

    /**
     * @var string 函数类型，取值为 HTTP 或者 Event
     */
    public $Type;

    /**
     * @param string $ModTime 修改时间
     * @param string $AddTime 创建时间
     * @param string $Runtime 运行时
     * @param string $FunctionName 函数名称
     * @param string $FunctionId 函数ID
     * @param string $Namespace 命名空间
     * @param string $Status 函数状态
     * @param string $StatusDesc 函数状态详情
     * @param string $Description 函数描述
     * @param array $Tags 函数标签
     * @param string $Type 函数类型，取值为 HTTP 或者 Event
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
        if (array_key_exists("ModTime",$param) and $param["ModTime"] !== null) {
            $this->ModTime = $param["ModTime"];
        }

        if (array_key_exists("AddTime",$param) and $param["AddTime"] !== null) {
            $this->AddTime = $param["AddTime"];
        }

        if (array_key_exists("Runtime",$param) and $param["Runtime"] !== null) {
            $this->Runtime = $param["Runtime"];
        }

        if (array_key_exists("FunctionName",$param) and $param["FunctionName"] !== null) {
            $this->FunctionName = $param["FunctionName"];
        }

        if (array_key_exists("FunctionId",$param) and $param["FunctionId"] !== null) {
            $this->FunctionId = $param["FunctionId"];
        }

        if (array_key_exists("Namespace",$param) and $param["Namespace"] !== null) {
            $this->Namespace = $param["Namespace"];
        }

        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("StatusDesc",$param) and $param["StatusDesc"] !== null) {
            $this->StatusDesc = $param["StatusDesc"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }

        if (array_key_exists("Tags",$param) and $param["Tags"] !== null) {
            $this->Tags = [];
            foreach ($param["Tags"] as $key => $value){
                $obj = new Tag();
                $obj->deserialize($value);
                array_push($this->Tags, $obj);
            }
        }

        if (array_key_exists("Type",$param) and $param["Type"] !== null) {
            $this->Type = $param["Type"];
        }
    }
}
