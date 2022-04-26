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
namespace TencentCloud\Cpdp\V20190820\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CheckAcct请求参数结构体
 *
 * @method string getMidasAppId() 获取聚鑫分配的支付主MidasAppId
 * @method void setMidasAppId(string $MidasAppId) 设置聚鑫分配的支付主MidasAppId
 * @method string getSubAppId() 获取聚鑫计费SubAppId，代表子商户
 * @method void setSubAppId(string $SubAppId) 设置聚鑫计费SubAppId，代表子商户
 * @method integer getBindType() 获取1：小额鉴权
2：短信校验鉴权
 * @method void setBindType(integer $BindType) 设置1：小额鉴权
2：短信校验鉴权
 * @method string getSettleAcctNo() 获取结算账户账号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method void setSettleAcctNo(string $SettleAcctNo) 设置结算账户账号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method string getMidasSecretId() 获取聚鑫分配的安全ID
 * @method void setMidasSecretId(string $MidasSecretId) 设置聚鑫分配的安全ID
 * @method string getMidasSignature() 获取按照聚鑫安全密钥计算的签名
 * @method void setMidasSignature(string $MidasSignature) 设置按照聚鑫安全密钥计算的签名
 * @method string getCheckCode() 获取短信验证码
BindType==2必填
 * @method void setCheckCode(string $CheckCode) 设置短信验证码
BindType==2必填
 * @method string getCurrencyType() 获取币种 RMB
BindType==1必填
 * @method void setCurrencyType(string $CurrencyType) 设置币种 RMB
BindType==1必填
 * @method integer getCurrencyUnit() 获取单位
1：元，2：角，3：分
BindType==1必填
 * @method void setCurrencyUnit(integer $CurrencyUnit) 设置单位
1：元，2：角，3：分
BindType==1必填
 * @method string getCurrencyAmt() 获取金额
BindType==1必填
 * @method void setCurrencyAmt(string $CurrencyAmt) 设置金额
BindType==1必填
 */
class CheckAcctRequest extends AbstractModel
{
    /**
     * @var string 聚鑫分配的支付主MidasAppId
     */
    public $MidasAppId;

    /**
     * @var string 聚鑫计费SubAppId，代表子商户
     */
    public $SubAppId;

    /**
     * @var integer 1：小额鉴权
2：短信校验鉴权
     */
    public $BindType;

    /**
     * @var string 结算账户账号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     */
    public $SettleAcctNo;

    /**
     * @var string 聚鑫分配的安全ID
     */
    public $MidasSecretId;

    /**
     * @var string 按照聚鑫安全密钥计算的签名
     */
    public $MidasSignature;

    /**
     * @var string 短信验证码
BindType==2必填
     */
    public $CheckCode;

    /**
     * @var string 币种 RMB
BindType==1必填
     */
    public $CurrencyType;

    /**
     * @var integer 单位
1：元，2：角，3：分
BindType==1必填
     */
    public $CurrencyUnit;

    /**
     * @var string 金额
BindType==1必填
     */
    public $CurrencyAmt;

    /**
     * @param string $MidasAppId 聚鑫分配的支付主MidasAppId
     * @param string $SubAppId 聚鑫计费SubAppId，代表子商户
     * @param integer $BindType 1：小额鉴权
2：短信校验鉴权
     * @param string $SettleAcctNo 结算账户账号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     * @param string $MidasSecretId 聚鑫分配的安全ID
     * @param string $MidasSignature 按照聚鑫安全密钥计算的签名
     * @param string $CheckCode 短信验证码
BindType==2必填
     * @param string $CurrencyType 币种 RMB
BindType==1必填
     * @param integer $CurrencyUnit 单位
1：元，2：角，3：分
BindType==1必填
     * @param string $CurrencyAmt 金额
BindType==1必填
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
        if (array_key_exists("MidasAppId",$param) and $param["MidasAppId"] !== null) {
            $this->MidasAppId = $param["MidasAppId"];
        }

        if (array_key_exists("SubAppId",$param) and $param["SubAppId"] !== null) {
            $this->SubAppId = $param["SubAppId"];
        }

        if (array_key_exists("BindType",$param) and $param["BindType"] !== null) {
            $this->BindType = $param["BindType"];
        }

        if (array_key_exists("SettleAcctNo",$param) and $param["SettleAcctNo"] !== null) {
            $this->SettleAcctNo = $param["SettleAcctNo"];
        }

        if (array_key_exists("MidasSecretId",$param) and $param["MidasSecretId"] !== null) {
            $this->MidasSecretId = $param["MidasSecretId"];
        }

        if (array_key_exists("MidasSignature",$param) and $param["MidasSignature"] !== null) {
            $this->MidasSignature = $param["MidasSignature"];
        }

        if (array_key_exists("CheckCode",$param) and $param["CheckCode"] !== null) {
            $this->CheckCode = $param["CheckCode"];
        }

        if (array_key_exists("CurrencyType",$param) and $param["CurrencyType"] !== null) {
            $this->CurrencyType = $param["CurrencyType"];
        }

        if (array_key_exists("CurrencyUnit",$param) and $param["CurrencyUnit"] !== null) {
            $this->CurrencyUnit = $param["CurrencyUnit"];
        }

        if (array_key_exists("CurrencyAmt",$param) and $param["CurrencyAmt"] !== null) {
            $this->CurrencyAmt = $param["CurrencyAmt"];
        }
    }
}
