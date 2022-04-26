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
 * ImportMediaToProject请求参数结构体
 *
 * @method string getPlatform() 获取平台名称，指定访问的平台。
 * @method void setPlatform(string $Platform) 设置平台名称，指定访问的平台。
 * @method string getProjectId() 获取项目 Id。
 * @method void setProjectId(string $ProjectId) 设置项目 Id。
 * @method string getVodFileId() 获取云点播媒资 FileId。
 * @method void setVodFileId(string $VodFileId) 设置云点播媒资 FileId。
 * @method string getName() 获取素材名称，不能超过30个字符。
 * @method void setName(string $Name) 设置素材名称，不能超过30个字符。
 * @method integer getPreProcessDefinition() 获取素材预处理任务模板 ID，取值：
<li>10：进行编辑预处理。</li>
注意：如果填0则不进行处理。
 * @method void setPreProcessDefinition(integer $PreProcessDefinition) 设置素材预处理任务模板 ID，取值：
<li>10：进行编辑预处理。</li>
注意：如果填0则不进行处理。
 */
class ImportMediaToProjectRequest extends AbstractModel
{
    /**
     * @var string 平台名称，指定访问的平台。
     */
    public $Platform;

    /**
     * @var string 项目 Id。
     */
    public $ProjectId;

    /**
     * @var string 云点播媒资 FileId。
     */
    public $VodFileId;

    /**
     * @var string 素材名称，不能超过30个字符。
     */
    public $Name;

    /**
     * @var integer 素材预处理任务模板 ID，取值：
<li>10：进行编辑预处理。</li>
注意：如果填0则不进行处理。
     */
    public $PreProcessDefinition;

    /**
     * @param string $Platform 平台名称，指定访问的平台。
     * @param string $ProjectId 项目 Id。
     * @param string $VodFileId 云点播媒资 FileId。
     * @param string $Name 素材名称，不能超过30个字符。
     * @param integer $PreProcessDefinition 素材预处理任务模板 ID，取值：
<li>10：进行编辑预处理。</li>
注意：如果填0则不进行处理。
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

        if (array_key_exists("ProjectId",$param) and $param["ProjectId"] !== null) {
            $this->ProjectId = $param["ProjectId"];
        }

        if (array_key_exists("VodFileId",$param) and $param["VodFileId"] !== null) {
            $this->VodFileId = $param["VodFileId"];
        }

        if (array_key_exists("Name",$param) and $param["Name"] !== null) {
            $this->Name = $param["Name"];
        }

        if (array_key_exists("PreProcessDefinition",$param) and $param["PreProcessDefinition"] !== null) {
            $this->PreProcessDefinition = $param["PreProcessDefinition"];
        }
    }
}
