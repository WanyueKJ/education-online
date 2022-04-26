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
namespace TencentCloud\Gaap\V20180529\Models;
use TencentCloud\Common\AbstractModel;

/**
 * ModifySecurityRule请求参数结构体
 *
 * @method string getRuleId() 获取规则ID
 * @method void setRuleId(string $RuleId) 设置规则ID
 * @method string getAliasName() 获取规则名：不得超过30个字符，超过部分会被截断。
 * @method void setAliasName(string $AliasName) 设置规则名：不得超过30个字符，超过部分会被截断。
 * @method string getPolicyId() 获取安全策略ID
 * @method void setPolicyId(string $PolicyId) 设置安全策略ID
 */
class ModifySecurityRuleRequest extends AbstractModel
{
    /**
     * @var string 规则ID
     */
    public $RuleId;

    /**
     * @var string 规则名：不得超过30个字符，超过部分会被截断。
     */
    public $AliasName;

    /**
     * @var string 安全策略ID
     */
    public $PolicyId;

    /**
     * @param string $RuleId 规则ID
     * @param string $AliasName 规则名：不得超过30个字符，超过部分会被截断。
     * @param string $PolicyId 安全策略ID
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
        if (array_key_exists("RuleId",$param) and $param["RuleId"] !== null) {
            $this->RuleId = $param["RuleId"];
        }

        if (array_key_exists("AliasName",$param) and $param["AliasName"] !== null) {
            $this->AliasName = $param["AliasName"];
        }

        if (array_key_exists("PolicyId",$param) and $param["PolicyId"] !== null) {
            $this->PolicyId = $param["PolicyId"];
        }
    }
}
