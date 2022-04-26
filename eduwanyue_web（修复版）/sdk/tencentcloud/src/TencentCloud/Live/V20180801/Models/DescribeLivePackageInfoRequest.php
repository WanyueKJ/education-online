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
namespace TencentCloud\Live\V20180801\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeLivePackageInfo请求参数结构体
 *
 * @method integer getPackageType() 获取包类型，可选值：
0：流量包；
1：转码包。
 * @method void setPackageType(integer $PackageType) 设置包类型，可选值：
0：流量包；
1：转码包。
 */
class DescribeLivePackageInfoRequest extends AbstractModel
{
    /**
     * @var integer 包类型，可选值：
0：流量包；
1：转码包。
     */
    public $PackageType;

    /**
     * @param integer $PackageType 包类型，可选值：
0：流量包；
1：转码包。
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
        if (array_key_exists("PackageType",$param) and $param["PackageType"] !== null) {
            $this->PackageType = $param["PackageType"];
        }
    }
}
