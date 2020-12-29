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
namespace TencentCloud\Redis\V20180412\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateInstances请求参数结构体
 *
 * @method integer getZoneId() 获取实例所属的可用区ID
 * @method void setZoneId(integer $ZoneId) 设置实例所属的可用区ID
 * @method integer getTypeId() 获取实例类型：2 – Redis2.8主从版，3 – Redis3.2主从版(CKV主从版)，4 – Redis3.2集群版(CKV集群版)，5-Redis2.8单机版，6 – Redis4.0主从版，7 – Redis4.0集群版，8 – Redis5.0主从版，9 – Redis5.0集群版，
 * @method void setTypeId(integer $TypeId) 设置实例类型：2 – Redis2.8主从版，3 – Redis3.2主从版(CKV主从版)，4 – Redis3.2集群版(CKV集群版)，5-Redis2.8单机版，6 – Redis4.0主从版，7 – Redis4.0集群版，8 – Redis5.0主从版，9 – Redis5.0集群版，
 * @method integer getMemSize() 获取实例容量，单位MB， 取值大小以 查询售卖规格接口返回的规格为准
 * @method void setMemSize(integer $MemSize) 设置实例容量，单位MB， 取值大小以 查询售卖规格接口返回的规格为准
 * @method integer getGoodsNum() 获取实例数量，单次购买实例数量以 查询售卖规格接口返回的规格为准
 * @method void setGoodsNum(integer $GoodsNum) 设置实例数量，单次购买实例数量以 查询售卖规格接口返回的规格为准
 * @method integer getPeriod() 获取购买时长，在创建包年包月实例的时候需要填写，按量计费实例填1即可，单位：月，取值范围 [1,2,3,4,5,6,7,8,9,10,11,12,24,36]
 * @method void setPeriod(integer $Period) 设置购买时长，在创建包年包月实例的时候需要填写，按量计费实例填1即可，单位：月，取值范围 [1,2,3,4,5,6,7,8,9,10,11,12,24,36]
 * @method integer getBillingMode() 获取付费方式:0-按量计费，1-包年包月。
 * @method void setBillingMode(integer $BillingMode) 设置付费方式:0-按量计费，1-包年包月。
 * @method string getPassword() 获取实例密码，密码规则：1.长度为8-16个字符；2:至少包含字母、数字和字符!@^*()中的两种（创建免密实例时，可不传入该字段，该字段内容会忽略）
 * @method void setPassword(string $Password) 设置实例密码，密码规则：1.长度为8-16个字符；2:至少包含字母、数字和字符!@^*()中的两种（创建免密实例时，可不传入该字段，该字段内容会忽略）
 * @method string getVpcId() 获取私有网络ID，如果不传则默认选择基础网络，请使用私有网络列表查询，如：vpc-sad23jfdfk
 * @method void setVpcId(string $VpcId) 设置私有网络ID，如果不传则默认选择基础网络，请使用私有网络列表查询，如：vpc-sad23jfdfk
 * @method string getSubnetId() 获取基础网络下， subnetId无效； vpc子网下，取值以查询子网列表，如：subnet-fdj24n34j2
 * @method void setSubnetId(string $SubnetId) 设置基础网络下， subnetId无效； vpc子网下，取值以查询子网列表，如：subnet-fdj24n34j2
 * @method integer getProjectId() 获取项目id，取值以用户账户>用户账户相关接口查询>项目列表返回的projectId为准
 * @method void setProjectId(integer $ProjectId) 设置项目id，取值以用户账户>用户账户相关接口查询>项目列表返回的projectId为准
 * @method integer getAutoRenew() 获取自动续费标识。0 - 默认状态（手动续费）；1 - 自动续费；2 - 明确不自动续费
 * @method void setAutoRenew(integer $AutoRenew) 设置自动续费标识。0 - 默认状态（手动续费）；1 - 自动续费；2 - 明确不自动续费
 * @method array getSecurityGroupIdList() 获取安全组id数组
 * @method void setSecurityGroupIdList(array $SecurityGroupIdList) 设置安全组id数组
 * @method integer getVPort() 获取用户自定义的端口 不填则默认为6379，范围[1024,65535]
 * @method void setVPort(integer $VPort) 设置用户自定义的端口 不填则默认为6379，范围[1024,65535]
 * @method integer getRedisShardNum() 获取实例分片数量，Redis2.8主从版、CKV主从版和Redis2.8单机版、Redis4.0主从版不需要填写
 * @method void setRedisShardNum(integer $RedisShardNum) 设置实例分片数量，Redis2.8主从版、CKV主从版和Redis2.8单机版、Redis4.0主从版不需要填写
 * @method integer getRedisReplicasNum() 获取实例副本数量，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
 * @method void setRedisReplicasNum(integer $RedisReplicasNum) 设置实例副本数量，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
 * @method boolean getReplicasReadonly() 获取是否支持副本只读，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
 * @method void setReplicasReadonly(boolean $ReplicasReadonly) 设置是否支持副本只读，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
 * @method string getInstanceName() 获取实例名称
 * @method void setInstanceName(string $InstanceName) 设置实例名称
 * @method boolean getNoAuth() 获取是否支持免密，true-免密实例，false-非免密实例，默认为非免密实例
 * @method void setNoAuth(boolean $NoAuth) 设置是否支持免密，true-免密实例，false-非免密实例，默认为非免密实例
 */
class CreateInstancesRequest extends AbstractModel
{
    /**
     * @var integer 实例所属的可用区ID
     */
    public $ZoneId;

    /**
     * @var integer 实例类型：2 – Redis2.8主从版，3 – Redis3.2主从版(CKV主从版)，4 – Redis3.2集群版(CKV集群版)，5-Redis2.8单机版，6 – Redis4.0主从版，7 – Redis4.0集群版，8 – Redis5.0主从版，9 – Redis5.0集群版，
     */
    public $TypeId;

    /**
     * @var integer 实例容量，单位MB， 取值大小以 查询售卖规格接口返回的规格为准
     */
    public $MemSize;

    /**
     * @var integer 实例数量，单次购买实例数量以 查询售卖规格接口返回的规格为准
     */
    public $GoodsNum;

    /**
     * @var integer 购买时长，在创建包年包月实例的时候需要填写，按量计费实例填1即可，单位：月，取值范围 [1,2,3,4,5,6,7,8,9,10,11,12,24,36]
     */
    public $Period;

    /**
     * @var integer 付费方式:0-按量计费，1-包年包月。
     */
    public $BillingMode;

    /**
     * @var string 实例密码，密码规则：1.长度为8-16个字符；2:至少包含字母、数字和字符!@^*()中的两种（创建免密实例时，可不传入该字段，该字段内容会忽略）
     */
    public $Password;

    /**
     * @var string 私有网络ID，如果不传则默认选择基础网络，请使用私有网络列表查询，如：vpc-sad23jfdfk
     */
    public $VpcId;

    /**
     * @var string 基础网络下， subnetId无效； vpc子网下，取值以查询子网列表，如：subnet-fdj24n34j2
     */
    public $SubnetId;

    /**
     * @var integer 项目id，取值以用户账户>用户账户相关接口查询>项目列表返回的projectId为准
     */
    public $ProjectId;

    /**
     * @var integer 自动续费标识。0 - 默认状态（手动续费）；1 - 自动续费；2 - 明确不自动续费
     */
    public $AutoRenew;

    /**
     * @var array 安全组id数组
     */
    public $SecurityGroupIdList;

    /**
     * @var integer 用户自定义的端口 不填则默认为6379，范围[1024,65535]
     */
    public $VPort;

    /**
     * @var integer 实例分片数量，Redis2.8主从版、CKV主从版和Redis2.8单机版、Redis4.0主从版不需要填写
     */
    public $RedisShardNum;

    /**
     * @var integer 实例副本数量，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
     */
    public $RedisReplicasNum;

    /**
     * @var boolean 是否支持副本只读，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
     */
    public $ReplicasReadonly;

    /**
     * @var string 实例名称
     */
    public $InstanceName;

    /**
     * @var boolean 是否支持免密，true-免密实例，false-非免密实例，默认为非免密实例
     */
    public $NoAuth;

    /**
     * @param integer $ZoneId 实例所属的可用区ID
     * @param integer $TypeId 实例类型：2 – Redis2.8主从版，3 – Redis3.2主从版(CKV主从版)，4 – Redis3.2集群版(CKV集群版)，5-Redis2.8单机版，6 – Redis4.0主从版，7 – Redis4.0集群版，8 – Redis5.0主从版，9 – Redis5.0集群版，
     * @param integer $MemSize 实例容量，单位MB， 取值大小以 查询售卖规格接口返回的规格为准
     * @param integer $GoodsNum 实例数量，单次购买实例数量以 查询售卖规格接口返回的规格为准
     * @param integer $Period 购买时长，在创建包年包月实例的时候需要填写，按量计费实例填1即可，单位：月，取值范围 [1,2,3,4,5,6,7,8,9,10,11,12,24,36]
     * @param integer $BillingMode 付费方式:0-按量计费，1-包年包月。
     * @param string $Password 实例密码，密码规则：1.长度为8-16个字符；2:至少包含字母、数字和字符!@^*()中的两种（创建免密实例时，可不传入该字段，该字段内容会忽略）
     * @param string $VpcId 私有网络ID，如果不传则默认选择基础网络，请使用私有网络列表查询，如：vpc-sad23jfdfk
     * @param string $SubnetId 基础网络下， subnetId无效； vpc子网下，取值以查询子网列表，如：subnet-fdj24n34j2
     * @param integer $ProjectId 项目id，取值以用户账户>用户账户相关接口查询>项目列表返回的projectId为准
     * @param integer $AutoRenew 自动续费标识。0 - 默认状态（手动续费）；1 - 自动续费；2 - 明确不自动续费
     * @param array $SecurityGroupIdList 安全组id数组
     * @param integer $VPort 用户自定义的端口 不填则默认为6379，范围[1024,65535]
     * @param integer $RedisShardNum 实例分片数量，Redis2.8主从版、CKV主从版和Redis2.8单机版、Redis4.0主从版不需要填写
     * @param integer $RedisReplicasNum 实例副本数量，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
     * @param boolean $ReplicasReadonly 是否支持副本只读，Redis2.8主从版、CKV主从版和Redis2.8单机版不需要填写
     * @param string $InstanceName 实例名称
     * @param boolean $NoAuth 是否支持免密，true-免密实例，false-非免密实例，默认为非免密实例
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
        if (array_key_exists("ZoneId",$param) and $param["ZoneId"] !== null) {
            $this->ZoneId = $param["ZoneId"];
        }

        if (array_key_exists("TypeId",$param) and $param["TypeId"] !== null) {
            $this->TypeId = $param["TypeId"];
        }

        if (array_key_exists("MemSize",$param) and $param["MemSize"] !== null) {
            $this->MemSize = $param["MemSize"];
        }

        if (array_key_exists("GoodsNum",$param) and $param["GoodsNum"] !== null) {
            $this->GoodsNum = $param["GoodsNum"];
        }

        if (array_key_exists("Period",$param) and $param["Period"] !== null) {
            $this->Period = $param["Period"];
        }

        if (array_key_exists("BillingMode",$param) and $param["BillingMode"] !== null) {
            $this->BillingMode = $param["BillingMode"];
        }

        if (array_key_exists("Password",$param) and $param["Password"] !== null) {
            $this->Password = $param["Password"];
        }

        if (array_key_exists("VpcId",$param) and $param["VpcId"] !== null) {
            $this->VpcId = $param["VpcId"];
        }

        if (array_key_exists("SubnetId",$param) and $param["SubnetId"] !== null) {
            $this->SubnetId = $param["SubnetId"];
        }

        if (array_key_exists("ProjectId",$param) and $param["ProjectId"] !== null) {
            $this->ProjectId = $param["ProjectId"];
        }

        if (array_key_exists("AutoRenew",$param) and $param["AutoRenew"] !== null) {
            $this->AutoRenew = $param["AutoRenew"];
        }

        if (array_key_exists("SecurityGroupIdList",$param) and $param["SecurityGroupIdList"] !== null) {
            $this->SecurityGroupIdList = $param["SecurityGroupIdList"];
        }

        if (array_key_exists("VPort",$param) and $param["VPort"] !== null) {
            $this->VPort = $param["VPort"];
        }

        if (array_key_exists("RedisShardNum",$param) and $param["RedisShardNum"] !== null) {
            $this->RedisShardNum = $param["RedisShardNum"];
        }

        if (array_key_exists("RedisReplicasNum",$param) and $param["RedisReplicasNum"] !== null) {
            $this->RedisReplicasNum = $param["RedisReplicasNum"];
        }

        if (array_key_exists("ReplicasReadonly",$param) and $param["ReplicasReadonly"] !== null) {
            $this->ReplicasReadonly = $param["ReplicasReadonly"];
        }

        if (array_key_exists("InstanceName",$param) and $param["InstanceName"] !== null) {
            $this->InstanceName = $param["InstanceName"];
        }

        if (array_key_exists("NoAuth",$param) and $param["NoAuth"] !== null) {
            $this->NoAuth = $param["NoAuth"];
        }
    }
}
