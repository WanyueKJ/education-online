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
 * 集群高级配置
 *
 * @method boolean getIPVS() 获取是否启用IPVS
 * @method void setIPVS(boolean $IPVS) 设置是否启用IPVS
 * @method boolean getAsEnabled() 获取是否启用集群节点自动扩缩容(创建集群流程不支持开启此功能)
 * @method void setAsEnabled(boolean $AsEnabled) 设置是否启用集群节点自动扩缩容(创建集群流程不支持开启此功能)
 * @method string getContainerRuntime() 获取集群使用的runtime类型，包括"docker"和"containerd"两种类型，默认为"docker"
 * @method void setContainerRuntime(string $ContainerRuntime) 设置集群使用的runtime类型，包括"docker"和"containerd"两种类型，默认为"docker"
 * @method string getNodeNameType() 获取集群中节点NodeName类型（包括 hostname,lan-ip两种形式，默认为lan-ip。如果开启了hostname模式，创建节点时需要设置HostName参数，并且InstanceName需要和HostName一致）
 * @method void setNodeNameType(string $NodeNameType) 设置集群中节点NodeName类型（包括 hostname,lan-ip两种形式，默认为lan-ip。如果开启了hostname模式，创建节点时需要设置HostName参数，并且InstanceName需要和HostName一致）
 * @method ClusterExtraArgs getExtraArgs() 获取集群自定义参数
 * @method void setExtraArgs(ClusterExtraArgs $ExtraArgs) 设置集群自定义参数
 * @method string getNetworkType() 获取集群网络类型（包括GR(全局路由)和VPC-CNI两种模式，默认为GR。
 * @method void setNetworkType(string $NetworkType) 设置集群网络类型（包括GR(全局路由)和VPC-CNI两种模式，默认为GR。
 * @method boolean getIsNonStaticIpMode() 获取集群VPC-CNI模式是否为非固定IP，默认: FALSE 固定IP。
 * @method void setIsNonStaticIpMode(boolean $IsNonStaticIpMode) 设置集群VPC-CNI模式是否为非固定IP，默认: FALSE 固定IP。
 * @method boolean getDeletionProtection() 获取是否启用集群删除保护
 * @method void setDeletionProtection(boolean $DeletionProtection) 设置是否启用集群删除保护
 */
class ClusterAdvancedSettings extends AbstractModel
{
    /**
     * @var boolean 是否启用IPVS
     */
    public $IPVS;

    /**
     * @var boolean 是否启用集群节点自动扩缩容(创建集群流程不支持开启此功能)
     */
    public $AsEnabled;

    /**
     * @var string 集群使用的runtime类型，包括"docker"和"containerd"两种类型，默认为"docker"
     */
    public $ContainerRuntime;

    /**
     * @var string 集群中节点NodeName类型（包括 hostname,lan-ip两种形式，默认为lan-ip。如果开启了hostname模式，创建节点时需要设置HostName参数，并且InstanceName需要和HostName一致）
     */
    public $NodeNameType;

    /**
     * @var ClusterExtraArgs 集群自定义参数
     */
    public $ExtraArgs;

    /**
     * @var string 集群网络类型（包括GR(全局路由)和VPC-CNI两种模式，默认为GR。
     */
    public $NetworkType;

    /**
     * @var boolean 集群VPC-CNI模式是否为非固定IP，默认: FALSE 固定IP。
     */
    public $IsNonStaticIpMode;

    /**
     * @var boolean 是否启用集群删除保护
     */
    public $DeletionProtection;

    /**
     * @param boolean $IPVS 是否启用IPVS
     * @param boolean $AsEnabled 是否启用集群节点自动扩缩容(创建集群流程不支持开启此功能)
     * @param string $ContainerRuntime 集群使用的runtime类型，包括"docker"和"containerd"两种类型，默认为"docker"
     * @param string $NodeNameType 集群中节点NodeName类型（包括 hostname,lan-ip两种形式，默认为lan-ip。如果开启了hostname模式，创建节点时需要设置HostName参数，并且InstanceName需要和HostName一致）
     * @param ClusterExtraArgs $ExtraArgs 集群自定义参数
     * @param string $NetworkType 集群网络类型（包括GR(全局路由)和VPC-CNI两种模式，默认为GR。
     * @param boolean $IsNonStaticIpMode 集群VPC-CNI模式是否为非固定IP，默认: FALSE 固定IP。
     * @param boolean $DeletionProtection 是否启用集群删除保护
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
        if (array_key_exists("IPVS",$param) and $param["IPVS"] !== null) {
            $this->IPVS = $param["IPVS"];
        }

        if (array_key_exists("AsEnabled",$param) and $param["AsEnabled"] !== null) {
            $this->AsEnabled = $param["AsEnabled"];
        }

        if (array_key_exists("ContainerRuntime",$param) and $param["ContainerRuntime"] !== null) {
            $this->ContainerRuntime = $param["ContainerRuntime"];
        }

        if (array_key_exists("NodeNameType",$param) and $param["NodeNameType"] !== null) {
            $this->NodeNameType = $param["NodeNameType"];
        }

        if (array_key_exists("ExtraArgs",$param) and $param["ExtraArgs"] !== null) {
            $this->ExtraArgs = new ClusterExtraArgs();
            $this->ExtraArgs->deserialize($param["ExtraArgs"]);
        }

        if (array_key_exists("NetworkType",$param) and $param["NetworkType"] !== null) {
            $this->NetworkType = $param["NetworkType"];
        }

        if (array_key_exists("IsNonStaticIpMode",$param) and $param["IsNonStaticIpMode"] !== null) {
            $this->IsNonStaticIpMode = $param["IsNonStaticIpMode"];
        }

        if (array_key_exists("DeletionProtection",$param) and $param["DeletionProtection"] !== null) {
            $this->DeletionProtection = $param["DeletionProtection"];
        }
    }
}
