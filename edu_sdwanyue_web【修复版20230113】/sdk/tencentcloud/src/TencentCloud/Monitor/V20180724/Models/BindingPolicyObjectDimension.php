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
namespace TencentCloud\Monitor\V20180724\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 策略绑定实例维度信息
 *
 * @method string getRegion() 获取地域名
 * @method void setRegion(string $Region) 设置地域名
 * @method integer getRegionId() 获取地域ID
 * @method void setRegionId(integer $RegionId) 设置地域ID
 * @method string getDimensions() 获取维度信息
 * @method void setDimensions(string $Dimensions) 设置维度信息
 * @method string getEventDimensions() 获取事件维度信息
 * @method void setEventDimensions(string $EventDimensions) 设置事件维度信息
 */
class BindingPolicyObjectDimension extends AbstractModel
{
    /**
     * @var string 地域名
     */
    public $Region;

    /**
     * @var integer 地域ID
     */
    public $RegionId;

    /**
     * @var string 维度信息
     */
    public $Dimensions;

    /**
     * @var string 事件维度信息
     */
    public $EventDimensions;

    /**
     * @param string $Region 地域名
     * @param integer $RegionId 地域ID
     * @param string $Dimensions 维度信息
     * @param string $EventDimensions 事件维度信息
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
        if (array_key_exists("Region",$param) and $param["Region"] !== null) {
            $this->Region = $param["Region"];
        }

        if (array_key_exists("RegionId",$param) and $param["RegionId"] !== null) {
            $this->RegionId = $param["RegionId"];
        }

        if (array_key_exists("Dimensions",$param) and $param["Dimensions"] !== null) {
            $this->Dimensions = $param["Dimensions"];
        }

        if (array_key_exists("EventDimensions",$param) and $param["EventDimensions"] !== null) {
            $this->EventDimensions = $param["EventDimensions"];
        }
    }
}
