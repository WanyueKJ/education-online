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
namespace TencentCloud\Tcr\V20190924\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 实例信息结构体
 *
 * @method string getRegistryId() 获取实例ID
 * @method void setRegistryId(string $RegistryId) 设置实例ID
 * @method string getRegistryName() 获取实例名称
 * @method void setRegistryName(string $RegistryName) 设置实例名称
 * @method string getRegistryType() 获取实例规格
 * @method void setRegistryType(string $RegistryType) 设置实例规格
 * @method string getStatus() 获取实例状态
 * @method void setStatus(string $Status) 设置实例状态
 * @method string getPublicDomain() 获取实例的公共访问地址
 * @method void setPublicDomain(string $PublicDomain) 设置实例的公共访问地址
 * @method string getCreatedAt() 获取实例创建时间
 * @method void setCreatedAt(string $CreatedAt) 设置实例创建时间
 * @method string getRegionName() 获取地域名称
 * @method void setRegionName(string $RegionName) 设置地域名称
 * @method integer getRegionId() 获取地域Id
 * @method void setRegionId(integer $RegionId) 设置地域Id
 * @method boolean getEnableAnonymous() 获取是否支持匿名
 * @method void setEnableAnonymous(boolean $EnableAnonymous) 设置是否支持匿名
 * @method integer getTokenValidTime() 获取Token有效时间
 * @method void setTokenValidTime(integer $TokenValidTime) 设置Token有效时间
 * @method string getInternalEndpoint() 获取实例内部访问地址
 * @method void setInternalEndpoint(string $InternalEndpoint) 设置实例内部访问地址
 */
class Registry extends AbstractModel
{
    /**
     * @var string 实例ID
     */
    public $RegistryId;

    /**
     * @var string 实例名称
     */
    public $RegistryName;

    /**
     * @var string 实例规格
     */
    public $RegistryType;

    /**
     * @var string 实例状态
     */
    public $Status;

    /**
     * @var string 实例的公共访问地址
     */
    public $PublicDomain;

    /**
     * @var string 实例创建时间
     */
    public $CreatedAt;

    /**
     * @var string 地域名称
     */
    public $RegionName;

    /**
     * @var integer 地域Id
     */
    public $RegionId;

    /**
     * @var boolean 是否支持匿名
     */
    public $EnableAnonymous;

    /**
     * @var integer Token有效时间
     */
    public $TokenValidTime;

    /**
     * @var string 实例内部访问地址
     */
    public $InternalEndpoint;

    /**
     * @param string $RegistryId 实例ID
     * @param string $RegistryName 实例名称
     * @param string $RegistryType 实例规格
     * @param string $Status 实例状态
     * @param string $PublicDomain 实例的公共访问地址
     * @param string $CreatedAt 实例创建时间
     * @param string $RegionName 地域名称
     * @param integer $RegionId 地域Id
     * @param boolean $EnableAnonymous 是否支持匿名
     * @param integer $TokenValidTime Token有效时间
     * @param string $InternalEndpoint 实例内部访问地址
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
        if (array_key_exists("RegistryId",$param) and $param["RegistryId"] !== null) {
            $this->RegistryId = $param["RegistryId"];
        }

        if (array_key_exists("RegistryName",$param) and $param["RegistryName"] !== null) {
            $this->RegistryName = $param["RegistryName"];
        }

        if (array_key_exists("RegistryType",$param) and $param["RegistryType"] !== null) {
            $this->RegistryType = $param["RegistryType"];
        }

        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("PublicDomain",$param) and $param["PublicDomain"] !== null) {
            $this->PublicDomain = $param["PublicDomain"];
        }

        if (array_key_exists("CreatedAt",$param) and $param["CreatedAt"] !== null) {
            $this->CreatedAt = $param["CreatedAt"];
        }

        if (array_key_exists("RegionName",$param) and $param["RegionName"] !== null) {
            $this->RegionName = $param["RegionName"];
        }

        if (array_key_exists("RegionId",$param) and $param["RegionId"] !== null) {
            $this->RegionId = $param["RegionId"];
        }

        if (array_key_exists("EnableAnonymous",$param) and $param["EnableAnonymous"] !== null) {
            $this->EnableAnonymous = $param["EnableAnonymous"];
        }

        if (array_key_exists("TokenValidTime",$param) and $param["TokenValidTime"] !== null) {
            $this->TokenValidTime = $param["TokenValidTime"];
        }

        if (array_key_exists("InternalEndpoint",$param) and $param["InternalEndpoint"] !== null) {
            $this->InternalEndpoint = $param["InternalEndpoint"];
        }
    }
}
