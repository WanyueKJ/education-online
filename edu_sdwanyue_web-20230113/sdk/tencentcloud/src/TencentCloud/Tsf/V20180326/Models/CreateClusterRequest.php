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
 * CreateCluster请求参数结构体
 *
 * @method string getClusterName() 获取集群名称
 * @method void setClusterName(string $ClusterName) 设置集群名称
 * @method string getClusterType() 获取集群类型
 * @method void setClusterType(string $ClusterType) 设置集群类型
 * @method string getVpcId() 获取私有网络ID
 * @method void setVpcId(string $VpcId) 设置私有网络ID
 * @method string getClusterCIDR() 获取分配给集群容器和服务IP的CIDR
 * @method void setClusterCIDR(string $ClusterCIDR) 设置分配给集群容器和服务IP的CIDR
 * @method string getClusterDesc() 获取集群备注
 * @method void setClusterDesc(string $ClusterDesc) 设置集群备注
 * @method string getTsfRegionId() 获取集群所属TSF地域
 * @method void setTsfRegionId(string $TsfRegionId) 设置集群所属TSF地域
 * @method string getTsfZoneId() 获取集群所属TSF可用区
 * @method void setTsfZoneId(string $TsfZoneId) 设置集群所属TSF可用区
 * @method string getSubnetId() 获取私有网络子网ID
 * @method void setSubnetId(string $SubnetId) 设置私有网络子网ID
 */
class CreateClusterRequest extends AbstractModel
{
    /**
     * @var string 集群名称
     */
    public $ClusterName;

    /**
     * @var string 集群类型
     */
    public $ClusterType;

    /**
     * @var string 私有网络ID
     */
    public $VpcId;

    /**
     * @var string 分配给集群容器和服务IP的CIDR
     */
    public $ClusterCIDR;

    /**
     * @var string 集群备注
     */
    public $ClusterDesc;

    /**
     * @var string 集群所属TSF地域
     */
    public $TsfRegionId;

    /**
     * @var string 集群所属TSF可用区
     */
    public $TsfZoneId;

    /**
     * @var string 私有网络子网ID
     */
    public $SubnetId;

    /**
     * @param string $ClusterName 集群名称
     * @param string $ClusterType 集群类型
     * @param string $VpcId 私有网络ID
     * @param string $ClusterCIDR 分配给集群容器和服务IP的CIDR
     * @param string $ClusterDesc 集群备注
     * @param string $TsfRegionId 集群所属TSF地域
     * @param string $TsfZoneId 集群所属TSF可用区
     * @param string $SubnetId 私有网络子网ID
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
        if (array_key_exists("ClusterName",$param) and $param["ClusterName"] !== null) {
            $this->ClusterName = $param["ClusterName"];
        }

        if (array_key_exists("ClusterType",$param) and $param["ClusterType"] !== null) {
            $this->ClusterType = $param["ClusterType"];
        }

        if (array_key_exists("VpcId",$param) and $param["VpcId"] !== null) {
            $this->VpcId = $param["VpcId"];
        }

        if (array_key_exists("ClusterCIDR",$param) and $param["ClusterCIDR"] !== null) {
            $this->ClusterCIDR = $param["ClusterCIDR"];
        }

        if (array_key_exists("ClusterDesc",$param) and $param["ClusterDesc"] !== null) {
            $this->ClusterDesc = $param["ClusterDesc"];
        }

        if (array_key_exists("TsfRegionId",$param) and $param["TsfRegionId"] !== null) {
            $this->TsfRegionId = $param["TsfRegionId"];
        }

        if (array_key_exists("TsfZoneId",$param) and $param["TsfZoneId"] !== null) {
            $this->TsfZoneId = $param["TsfZoneId"];
        }

        if (array_key_exists("SubnetId",$param) and $param["SubnetId"] !== null) {
            $this->SubnetId = $param["SubnetId"];
        }
    }
}
