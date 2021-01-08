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
namespace TencentCloud\Cdn\V20180606\Models;
use TencentCloud\Common\AbstractModel;

/**
 * MagAge 规则配置
 *
 * @method string getMaxAgeType() 获取规则类型：
all：所有文件生效
file：指定文件后缀生效
directory：指定路径生效
path：指定绝对路径生效
 * @method void setMaxAgeType(string $MaxAgeType) 设置规则类型：
all：所有文件生效
file：指定文件后缀生效
directory：指定路径生效
path：指定绝对路径生效
 * @method array getMaxAgeContents() 获取MaxAgeType 对应类型下的匹配内容：
all 时填充 *
file 时填充后缀名，如 jpg、txt
directory 时填充路径，如 /xxx/test/
path 时填充绝对路径，如 /xxx/test.html
 * @method void setMaxAgeContents(array $MaxAgeContents) 设置MaxAgeType 对应类型下的匹配内容：
all 时填充 *
file 时填充后缀名，如 jpg、txt
directory 时填充路径，如 /xxx/test/
path 时填充绝对路径，如 /xxx/test.html
 * @method integer getMaxAgeTime() 获取MaxAge 时间设置，单位秒
 * @method void setMaxAgeTime(integer $MaxAgeTime) 设置MaxAge 时间设置，单位秒
 */
class MaxAgeRule extends AbstractModel
{
    /**
     * @var string 规则类型：
all：所有文件生效
file：指定文件后缀生效
directory：指定路径生效
path：指定绝对路径生效
     */
    public $MaxAgeType;

    /**
     * @var array MaxAgeType 对应类型下的匹配内容：
all 时填充 *
file 时填充后缀名，如 jpg、txt
directory 时填充路径，如 /xxx/test/
path 时填充绝对路径，如 /xxx/test.html
     */
    public $MaxAgeContents;

    /**
     * @var integer MaxAge 时间设置，单位秒
     */
    public $MaxAgeTime;

    /**
     * @param string $MaxAgeType 规则类型：
all：所有文件生效
file：指定文件后缀生效
directory：指定路径生效
path：指定绝对路径生效
     * @param array $MaxAgeContents MaxAgeType 对应类型下的匹配内容：
all 时填充 *
file 时填充后缀名，如 jpg、txt
directory 时填充路径，如 /xxx/test/
path 时填充绝对路径，如 /xxx/test.html
     * @param integer $MaxAgeTime MaxAge 时间设置，单位秒
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
        if (array_key_exists("MaxAgeType",$param) and $param["MaxAgeType"] !== null) {
            $this->MaxAgeType = $param["MaxAgeType"];
        }

        if (array_key_exists("MaxAgeContents",$param) and $param["MaxAgeContents"] !== null) {
            $this->MaxAgeContents = $param["MaxAgeContents"];
        }

        if (array_key_exists("MaxAgeTime",$param) and $param["MaxAgeTime"] !== null) {
            $this->MaxAgeTime = $param["MaxAgeTime"];
        }
    }
}
