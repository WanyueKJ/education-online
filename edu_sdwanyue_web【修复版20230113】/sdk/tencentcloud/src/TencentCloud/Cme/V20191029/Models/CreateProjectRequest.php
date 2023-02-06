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
namespace TencentCloud\Cme\V20191029\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateProject请求参数结构体
 *
 * @method string getPlatform() 获取平台名称，指定访问的平台。
 * @method void setPlatform(string $Platform) 设置平台名称，指定访问的平台。
 * @method string getCategory() 获取项目类别，取值有：
<li>VIDEO_EDIT：视频编辑。</li>
 * @method void setCategory(string $Category) 设置项目类别，取值有：
<li>VIDEO_EDIT：视频编辑。</li>
 * @method string getName() 获取项目名称，不可超过30个字符。
 * @method void setName(string $Name) 设置项目名称，不可超过30个字符。
 * @method string getAspectRatio() 获取画布宽高比，取值有：
<li>16:9；</li>
<li>9:16。</li>
 * @method void setAspectRatio(string $AspectRatio) 设置画布宽高比，取值有：
<li>16:9；</li>
<li>9:16。</li>
 * @method Entity getOwner() 获取归属者。
 * @method void setOwner(Entity $Owner) 设置归属者。
 */
class CreateProjectRequest extends AbstractModel
{
    /**
     * @var string 平台名称，指定访问的平台。
     */
    public $Platform;

    /**
     * @var string 项目类别，取值有：
<li>VIDEO_EDIT：视频编辑。</li>
     */
    public $Category;

    /**
     * @var string 项目名称，不可超过30个字符。
     */
    public $Name;

    /**
     * @var string 画布宽高比，取值有：
<li>16:9；</li>
<li>9:16。</li>
     */
    public $AspectRatio;

    /**
     * @var Entity 归属者。
     */
    public $Owner;

    /**
     * @param string $Platform 平台名称，指定访问的平台。
     * @param string $Category 项目类别，取值有：
<li>VIDEO_EDIT：视频编辑。</li>
     * @param string $Name 项目名称，不可超过30个字符。
     * @param string $AspectRatio 画布宽高比，取值有：
<li>16:9；</li>
<li>9:16。</li>
     * @param Entity $Owner 归属者。
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
        if (array_key_exists("Platform",$param) and $param["Platform"] !== null) {
            $this->Platform = $param["Platform"];
        }

        if (array_key_exists("Category",$param) and $param["Category"] !== null) {
            $this->Category = $param["Category"];
        }

        if (array_key_exists("Name",$param) and $param["Name"] !== null) {
            $this->Name = $param["Name"];
        }

        if (array_key_exists("AspectRatio",$param) and $param["AspectRatio"] !== null) {
            $this->AspectRatio = $param["AspectRatio"];
        }

        if (array_key_exists("Owner",$param) and $param["Owner"] !== null) {
            $this->Owner = new Entity();
            $this->Owner->deserialize($param["Owner"]);
        }
    }
}
