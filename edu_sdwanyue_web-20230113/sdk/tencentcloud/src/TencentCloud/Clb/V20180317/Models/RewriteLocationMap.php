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
namespace TencentCloud\Clb\V20180317\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 转发规则之间的重定向关系
 *
 * @method string getSourceLocationId() 获取源转发规则ID
 * @method void setSourceLocationId(string $SourceLocationId) 设置源转发规则ID
 * @method string getTargetLocationId() 获取重定向至的目标转发规则ID
 * @method void setTargetLocationId(string $TargetLocationId) 设置重定向至的目标转发规则ID
 */
class RewriteLocationMap extends AbstractModel
{
    /**
     * @var string 源转发规则ID
     */
    public $SourceLocationId;

    /**
     * @var string 重定向至的目标转发规则ID
     */
    public $TargetLocationId;

    /**
     * @param string $SourceLocationId 源转发规则ID
     * @param string $TargetLocationId 重定向至的目标转发规则ID
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
        if (array_key_exists("SourceLocationId",$param) and $param["SourceLocationId"] !== null) {
            $this->SourceLocationId = $param["SourceLocationId"];
        }

        if (array_key_exists("TargetLocationId",$param) and $param["TargetLocationId"] !== null) {
            $this->TargetLocationId = $param["TargetLocationId"];
        }
    }
}
