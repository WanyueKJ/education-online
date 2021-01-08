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
namespace TencentCloud\Iotvideo\V20191126\Models;
use TencentCloud\Common\AbstractModel;

/**
 * RunOtaVersion请求参数结构体
 *
 * @method string getProductId() 获取产品ID
 * @method void setProductId(string $ProductId) 设置产品ID
 * @method string getOtaVersion() 获取固件版本号，格式为x.y.z， x，y 范围0-63，z范围1~524288
 * @method void setOtaVersion(string $OtaVersion) 设置固件版本号，格式为x.y.z， x，y 范围0-63，z范围1~524288
 * @method integer getGrayValue() 获取灰度值,取值范围0-100，为0时相当于暂停发布
 * @method void setGrayValue(integer $GrayValue) 设置灰度值,取值范围0-100，为0时相当于暂停发布
 * @method array getOldVersions() 获取指定的旧版本
 * @method void setOldVersions(array $OldVersions) 设置指定的旧版本
 * @method string getOperator() 获取操作人
 * @method void setOperator(string $Operator) 设置操作人
 */
class RunOtaVersionRequest extends AbstractModel
{
    /**
     * @var string 产品ID
     */
    public $ProductId;

    /**
     * @var string 固件版本号，格式为x.y.z， x，y 范围0-63，z范围1~524288
     */
    public $OtaVersion;

    /**
     * @var integer 灰度值,取值范围0-100，为0时相当于暂停发布
     */
    public $GrayValue;

    /**
     * @var array 指定的旧版本
     */
    public $OldVersions;

    /**
     * @var string 操作人
     */
    public $Operator;

    /**
     * @param string $ProductId 产品ID
     * @param string $OtaVersion 固件版本号，格式为x.y.z， x，y 范围0-63，z范围1~524288
     * @param integer $GrayValue 灰度值,取值范围0-100，为0时相当于暂停发布
     * @param array $OldVersions 指定的旧版本
     * @param string $Operator 操作人
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
        if (array_key_exists("ProductId",$param) and $param["ProductId"] !== null) {
            $this->ProductId = $param["ProductId"];
        }

        if (array_key_exists("OtaVersion",$param) and $param["OtaVersion"] !== null) {
            $this->OtaVersion = $param["OtaVersion"];
        }

        if (array_key_exists("GrayValue",$param) and $param["GrayValue"] !== null) {
            $this->GrayValue = $param["GrayValue"];
        }

        if (array_key_exists("OldVersions",$param) and $param["OldVersions"] !== null) {
            $this->OldVersions = $param["OldVersions"];
        }

        if (array_key_exists("Operator",$param) and $param["Operator"] !== null) {
            $this->Operator = $param["Operator"];
        }
    }
}
