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
namespace TencentCloud\Sqlserver\V20180328\Models;
use TencentCloud\Common\AbstractModel;

/**
 * UpgradeDBInstance请求参数结构体
 *
 * @method string getInstanceId() 获取实例ID，形如mssql-j8kv137v
 * @method void setInstanceId(string $InstanceId) 设置实例ID，形如mssql-j8kv137v
 * @method integer getMemory() 获取实例升级后内存大小，单位GB，其值不能小于当前实例内存大小
 * @method void setMemory(integer $Memory) 设置实例升级后内存大小，单位GB，其值不能小于当前实例内存大小
 * @method integer getStorage() 获取实例升级后磁盘大小，单位GB，其值不能小于当前实例磁盘大小
 * @method void setStorage(integer $Storage) 设置实例升级后磁盘大小，单位GB，其值不能小于当前实例磁盘大小
 * @method integer getAutoVoucher() 获取是否自动使用代金券，0 - 不使用；1 - 默认使用。取值默认为0
 * @method void setAutoVoucher(integer $AutoVoucher) 设置是否自动使用代金券，0 - 不使用；1 - 默认使用。取值默认为0
 * @method array getVoucherIds() 获取代金券ID，目前单个订单只能使用一张代金券
 * @method void setVoucherIds(array $VoucherIds) 设置代金券ID，目前单个订单只能使用一张代金券
 */
class UpgradeDBInstanceRequest extends AbstractModel
{
    /**
     * @var string 实例ID，形如mssql-j8kv137v
     */
    public $InstanceId;

    /**
     * @var integer 实例升级后内存大小，单位GB，其值不能小于当前实例内存大小
     */
    public $Memory;

    /**
     * @var integer 实例升级后磁盘大小，单位GB，其值不能小于当前实例磁盘大小
     */
    public $Storage;

    /**
     * @var integer 是否自动使用代金券，0 - 不使用；1 - 默认使用。取值默认为0
     */
    public $AutoVoucher;

    /**
     * @var array 代金券ID，目前单个订单只能使用一张代金券
     */
    public $VoucherIds;

    /**
     * @param string $InstanceId 实例ID，形如mssql-j8kv137v
     * @param integer $Memory 实例升级后内存大小，单位GB，其值不能小于当前实例内存大小
     * @param integer $Storage 实例升级后磁盘大小，单位GB，其值不能小于当前实例磁盘大小
     * @param integer $AutoVoucher 是否自动使用代金券，0 - 不使用；1 - 默认使用。取值默认为0
     * @param array $VoucherIds 代金券ID，目前单个订单只能使用一张代金券
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
        if (array_key_exists("InstanceId",$param) and $param["InstanceId"] !== null) {
            $this->InstanceId = $param["InstanceId"];
        }

        if (array_key_exists("Memory",$param) and $param["Memory"] !== null) {
            $this->Memory = $param["Memory"];
        }

        if (array_key_exists("Storage",$param) and $param["Storage"] !== null) {
            $this->Storage = $param["Storage"];
        }

        if (array_key_exists("AutoVoucher",$param) and $param["AutoVoucher"] !== null) {
            $this->AutoVoucher = $param["AutoVoucher"];
        }

        if (array_key_exists("VoucherIds",$param) and $param["VoucherIds"] !== null) {
            $this->VoucherIds = $param["VoucherIds"];
        }
    }
}
