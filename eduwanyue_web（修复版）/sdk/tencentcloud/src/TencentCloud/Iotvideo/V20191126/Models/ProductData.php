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
 * 产品信息
 *
 * @method string getProductId() 获取产品ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProductId(string $ProductId) 设置产品ID
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProductName() 获取产品名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProductName(string $ProductName) 设置产品名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProductDescription() 获取产品描述信息
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProductDescription(string $ProductDescription) 设置产品描述信息
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getCreateTime() 获取创建时间，UNIX 时间戳，单位秒
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(integer $CreateTime) 设置创建时间，UNIX 时间戳，单位秒
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getIotModelRevision() 获取物模型发布版本号,0代表物模型尚未发布
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setIotModelRevision(integer $IotModelRevision) 设置物模型发布版本号,0代表物模型尚未发布
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getSecretKey() 获取产品密钥
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSecretKey(string $SecretKey) 设置产品密钥
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getFeatures() 获取设备功能码
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFeatures(array $Features) 设置设备功能码
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProductModel() 获取产器型号(APP产品,为APP包名)
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProductModel(string $ProductModel) 设置产器型号(APP产品,为APP包名)
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getChipManufactureId() 获取主芯片厂商id
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setChipManufactureId(string $ChipManufactureId) 设置主芯片厂商id
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getChipId() 获取主芯片型号
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setChipId(string $ChipId) 设置主芯片型号
注意：此字段可能返回 null，表示取不到有效值。
 */
class ProductData extends AbstractModel
{
    /**
     * @var string 产品ID
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProductId;

    /**
     * @var string 产品名称
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProductName;

    /**
     * @var string 产品描述信息
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProductDescription;

    /**
     * @var integer 创建时间，UNIX 时间戳，单位秒
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var integer 物模型发布版本号,0代表物模型尚未发布
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $IotModelRevision;

    /**
     * @var string 产品密钥
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $SecretKey;

    /**
     * @var array 设备功能码
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Features;

    /**
     * @var string 产器型号(APP产品,为APP包名)
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProductModel;

    /**
     * @var string 主芯片厂商id
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ChipManufactureId;

    /**
     * @var string 主芯片型号
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ChipId;

    /**
     * @param string $ProductId 产品ID
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ProductName 产品名称
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ProductDescription 产品描述信息
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $CreateTime 创建时间，UNIX 时间戳，单位秒
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $IotModelRevision 物模型发布版本号,0代表物模型尚未发布
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $SecretKey 产品密钥
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $Features 设备功能码
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ProductModel 产器型号(APP产品,为APP包名)
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ChipManufactureId 主芯片厂商id
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ChipId 主芯片型号
注意：此字段可能返回 null，表示取不到有效值。
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

        if (array_key_exists("ProductName",$param) and $param["ProductName"] !== null) {
            $this->ProductName = $param["ProductName"];
        }

        if (array_key_exists("ProductDescription",$param) and $param["ProductDescription"] !== null) {
            $this->ProductDescription = $param["ProductDescription"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("IotModelRevision",$param) and $param["IotModelRevision"] !== null) {
            $this->IotModelRevision = $param["IotModelRevision"];
        }

        if (array_key_exists("SecretKey",$param) and $param["SecretKey"] !== null) {
            $this->SecretKey = $param["SecretKey"];
        }

        if (array_key_exists("Features",$param) and $param["Features"] !== null) {
            $this->Features = $param["Features"];
        }

        if (array_key_exists("ProductModel",$param) and $param["ProductModel"] !== null) {
            $this->ProductModel = $param["ProductModel"];
        }

        if (array_key_exists("ChipManufactureId",$param) and $param["ChipManufactureId"] !== null) {
            $this->ChipManufactureId = $param["ChipManufactureId"];
        }

        if (array_key_exists("ChipId",$param) and $param["ChipId"] !== null) {
            $this->ChipId = $param["ChipId"];
        }
    }
}
