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
namespace TencentCloud\Scf\V20180416\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 触发器信息
 *
 * @method integer getEnable() 获取使能开关
 * @method void setEnable(integer $Enable) 设置使能开关
 * @method string getQualifier() 获取函数版本或别名
 * @method void setQualifier(string $Qualifier) 设置函数版本或别名
 * @method string getTriggerName() 获取触发器名称
 * @method void setTriggerName(string $TriggerName) 设置触发器名称
 * @method string getType() 获取触发器类型
 * @method void setType(string $Type) 设置触发器类型
 * @method string getTriggerDesc() 获取触发器详细配置
 * @method void setTriggerDesc(string $TriggerDesc) 设置触发器详细配置
 * @method string getAvailableStatus() 获取触发器是否可用
 * @method void setAvailableStatus(string $AvailableStatus) 设置触发器是否可用
 * @method string getCustomArgument() 获取客户自定义参数
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCustomArgument(string $CustomArgument) 设置客户自定义参数
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getAddTime() 获取触发器创建时间
 * @method void setAddTime(string $AddTime) 设置触发器创建时间
 * @method string getModTime() 获取触发器最后修改时间
 * @method void setModTime(string $ModTime) 设置触发器最后修改时间
 */
class TriggerInfo extends AbstractModel
{
    /**
     * @var integer 使能开关
     */
    public $Enable;

    /**
     * @var string 函数版本或别名
     */
    public $Qualifier;

    /**
     * @var string 触发器名称
     */
    public $TriggerName;

    /**
     * @var string 触发器类型
     */
    public $Type;

    /**
     * @var string 触发器详细配置
     */
    public $TriggerDesc;

    /**
     * @var string 触发器是否可用
     */
    public $AvailableStatus;

    /**
     * @var string 客户自定义参数
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CustomArgument;

    /**
     * @var string 触发器创建时间
     */
    public $AddTime;

    /**
     * @var string 触发器最后修改时间
     */
    public $ModTime;

    /**
     * @param integer $Enable 使能开关
     * @param string $Qualifier 函数版本或别名
     * @param string $TriggerName 触发器名称
     * @param string $Type 触发器类型
     * @param string $TriggerDesc 触发器详细配置
     * @param string $AvailableStatus 触发器是否可用
     * @param string $CustomArgument 客户自定义参数
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $AddTime 触发器创建时间
     * @param string $ModTime 触发器最后修改时间
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
        if (array_key_exists("Enable",$param) and $param["Enable"] !== null) {
            $this->Enable = $param["Enable"];
        }

        if (array_key_exists("Qualifier",$param) and $param["Qualifier"] !== null) {
            $this->Qualifier = $param["Qualifier"];
        }

        if (array_key_exists("TriggerName",$param) and $param["TriggerName"] !== null) {
            $this->TriggerName = $param["TriggerName"];
        }

        if (array_key_exists("Type",$param) and $param["Type"] !== null) {
            $this->Type = $param["Type"];
        }

        if (array_key_exists("TriggerDesc",$param) and $param["TriggerDesc"] !== null) {
            $this->TriggerDesc = $param["TriggerDesc"];
        }

        if (array_key_exists("AvailableStatus",$param) and $param["AvailableStatus"] !== null) {
            $this->AvailableStatus = $param["AvailableStatus"];
        }

        if (array_key_exists("CustomArgument",$param) and $param["CustomArgument"] !== null) {
            $this->CustomArgument = $param["CustomArgument"];
        }

        if (array_key_exists("AddTime",$param) and $param["AddTime"] !== null) {
            $this->AddTime = $param["AddTime"];
        }

        if (array_key_exists("ModTime",$param) and $param["ModTime"] !== null) {
            $this->ModTime = $param["ModTime"];
        }
    }
}
