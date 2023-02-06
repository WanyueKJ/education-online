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
 * 订阅返回参数
 *
 * @method string getSubscriptionName() 获取SubscriptionName
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSubscriptionName(string $SubscriptionName) 设置SubscriptionName
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getSubscriptionId() 获取SubscriptionId
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSubscriptionId(string $SubscriptionId) 设置SubscriptionId
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getTopicOwner() 获取TopicOwner
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTopicOwner(integer $TopicOwner) 设置TopicOwner
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMsgCount() 获取MsgCount
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMsgCount(integer $MsgCount) 设置MsgCount
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getLastModifyTime() 获取LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLastModifyTime(integer $LastModifyTime) 设置LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateTime() 获取CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(integer $CreateTime) 设置CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getBindingKey() 获取BindingKey
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBindingKey(array $BindingKey) 设置BindingKey
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getEndpoint() 获取Endpoint
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setEndpoint(string $Endpoint) 设置Endpoint
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getFilterTags() 获取FilterTags
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFilterTags(array $FilterTags) 设置FilterTags
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProtocol() 获取Protocol
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProtocol(string $Protocol) 设置Protocol
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getNotifyStrategy() 获取NotifyStrategy
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setNotifyStrategy(string $NotifyStrategy) 设置NotifyStrategy
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getNotifyContentFormat() 获取NotifyContentFormat
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setNotifyContentFormat(string $NotifyContentFormat) 设置NotifyContentFormat
注意：此字段可能返回 null，表示取不到有效值。
 */
class Subscription extends AbstractModel
{
    /**
     * @var string SubscriptionName
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $SubscriptionName;

    /**
     * @var string SubscriptionId
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $SubscriptionId;

    /**
     * @var integer TopicOwner
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TopicOwner;

    /**
     * @var integer MsgCount
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MsgCount;

    /**
     * @var integer LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $LastModifyTime;

    /**
     * @var integer CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var array BindingKey
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $BindingKey;

    /**
     * @var string Endpoint
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Endpoint;

    /**
     * @var array FilterTags
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FilterTags;

    /**
     * @var string Protocol
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Protocol;

    /**
     * @var string NotifyStrategy
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $NotifyStrategy;

    /**
     * @var string NotifyContentFormat
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $NotifyContentFormat;

    /**
     * @param string $SubscriptionName SubscriptionName
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $SubscriptionId SubscriptionId
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $TopicOwner TopicOwner
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MsgCount MsgCount
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $LastModifyTime LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateTime CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $BindingKey BindingKey
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Endpoint Endpoint
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $FilterTags FilterTags
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Protocol Protocol
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $NotifyStrategy NotifyStrategy
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $NotifyContentFormat NotifyContentFormat
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
        if (array_key_exists("SubscriptionName",$param) and $param["SubscriptionName"] !== null) {
            $this->SubscriptionName = $param["SubscriptionName"];
        }

        if (array_key_exists("SubscriptionId",$param) and $param["SubscriptionId"] !== null) {
            $this->SubscriptionId = $param["SubscriptionId"];
        }

        if (array_key_exists("TopicOwner",$param) and $param["TopicOwner"] !== null) {
            $this->TopicOwner = $param["TopicOwner"];
        }

        if (array_key_exists("MsgCount",$param) and $param["MsgCount"] !== null) {
            $this->MsgCount = $param["MsgCount"];
        }

        if (array_key_exists("LastModifyTime",$param) and $param["LastModifyTime"] !== null) {
            $this->LastModifyTime = $param["LastModifyTime"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("BindingKey",$param) and $param["BindingKey"] !== null) {
            $this->BindingKey = $param["BindingKey"];
        }

        if (array_key_exists("Endpoint",$param) and $param["Endpoint"] !== null) {
            $this->Endpoint = $param["Endpoint"];
        }

        if (array_key_exists("FilterTags",$param) and $param["FilterTags"] !== null) {
            $this->FilterTags = $param["FilterTags"];
        }

        if (array_key_exists("Protocol",$param) and $param["Protocol"] !== null) {
            $this->Protocol = $param["Protocol"];
        }

        if (array_key_exists("NotifyStrategy",$param) and $param["NotifyStrategy"] !== null) {
            $this->NotifyStrategy = $param["NotifyStrategy"];
        }

        if (array_key_exists("NotifyContentFormat",$param) and $param["NotifyContentFormat"] !== null) {
            $this->NotifyContentFormat = $param["NotifyContentFormat"];
        }
    }
}
