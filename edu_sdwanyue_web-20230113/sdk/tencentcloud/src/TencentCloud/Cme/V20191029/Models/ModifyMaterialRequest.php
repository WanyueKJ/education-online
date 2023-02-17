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
namespace TencentCloud\Cme\V20191029\Models;
use TencentCloud\Common\AbstractModel;

/**
 * ModifyMaterial请求参数结构体
 *
 * @method string getPlatform() 获取平台名称，指定访问的平台。
 * @method void setPlatform(string $Platform) 设置平台名称，指定访问的平台。
 * @method string getMaterialId() 获取素材 Id。
 * @method void setMaterialId(string $MaterialId) 设置素材 Id。
 * @method Entity getOwner() 获取素材归属。
 * @method void setOwner(Entity $Owner) 设置素材归属。
 * @method string getName() 获取素材名称，不能超过30个字符。
 * @method void setName(string $Name) 设置素材名称，不能超过30个字符。
 * @method array getTags() 获取素材标签，单个标签长度不能超过10个字符，数组长度不能超过10。
 * @method void setTags(array $Tags) 设置素材标签，单个标签长度不能超过10个字符，数组长度不能超过10。
 * @method string getClassPath() 获取素材分类路径，例如填写"/a/b"，则代表该素材存储的路径为"/a/b"。
 * @method void setClassPath(string $ClassPath) 设置素材分类路径，例如填写"/a/b"，则代表该素材存储的路径为"/a/b"。
 * @method string getOperator() 获取操作者。填写用户的 Id，用于标识调用者及校验操作权限。
 * @method void setOperator(string $Operator) 设置操作者。填写用户的 Id，用于标识调用者及校验操作权限。
 */
class ModifyMaterialRequest extends AbstractModel
{
    /**
     * @var string 平台名称，指定访问的平台。
     */
    public $Platform;

    /**
     * @var string 素材 Id。
     */
    public $MaterialId;

    /**
     * @var Entity 素材归属。
     */
    public $Owner;

    /**
     * @var string 素材名称，不能超过30个字符。
     */
    public $Name;

    /**
     * @var array 素材标签，单个标签长度不能超过10个字符，数组长度不能超过10。
     */
    public $Tags;

    /**
     * @var string 素材分类路径，例如填写"/a/b"，则代表该素材存储的路径为"/a/b"。
     */
    public $ClassPath;

    /**
     * @var string 操作者。填写用户的 Id，用于标识调用者及校验操作权限。
     */
    public $Operator;

    /**
     * @param string $Platform 平台名称，指定访问的平台。
     * @param string $MaterialId 素材 Id。
     * @param Entity $Owner 素材归属。
     * @param string $Name 素材名称，不能超过30个字符。
     * @param array $Tags 素材标签，单个标签长度不能超过10个字符，数组长度不能超过10。
     * @param string $ClassPath 素材分类路径，例如填写"/a/b"，则代表该素材存储的路径为"/a/b"。
     * @param string $Operator 操作者。填写用户的 Id，用于标识调用者及校验操作权限。
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
        if (array_key_exists("Platform",$param) and $param["Platform"] !== null) {
            $this->Platform = $param["Platform"];
        }

        if (array_key_exists("MaterialId",$param) and $param["MaterialId"] !== null) {
            $this->MaterialId = $param["MaterialId"];
        }

        if (array_key_exists("Owner",$param) and $param["Owner"] !== null) {
            $this->Owner = new Entity();
            $this->Owner->deserialize($param["Owner"]);
        }

        if (array_key_exists("Name",$param) and $param["Name"] !== null) {
            $this->Name = $param["Name"];
        }

        if (array_key_exists("Tags",$param) and $param["Tags"] !== null) {
            $this->Tags = $param["Tags"];
        }

        if (array_key_exists("ClassPath",$param) and $param["ClassPath"] !== null) {
            $this->ClassPath = $param["ClassPath"];
        }

        if (array_key_exists("Operator",$param) and $param["Operator"] !== null) {
            $this->Operator = $param["Operator"];
        }
    }
}
