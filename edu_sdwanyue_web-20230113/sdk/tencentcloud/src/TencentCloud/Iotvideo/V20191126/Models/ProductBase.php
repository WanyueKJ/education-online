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
 * 产品信息摘要
 *
 * @method string getProductId() 获取产品ID
 * @method void setProductId(string $ProductId) 设置产品ID
 * @method string getProductModel() 获取产器型号(APP产品,为APP包名)
 * @method void setProductModel(string $ProductModel) 设置产器型号(APP产品,为APP包名)
 * @method string getProductName() 获取产品名称
 * @method void setProductName(string $ProductName) 设置产品名称
 * @method string getProductDescription() 获取产品描述信息
 * @method void setProductDescription(string $ProductDescription) 设置产品描述信息
 * @method integer getCreateTime() 获取创建时间，UNIX 时间戳，单位秒
 * @method void setCreateTime(integer $CreateTime) 设置创建时间，UNIX 时间戳，单位秒
 * @method integer getIotModelRevision() 获取物模型发布版本号,0代表物模型尚未发布
 * @method void setIotModelRevision(integer $IotModelRevision) 设置物模型发布版本号,0代表物模型尚未发布
 * @method string getSecretKey() 获取产品密钥
 * @method void setSecretKey(string $SecretKey) 设置产品密钥
 */
class ProductBase extends AbstractModel
{
    /**
     * @var string 产品ID
     */
    public $ProductId;

    /**
     * @var string 产器型号(APP产品,为APP包名)
     */
    public $ProductModel;

    /**
     * @var string 产品名称
     */
    public $ProductName;

    /**
     * @var string 产品描述信息
     */
    public $ProductDescription;

    /**
     * @var integer 创建时间，UNIX 时间戳，单位秒
     */
    public $CreateTime;

    /**
     * @var integer 物模型发布版本号,0代表物模型尚未发布
     */
    public $IotModelRevision;

    /**
     * @var string 产品密钥
     */
    public $SecretKey;

    /**
     * @param string $ProductId 产品ID
     * @param string $ProductModel 产器型号(APP产品,为APP包名)
     * @param string $ProductName 产品名称
     * @param string $ProductDescription 产品描述信息
     * @param integer $CreateTime 创建时间，UNIX 时间戳，单位秒
     * @param integer $IotModelRevision 物模型发布版本号,0代表物模型尚未发布
     * @param string $SecretKey 产品密钥
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

        if (array_key_exists("ProductModel",$param) and $param["ProductModel"] !== null) {
            $this->ProductModel = $param["ProductModel"];
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
    }
}
