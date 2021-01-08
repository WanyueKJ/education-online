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
namespace TencentCloud\Ecm\V20190719\Models;
use TencentCloud\Common\AbstractModel;

/**
 * AllocateAddresses请求参数结构体
 *
 * @method string getEcmRegion() 获取ECM 地域
 * @method void setEcmRegion(string $EcmRegion) 设置ECM 地域
 * @method integer getAddressCount() 获取EIP数量。默认值：1。
 * @method void setAddressCount(integer $AddressCount) 设置EIP数量。默认值：1。
 * @method string getInternetServiceProvider() 获取CMCC：中国移动
CTCC：中国电信
CUCC：中国联通
 * @method void setInternetServiceProvider(string $InternetServiceProvider) 设置CMCC：中国移动
CTCC：中国电信
CUCC：中国联通
 * @method integer getInternetMaxBandwidthOut() 获取1 Mbps 至 5000 Mbps，默认值：1 Mbps。
 * @method void setInternetMaxBandwidthOut(integer $InternetMaxBandwidthOut) 设置1 Mbps 至 5000 Mbps，默认值：1 Mbps。
 * @method array getTags() 获取需要关联的标签列表。
 * @method void setTags(array $Tags) 设置需要关联的标签列表。
 */
class AllocateAddressesRequest extends AbstractModel
{
    /**
     * @var string ECM 地域
     */
    public $EcmRegion;

    /**
     * @var integer EIP数量。默认值：1。
     */
    public $AddressCount;

    /**
     * @var string CMCC：中国移动
CTCC：中国电信
CUCC：中国联通
     */
    public $InternetServiceProvider;

    /**
     * @var integer 1 Mbps 至 5000 Mbps，默认值：1 Mbps。
     */
    public $InternetMaxBandwidthOut;

    /**
     * @var array 需要关联的标签列表。
     */
    public $Tags;

    /**
     * @param string $EcmRegion ECM 地域
     * @param integer $AddressCount EIP数量。默认值：1。
     * @param string $InternetServiceProvider CMCC：中国移动
CTCC：中国电信
CUCC：中国联通
     * @param integer $InternetMaxBandwidthOut 1 Mbps 至 5000 Mbps，默认值：1 Mbps。
     * @param array $Tags 需要关联的标签列表。
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
        if (array_key_exists("EcmRegion",$param) and $param["EcmRegion"] !== null) {
            $this->EcmRegion = $param["EcmRegion"];
        }

        if (array_key_exists("AddressCount",$param) and $param["AddressCount"] !== null) {
            $this->AddressCount = $param["AddressCount"];
        }

        if (array_key_exists("InternetServiceProvider",$param) and $param["InternetServiceProvider"] !== null) {
            $this->InternetServiceProvider = $param["InternetServiceProvider"];
        }

        if (array_key_exists("InternetMaxBandwidthOut",$param) and $param["InternetMaxBandwidthOut"] !== null) {
            $this->InternetMaxBandwidthOut = $param["InternetMaxBandwidthOut"];
        }

        if (array_key_exists("Tags",$param) and $param["Tags"] !== null) {
            $this->Tags = [];
            foreach ($param["Tags"] as $key => $value){
                $obj = new Tag();
                $obj->deserialize($value);
                array_push($this->Tags, $obj);
            }
        }
    }
}
