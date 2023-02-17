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
namespace TencentCloud\Vpc\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * SetCcnRegionBandwidthLimits请求参数结构体
 *
 * @method string getCcnId() 获取CCN实例ID。形如：ccn-f49l6u0z。
 * @method void setCcnId(string $CcnId) 设置CCN实例ID。形如：ccn-f49l6u0z。
 * @method array getCcnRegionBandwidthLimits() 获取云联网（CCN）各地域出带宽上限。
 * @method void setCcnRegionBandwidthLimits(array $CcnRegionBandwidthLimits) 设置云联网（CCN）各地域出带宽上限。
 */
class SetCcnRegionBandwidthLimitsRequest extends AbstractModel
{
    /**
     * @var string CCN实例ID。形如：ccn-f49l6u0z。
     */
    public $CcnId;

    /**
     * @var array 云联网（CCN）各地域出带宽上限。
     */
    public $CcnRegionBandwidthLimits;

    /**
     * @param string $CcnId CCN实例ID。形如：ccn-f49l6u0z。
     * @param array $CcnRegionBandwidthLimits 云联网（CCN）各地域出带宽上限。
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
        if (array_key_exists("CcnId",$param) and $param["CcnId"] !== null) {
            $this->CcnId = $param["CcnId"];
        }

        if (array_key_exists("CcnRegionBandwidthLimits",$param) and $param["CcnRegionBandwidthLimits"] !== null) {
            $this->CcnRegionBandwidthLimits = [];
            foreach ($param["CcnRegionBandwidthLimits"] as $key => $value){
                $obj = new CcnRegionBandwidthLimit();
                $obj->deserialize($value);
                array_push($this->CcnRegionBandwidthLimits, $obj);
            }
        }
    }
}
