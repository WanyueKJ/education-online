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
namespace TencentCloud\Gs\V20191118\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 机器详细信息
 *
 * @method integer getAppId() 获取客户appid
 * @method void setAppId(integer $AppId) 设置客户appid
 * @method integer getSetNo() 获取资源池编号
 * @method void setSetNo(integer $SetNo) 设置资源池编号
 * @method string getRegion() 获取机器所属区域
 * @method void setRegion(string $Region) 设置机器所属区域
 * @method string getInstanceId() 获取机器ID
 * @method void setInstanceId(string $InstanceId) 设置机器ID
 * @method string getInstanceType() 获取机器类型：
LARGE-大型
MEDIUM-中型
SMALL-小型
 * @method void setInstanceType(string $InstanceType) 设置机器类型：
LARGE-大型
MEDIUM-中型
SMALL-小型
 * @method string getIp() 获取机器IP
 * @method void setIp(string $Ip) 设置机器IP
 * @method string getServiceState() 获取服务状态：
IDLE-空闲
LOCK-锁定
ESTABLISHED-游戏中
RECONNECT-等待重连
RECOVERY-清理恢复
FORBID-禁用
UNAVAILABLE-不可用
 * @method void setServiceState(string $ServiceState) 设置服务状态：
IDLE-空闲
LOCK-锁定
ESTABLISHED-游戏中
RECONNECT-等待重连
RECOVERY-清理恢复
FORBID-禁用
UNAVAILABLE-不可用
 * @method string getUserId() 获取用户ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setUserId(string $UserId) 设置用户ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getGameId() 获取游戏ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setGameId(string $GameId) 设置游戏ID
注意：此字段可能返回 null，表示取不到有效值。
 */
class WorkerDetail extends AbstractModel
{
    /**
     * @var integer 客户appid
     */
    public $AppId;

    /**
     * @var integer 资源池编号
     */
    public $SetNo;

    /**
     * @var string 机器所属区域
     */
    public $Region;

    /**
     * @var string 机器ID
     */
    public $InstanceId;

    /**
     * @var string 机器类型：
LARGE-大型
MEDIUM-中型
SMALL-小型
     */
    public $InstanceType;

    /**
     * @var string 机器IP
     */
    public $Ip;

    /**
     * @var string 服务状态：
IDLE-空闲
LOCK-锁定
ESTABLISHED-游戏中
RECONNECT-等待重连
RECOVERY-清理恢复
FORBID-禁用
UNAVAILABLE-不可用
     */
    public $ServiceState;

    /**
     * @var string 用户ID
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $UserId;

    /**
     * @var string 游戏ID
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $GameId;

    /**
     * @param integer $AppId 客户appid
     * @param integer $SetNo 资源池编号
     * @param string $Region 机器所属区域
     * @param string $InstanceId 机器ID
     * @param string $InstanceType 机器类型：
LARGE-大型
MEDIUM-中型
SMALL-小型
     * @param string $Ip 机器IP
     * @param string $ServiceState 服务状态：
IDLE-空闲
LOCK-锁定
ESTABLISHED-游戏中
RECONNECT-等待重连
RECOVERY-清理恢复
FORBID-禁用
UNAVAILABLE-不可用
     * @param string $UserId 用户ID
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $GameId 游戏ID
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
        if (array_key_exists("AppId",$param) and $param["AppId"] !== null) {
            $this->AppId = $param["AppId"];
        }

        if (array_key_exists("SetNo",$param) and $param["SetNo"] !== null) {
            $this->SetNo = $param["SetNo"];
        }

        if (array_key_exists("Region",$param) and $param["Region"] !== null) {
            $this->Region = $param["Region"];
        }

        if (array_key_exists("InstanceId",$param) and $param["InstanceId"] !== null) {
            $this->InstanceId = $param["InstanceId"];
        }

        if (array_key_exists("InstanceType",$param) and $param["InstanceType"] !== null) {
            $this->InstanceType = $param["InstanceType"];
        }

        if (array_key_exists("Ip",$param) and $param["Ip"] !== null) {
            $this->Ip = $param["Ip"];
        }

        if (array_key_exists("ServiceState",$param) and $param["ServiceState"] !== null) {
            $this->ServiceState = $param["ServiceState"];
        }

        if (array_key_exists("UserId",$param) and $param["UserId"] !== null) {
            $this->UserId = $param["UserId"];
        }

        if (array_key_exists("GameId",$param) and $param["GameId"] !== null) {
            $this->GameId = $param["GameId"];
        }
    }
}
