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
namespace TencentCloud\Cmq\V20190304\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DeadLetterPolicy
 *
 * @method string getDeadLetterQueueName() 获取DeadLetterQueueName
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDeadLetterQueueName(string $DeadLetterQueueName) 设置DeadLetterQueueName
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getDeadLetterQueue() 获取DeadLetterQueue
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDeadLetterQueue(string $DeadLetterQueue) 设置DeadLetterQueue
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getPolicy() 获取Policy
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setPolicy(integer $Policy) 设置Policy
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxTimeToLive() 获取MaxTimeToLive
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxTimeToLive(integer $MaxTimeToLive) 设置MaxTimeToLive
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxReceiveCount() 获取MaxReceiveCount
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxReceiveCount(integer $MaxReceiveCount) 设置MaxReceiveCount
注意：此字段可能返回 null，表示取不到有效值。
 */
class DeadLetterPolicy extends AbstractModel
{
    /**
     * @var string DeadLetterQueueName
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $DeadLetterQueueName;

    /**
     * @var string DeadLetterQueue
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $DeadLetterQueue;

    /**
     * @var integer Policy
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Policy;

    /**
     * @var integer MaxTimeToLive
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxTimeToLive;

    /**
     * @var integer MaxReceiveCount
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxReceiveCount;

    /**
     * @param string $DeadLetterQueueName DeadLetterQueueName
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $DeadLetterQueue DeadLetterQueue
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $Policy Policy
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxTimeToLive MaxTimeToLive
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxReceiveCount MaxReceiveCount
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
        if (array_key_exists("DeadLetterQueueName",$param) and $param["DeadLetterQueueName"] !== null) {
            $this->DeadLetterQueueName = $param["DeadLetterQueueName"];
        }

        if (array_key_exists("DeadLetterQueue",$param) and $param["DeadLetterQueue"] !== null) {
            $this->DeadLetterQueue = $param["DeadLetterQueue"];
        }

        if (array_key_exists("Policy",$param) and $param["Policy"] !== null) {
            $this->Policy = $param["Policy"];
        }

        if (array_key_exists("MaxTimeToLive",$param) and $param["MaxTimeToLive"] !== null) {
            $this->MaxTimeToLive = $param["MaxTimeToLive"];
        }

        if (array_key_exists("MaxReceiveCount",$param) and $param["MaxReceiveCount"] !== null) {
            $this->MaxReceiveCount = $param["MaxReceiveCount"];
        }
    }
}
