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
 * CreateAcct请求参数结构体
 *
 * @method string getMidasAppId() 获取聚鑫平台分配的支付MidasAppId
 * @method void setMidasAppId(string $MidasAppId) 设置聚鑫平台分配的支付MidasAppId
 * @method string getSubMchId() 获取业务平台的子商户ID，唯一
 * @method void setSubMchId(string $SubMchId) 设置业务平台的子商户ID，唯一
 * @method string getSubMchName() 获取子商户名称
 * @method void setSubMchName(string $SubMchName) 设置子商户名称
 * @method string getAddress() 获取子商户地址
 * @method void setAddress(string $Address) 设置子商户地址
 * @method string getContact() 获取子商户联系人
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method void setContact(string $Contact) 设置子商户联系人
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method string getMobile() 获取联系人手机号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method void setMobile(string $Mobile) 设置联系人手机号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method string getEmail() 获取邮箱 
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method void setEmail(string $Email) 设置邮箱 
<敏感信息>加密详见《商户端接口敏感信息加密说明》
 * @method string getMidasSecretId() 获取聚鑫分配的安全ID
 * @method void setMidasSecretId(string $MidasSecretId) 设置聚鑫分配的安全ID
 * @method string getMidasSignature() 获取按照聚鑫安全密钥计算的签名
 * @method void setMidasSignature(string $MidasSignature) 设置按照聚鑫安全密钥计算的签名
 * @method string getSubMchType() 获取子商户类型：
个人: personal
企业：enterprise
缺省： enterprise
 * @method void setSubMchType(string $SubMchType) 设置子商户类型：
个人: personal
企业：enterprise
缺省： enterprise
 * @method string getShortName() 获取不填则默认子商户名称
 * @method void setShortName(string $ShortName) 设置不填则默认子商户名称
 * @method string getSubMerchantMemberType() 获取子商户会员类型：
general:普通子账户
merchant:商户子账户
缺省： general
 * @method void setSubMerchantMemberType(string $SubMerchantMemberType) 设置子商户会员类型：
general:普通子账户
merchant:商户子账户
缺省： general
 */
class CreateAcctRequest extends AbstractModel
{
    /**
     * @var string 聚鑫平台分配的支付MidasAppId
     */
    public $MidasAppId;

    /**
     * @var string 业务平台的子商户ID，唯一
     */
    public $SubMchId;

    /**
     * @var string 子商户名称
     */
    public $SubMchName;

    /**
     * @var string 子商户地址
     */
    public $Address;

    /**
     * @var string 子商户联系人
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     */
    public $Contact;

    /**
     * @var string 联系人手机号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     */
    public $Mobile;

    /**
     * @var string 邮箱 
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     */
    public $Email;

    /**
     * @var string 聚鑫分配的安全ID
     */
    public $MidasSecretId;

    /**
     * @var string 按照聚鑫安全密钥计算的签名
     */
    public $MidasSignature;

    /**
     * @var string 子商户类型：
个人: personal
企业：enterprise
缺省： enterprise
     */
    public $SubMchType;

    /**
     * @var string 不填则默认子商户名称
     */
    public $ShortName;

    /**
     * @var string 子商户会员类型：
general:普通子账户
merchant:商户子账户
缺省： general
     */
    public $SubMerchantMemberType;

    /**
     * @param string $MidasAppId 聚鑫平台分配的支付MidasAppId
     * @param string $SubMchId 业务平台的子商户ID，唯一
     * @param string $SubMchName 子商户名称
     * @param string $Address 子商户地址
     * @param string $Contact 子商户联系人
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     * @param string $Mobile 联系人手机号
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     * @param string $Email 邮箱 
<敏感信息>加密详见《商户端接口敏感信息加密说明》
     * @param string $MidasSecretId 聚鑫分配的安全ID
     * @param string $MidasSignature 按照聚鑫安全密钥计算的签名
     * @param string $SubMchType 子商户类型：
个人: personal
企业：enterprise
缺省： enterprise
     * @param string $ShortName 不填则默认子商户名称
     * @param string $SubMerchantMemberType 子商户会员类型：
general:普通子账户
merchant:商户子账户
缺省： general
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

        if (array_key_exists("SubMchId",$param) and $param["SubMchId"] !== null) {
            $this->SubMchId = $param["SubMchId"];
        }

        if (array_key_exists("SubMchName",$param) and $param["SubMchName"] !== null) {
            $this->SubMchName = $param["SubMchName"];
        }

        if (array_key_exists("Address",$param) and $param["Address"] !== null) {
            $this->Address = $param["Address"];
        }

        if (array_key_exists("Contact",$param) and $param["Contact"] !== null) {
            $this->Contact = $param["Contact"];
        }

        if (array_key_exists("Mobile",$param) and $param["Mobile"] !== null) {
            $this->Mobile = $param["Mobile"];
        }

        if (array_key_exists("Email",$param) and $param["Email"] !== null) {
            $this->Email = $param["Email"];
        }

        if (array_key_exists("MidasSecretId",$param) and $param["MidasSecretId"] !== null) {
            $this->MidasSecretId = $param["MidasSecretId"];
        }

        if (array_key_exists("MidasSignature",$param) and $param["MidasSignature"] !== null) {
            $this->MidasSignature = $param["MidasSignature"];
        }

        if (array_key_exists("SubMchType",$param) and $param["SubMchType"] !== null) {
            $this->SubMchType = $param["SubMchType"];
        }

        if (array_key_exists("ShortName",$param) and $param["ShortName"] !== null) {
            $this->ShortName = $param["ShortName"];
        }

        if (array_key_exists("SubMerchantMemberType",$param) and $param["SubMerchantMemberType"] !== null) {
            $this->SubMerchantMemberType = $param["SubMerchantMemberType"];
        }
    }
}
