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
 * topic返回信息展示字段
 *
 * @method string getTopicId() 获取TopicId
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTopicId(string $TopicId) 设置TopicId
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getTopicName() 获取TopicName
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTopicName(string $TopicName) 设置TopicName
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMsgRetentionSeconds() 获取MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMsgRetentionSeconds(integer $MsgRetentionSeconds) 设置MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMaxMsgSize() 获取MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMaxMsgSize(integer $MaxMsgSize) 设置MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getQps() 获取Qps
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setQps(integer $Qps) 设置Qps
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getFilterType() 获取FilterType
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFilterType(integer $FilterType) 设置FilterType
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateTime() 获取CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(integer $CreateTime) 设置CreateTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getLastModifyTime() 获取LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLastModifyTime(integer $LastModifyTime) 设置LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getMsgCount() 获取MsgCount
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMsgCount(integer $MsgCount) 设置MsgCount
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateUin() 获取CreateUin
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateUin(integer $CreateUin) 设置CreateUin
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getTags() 获取Tags
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTags(array $Tags) 设置Tags
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getTrace() 获取主题是否开启消息轨迹，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTrace(boolean $Trace) 设置主题是否开启消息轨迹，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
 */
class TopicSet extends AbstractModel
{
    /**
     * @var string TopicId
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TopicId;

    /**
     * @var string TopicName
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TopicName;

    /**
     * @var integer MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MsgRetentionSeconds;

    /**
     * @var integer MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MaxMsgSize;

    /**
     * @var integer Qps
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Qps;

    /**
     * @var integer FilterType
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FilterType;

    /**
     * @var integer CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var integer LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $LastModifyTime;

    /**
     * @var integer MsgCount
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $MsgCount;

    /**
     * @var integer CreateUin
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateUin;

    /**
     * @var array Tags
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Tags;

    /**
     * @var boolean 主题是否开启消息轨迹，true表示开启，false表示不开启
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Trace;

    /**
     * @param string $TopicId TopicId
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $TopicName TopicName
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MsgRetentionSeconds MsgRetentionSeconds
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MaxMsgSize MaxMsgSize
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $Qps Qps
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $FilterType FilterType
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateTime CreateTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $LastModifyTime LastModifyTime
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $MsgCount MsgCount
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateUin CreateUin
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $Tags Tags
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $Trace 主题是否开启消息轨迹，true表示开启，false表示不开启
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
        if (array_key_exists("TopicId",$param) and $param["TopicId"] !== null) {
            $this->TopicId = $param["TopicId"];
        }

        if (array_key_exists("TopicName",$param) and $param["TopicName"] !== null) {
            $this->TopicName = $param["TopicName"];
        }

        if (array_key_exists("MsgRetentionSeconds",$param) and $param["MsgRetentionSeconds"] !== null) {
            $this->MsgRetentionSeconds = $param["MsgRetentionSeconds"];
        }

        if (array_key_exists("MaxMsgSize",$param) and $param["MaxMsgSize"] !== null) {
            $this->MaxMsgSize = $param["MaxMsgSize"];
        }

        if (array_key_exists("Qps",$param) and $param["Qps"] !== null) {
            $this->Qps = $param["Qps"];
        }

        if (array_key_exists("FilterType",$param) and $param["FilterType"] !== null) {
            $this->FilterType = $param["FilterType"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("LastModifyTime",$param) and $param["LastModifyTime"] !== null) {
            $this->LastModifyTime = $param["LastModifyTime"];
        }

        if (array_key_exists("MsgCount",$param) and $param["MsgCount"] !== null) {
            $this->MsgCount = $param["MsgCount"];
        }

        if (array_key_exists("CreateUin",$param) and $param["CreateUin"] !== null) {
            $this->CreateUin = $param["CreateUin"];
        }

        if (array_key_exists("Tags",$param) and $param["Tags"] !== null) {
            $this->Tags = [];
            foreach ($param["Tags"] as $key => $value){
                $obj = new Tag();
                $obj->deserialize($value);
                array_push($this->Tags, $obj);
            }
        }

        if (array_key_exists("Trace",$param) and $param["Trace"] !== null) {
            $this->Trace = $param["Trace"];
        }
    }
}
