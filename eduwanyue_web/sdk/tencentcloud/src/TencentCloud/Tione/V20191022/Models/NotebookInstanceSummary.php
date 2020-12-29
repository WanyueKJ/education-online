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
namespace TencentCloud\Tione\V20191022\Models;
use TencentCloud\Common\AbstractModel;

/**
 * notebook实例概览
 *
 * @method string getCreationTime() 获取创建时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreationTime(string $CreationTime) 设置创建时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getLastModifiedTime() 获取最近修改时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLastModifiedTime(string $LastModifiedTime) 设置最近修改时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getNotebookInstanceName() 获取notebook实例名字
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setNotebookInstanceName(string $NotebookInstanceName) 设置notebook实例名字
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getNotebookInstanceStatus() 获取notebook实例状态
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setNotebookInstanceStatus(string $NotebookInstanceStatus) 设置notebook实例状态
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getInstanceType() 获取算力类型
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setInstanceType(string $InstanceType) 设置算力类型
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getInstanceId() 获取算力Id
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setInstanceId(string $InstanceId) 设置算力Id
注意：此字段可能返回 null，表示取不到有效值。
 */
class NotebookInstanceSummary extends AbstractModel
{
    /**
     * @var string 创建时间
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreationTime;

    /**
     * @var string 最近修改时间
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $LastModifiedTime;

    /**
     * @var string notebook实例名字
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $NotebookInstanceName;

    /**
     * @var string notebook实例状态
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $NotebookInstanceStatus;

    /**
     * @var string 算力类型
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $InstanceType;

    /**
     * @var string 算力Id
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $InstanceId;

    /**
     * @param string $CreationTime 创建时间
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $LastModifiedTime 最近修改时间
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $NotebookInstanceName notebook实例名字
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $NotebookInstanceStatus notebook实例状态
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $InstanceType 算力类型
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $InstanceId 算力Id
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
        if (array_key_exists("CreationTime",$param) and $param["CreationTime"] !== null) {
            $this->CreationTime = $param["CreationTime"];
        }

        if (array_key_exists("LastModifiedTime",$param) and $param["LastModifiedTime"] !== null) {
            $this->LastModifiedTime = $param["LastModifiedTime"];
        }

        if (array_key_exists("NotebookInstanceName",$param) and $param["NotebookInstanceName"] !== null) {
            $this->NotebookInstanceName = $param["NotebookInstanceName"];
        }

        if (array_key_exists("NotebookInstanceStatus",$param) and $param["NotebookInstanceStatus"] !== null) {
            $this->NotebookInstanceStatus = $param["NotebookInstanceStatus"];
        }

        if (array_key_exists("InstanceType",$param) and $param["InstanceType"] !== null) {
            $this->InstanceType = $param["InstanceType"];
        }

        if (array_key_exists("InstanceId",$param) and $param["InstanceId"] !== null) {
            $this->InstanceId = $param["InstanceId"];
        }
    }
}
