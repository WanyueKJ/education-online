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
namespace TencentCloud\Tcaplusdb\V20190823\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 集群详细信息
 *
 * @method string getClusterName() 获取集群名称
 * @method void setClusterName(string $ClusterName) 设置集群名称
 * @method string getClusterId() 获取集群ID
 * @method void setClusterId(string $ClusterId) 设置集群ID
 * @method string getRegion() 获取集群所在地域
 * @method void setRegion(string $Region) 设置集群所在地域
 * @method string getIdlType() 获取集群数据描述语言类型，如：`PROTO`,`TDR`或`MIX`
 * @method void setIdlType(string $IdlType) 设置集群数据描述语言类型，如：`PROTO`,`TDR`或`MIX`
 * @method string getNetworkType() 获取网络类型
 * @method void setNetworkType(string $NetworkType) 设置网络类型
 * @method string getVpcId() 获取集群关联的用户私有网络实例ID
 * @method void setVpcId(string $VpcId) 设置集群关联的用户私有网络实例ID
 * @method string getSubnetId() 获取集群关联的用户子网实例ID
 * @method void setSubnetId(string $SubnetId) 设置集群关联的用户子网实例ID
 * @method string getCreatedTime() 获取创建时间
 * @method void setCreatedTime(string $CreatedTime) 设置创建时间
 * @method string getPassword() 获取集群密码
 * @method void setPassword(string $Password) 设置集群密码
 * @method string getPasswordStatus() 获取密码状态
 * @method void setPasswordStatus(string $PasswordStatus) 设置密码状态
 * @method string getApiAccessId() 获取TcaplusDB SDK连接参数，接入ID
 * @method void setApiAccessId(string $ApiAccessId) 设置TcaplusDB SDK连接参数，接入ID
 * @method string getApiAccessIp() 获取TcaplusDB SDK连接参数，接入地址
 * @method void setApiAccessIp(string $ApiAccessIp) 设置TcaplusDB SDK连接参数，接入地址
 * @method integer getApiAccessPort() 获取TcaplusDB SDK连接参数，接入端口
 * @method void setApiAccessPort(integer $ApiAccessPort) 设置TcaplusDB SDK连接参数，接入端口
 * @method string getOldPasswordExpireTime() 获取如果PasswordStatus是unmodifiable说明有旧密码还未过期，此字段将显示旧密码过期的时间，否则为空
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setOldPasswordExpireTime(string $OldPasswordExpireTime) 设置如果PasswordStatus是unmodifiable说明有旧密码还未过期，此字段将显示旧密码过期的时间，否则为空
注意：此字段可能返回 null，表示取不到有效值。
 */
class ClusterInfo extends AbstractModel
{
    /**
     * @var string 集群名称
     */
    public $ClusterName;

    /**
     * @var string 集群ID
     */
    public $ClusterId;

    /**
     * @var string 集群所在地域
     */
    public $Region;

    /**
     * @var string 集群数据描述语言类型，如：`PROTO`,`TDR`或`MIX`
     */
    public $IdlType;

    /**
     * @var string 网络类型
     */
    public $NetworkType;

    /**
     * @var string 集群关联的用户私有网络实例ID
     */
    public $VpcId;

    /**
     * @var string 集群关联的用户子网实例ID
     */
    public $SubnetId;

    /**
     * @var string 创建时间
     */
    public $CreatedTime;

    /**
     * @var string 集群密码
     */
    public $Password;

    /**
     * @var string 密码状态
     */
    public $PasswordStatus;

    /**
     * @var string TcaplusDB SDK连接参数，接入ID
     */
    public $ApiAccessId;

    /**
     * @var string TcaplusDB SDK连接参数，接入地址
     */
    public $ApiAccessIp;

    /**
     * @var integer TcaplusDB SDK连接参数，接入端口
     */
    public $ApiAccessPort;

    /**
     * @var string 如果PasswordStatus是unmodifiable说明有旧密码还未过期，此字段将显示旧密码过期的时间，否则为空
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $OldPasswordExpireTime;

    /**
     * @param string $ClusterName 集群名称
     * @param string $ClusterId 集群ID
     * @param string $Region 集群所在地域
     * @param string $IdlType 集群数据描述语言类型，如：`PROTO`,`TDR`或`MIX`
     * @param string $NetworkType 网络类型
     * @param string $VpcId 集群关联的用户私有网络实例ID
     * @param string $SubnetId 集群关联的用户子网实例ID
     * @param string $CreatedTime 创建时间
     * @param string $Password 集群密码
     * @param string $PasswordStatus 密码状态
     * @param string $ApiAccessId TcaplusDB SDK连接参数，接入ID
     * @param string $ApiAccessIp TcaplusDB SDK连接参数，接入地址
     * @param integer $ApiAccessPort TcaplusDB SDK连接参数，接入端口
     * @param string $OldPasswordExpireTime 如果PasswordStatus是unmodifiable说明有旧密码还未过期，此字段将显示旧密码过期的时间，否则为空
注意：此字段可能返回 null，表示取不到有效值。
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

        if (array_key_exists("ClusterId",$param) and $param["ClusterId"] !== null) {
            $this->ClusterId = $param["ClusterId"];
        }

        if (array_key_exists("Region",$param) and $param["Region"] !== null) {
            $this->Region = $param["Region"];
        }

        if (array_key_exists("IdlType",$param) and $param["IdlType"] !== null) {
            $this->IdlType = $param["IdlType"];
        }

        if (array_key_exists("NetworkType",$param) and $param["NetworkType"] !== null) {
            $this->NetworkType = $param["NetworkType"];
        }

        if (array_key_exists("VpcId",$param) and $param["VpcId"] !== null) {
            $this->VpcId = $param["VpcId"];
        }

        if (array_key_exists("SubnetId",$param) and $param["SubnetId"] !== null) {
            $this->SubnetId = $param["SubnetId"];
        }

        if (array_key_exists("CreatedTime",$param) and $param["CreatedTime"] !== null) {
            $this->CreatedTime = $param["CreatedTime"];
        }

        if (array_key_exists("Password",$param) and $param["Password"] !== null) {
            $this->Password = $param["Password"];
        }

        if (array_key_exists("PasswordStatus",$param) and $param["PasswordStatus"] !== null) {
            $this->PasswordStatus = $param["PasswordStatus"];
        }

        if (array_key_exists("ApiAccessId",$param) and $param["ApiAccessId"] !== null) {
            $this->ApiAccessId = $param["ApiAccessId"];
        }

        if (array_key_exists("ApiAccessIp",$param) and $param["ApiAccessIp"] !== null) {
            $this->ApiAccessIp = $param["ApiAccessIp"];
        }

        if (array_key_exists("ApiAccessPort",$param) and $param["ApiAccessPort"] !== null) {
            $this->ApiAccessPort = $param["ApiAccessPort"];
        }

        if (array_key_exists("OldPasswordExpireTime",$param) and $param["OldPasswordExpireTime"] !== null) {
            $this->OldPasswordExpireTime = $param["OldPasswordExpireTime"];
        }
    }
}
