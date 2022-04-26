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
namespace TencentCloud\Taf\V20200210\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 业务入参
 *
 * @method string getUid() 获取用户ID值，如微信/QQ openid，或 手机号等（如15912345687）
 * @method void setUid(string $Uid) 设置用户ID值，如微信/QQ openid，或 手机号等（如15912345687）
 * @method integer getAccountType() 获取用户账号类型 
1：QQ开放帐号 
2：微信开放账号 
4：手机号 （暂仅支持国内手机号）
10004： 手机号MD5
 * @method void setAccountType(integer $AccountType) 设置用户账号类型 
1：QQ开放帐号 
2：微信开放账号 
4：手机号 （暂仅支持国内手机号）
10004： 手机号MD5
 * @method string getUserIp() 获取用户真实外网IP
 * @method void setUserIp(string $UserIp) 设置用户真实外网IP
 * @method integer getPostTime() 获取用户操作时间戳，单位秒（格林威治时间精确到秒，如1501590972）
 * @method void setPostTime(integer $PostTime) 设置用户操作时间戳，单位秒（格林威治时间精确到秒，如1501590972）
 * @method string getAppIdU() 获取accountType是QQ或微信开放账号时，该参数必填，表示QQ或微信分配给网站或应用的appId，用来唯一标识网站或应用
 * @method void setAppIdU(string $AppIdU) 设置accountType是QQ或微信开放账号时，该参数必填，表示QQ或微信分配给网站或应用的appId，用来唯一标识网站或应用
 * @method string getNickName() 获取昵称，UTF-8 编码
 * @method void setNickName(string $NickName) 设置昵称，UTF-8 编码
 * @method string getPhoneNumber() 获取手机号。若 accountType 选4（手机号）、或10004（手机号 MD5），则无需重复填写。否则填入对应的手机号（如15912345687）
 * @method void setPhoneNumber(string $PhoneNumber) 设置手机号。若 accountType 选4（手机号）、或10004（手机号 MD5），则无需重复填写。否则填入对应的手机号（如15912345687）
 * @method string getEmailAddress() 获取用户邮箱地址（非系统自动生成）
 * @method void setEmailAddress(string $EmailAddress) 设置用户邮箱地址（非系统自动生成）
 * @method float getCookieHash() 获取用户 HTTP 请求中的 cookie 进行2次 hash 的值，只要保证相同 cookie 的 hash 值一致即可
 * @method void setCookieHash(float $CookieHash) 设置用户 HTTP 请求中的 cookie 进行2次 hash 的值，只要保证相同 cookie 的 hash 值一致即可
 * @method string getUserAgent() 获取用户HTTP请求的 userAgent
 * @method void setUserAgent(string $UserAgent) 设置用户HTTP请求的 userAgent
 * @method string getXForwardedFor() 获取用户HTTP请求中的 x_forward_for
 * @method void setXForwardedFor(string $XForwardedFor) 设置用户HTTP请求中的 x_forward_for
 * @method string getMacAddress() 获取Mac地址或设备唯一标识
 * @method void setMacAddress(string $MacAddress) 设置Mac地址或设备唯一标识
 * @method string getVendorId() 获取手机制造商ID，如果手机注册，请带上此信息
 * @method void setVendorId(string $VendorId) 设置手机制造商ID，如果手机注册，请带上此信息
 * @method string getImei() 获取手机设备号
 * @method void setImei(string $Imei) 设置手机设备号
 */
class InputDetectAccountActivity extends AbstractModel
{
    /**
     * @var string 用户ID值，如微信/QQ openid，或 手机号等（如15912345687）
     */
    public $Uid;

    /**
     * @var integer 用户账号类型 
1：QQ开放帐号 
2：微信开放账号 
4：手机号 （暂仅支持国内手机号）
10004： 手机号MD5
     */
    public $AccountType;

    /**
     * @var string 用户真实外网IP
     */
    public $UserIp;

    /**
     * @var integer 用户操作时间戳，单位秒（格林威治时间精确到秒，如1501590972）
     */
    public $PostTime;

    /**
     * @var string accountType是QQ或微信开放账号时，该参数必填，表示QQ或微信分配给网站或应用的appId，用来唯一标识网站或应用
     */
    public $AppIdU;

    /**
     * @var string 昵称，UTF-8 编码
     */
    public $NickName;

    /**
     * @var string 手机号。若 accountType 选4（手机号）、或10004（手机号 MD5），则无需重复填写。否则填入对应的手机号（如15912345687）
     */
    public $PhoneNumber;

    /**
     * @var string 用户邮箱地址（非系统自动生成）
     */
    public $EmailAddress;

    /**
     * @var float 用户 HTTP 请求中的 cookie 进行2次 hash 的值，只要保证相同 cookie 的 hash 值一致即可
     */
    public $CookieHash;

    /**
     * @var string 用户HTTP请求的 userAgent
     */
    public $UserAgent;

    /**
     * @var string 用户HTTP请求中的 x_forward_for
     */
    public $XForwardedFor;

    /**
     * @var string Mac地址或设备唯一标识
     */
    public $MacAddress;

    /**
     * @var string 手机制造商ID，如果手机注册，请带上此信息
     */
    public $VendorId;

    /**
     * @var string 手机设备号
     */
    public $Imei;

    /**
     * @param string $Uid 用户ID值，如微信/QQ openid，或 手机号等（如15912345687）
     * @param integer $AccountType 用户账号类型 
1：QQ开放帐号 
2：微信开放账号 
4：手机号 （暂仅支持国内手机号）
10004： 手机号MD5
     * @param string $UserIp 用户真实外网IP
     * @param integer $PostTime 用户操作时间戳，单位秒（格林威治时间精确到秒，如1501590972）
     * @param string $AppIdU accountType是QQ或微信开放账号时，该参数必填，表示QQ或微信分配给网站或应用的appId，用来唯一标识网站或应用
     * @param string $NickName 昵称，UTF-8 编码
     * @param string $PhoneNumber 手机号。若 accountType 选4（手机号）、或10004（手机号 MD5），则无需重复填写。否则填入对应的手机号（如15912345687）
     * @param string $EmailAddress 用户邮箱地址（非系统自动生成）
     * @param float $CookieHash 用户 HTTP 请求中的 cookie 进行2次 hash 的值，只要保证相同 cookie 的 hash 值一致即可
     * @param string $UserAgent 用户HTTP请求的 userAgent
     * @param string $XForwardedFor 用户HTTP请求中的 x_forward_for
     * @param string $MacAddress Mac地址或设备唯一标识
     * @param string $VendorId 手机制造商ID，如果手机注册，请带上此信息
     * @param string $Imei 手机设备号
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
        if (array_key_exists("Uid",$param) and $param["Uid"] !== null) {
            $this->Uid = $param["Uid"];
        }

        if (array_key_exists("AccountType",$param) and $param["AccountType"] !== null) {
            $this->AccountType = $param["AccountType"];
        }

        if (array_key_exists("UserIp",$param) and $param["UserIp"] !== null) {
            $this->UserIp = $param["UserIp"];
        }

        if (array_key_exists("PostTime",$param) and $param["PostTime"] !== null) {
            $this->PostTime = $param["PostTime"];
        }

        if (array_key_exists("AppIdU",$param) and $param["AppIdU"] !== null) {
            $this->AppIdU = $param["AppIdU"];
        }

        if (array_key_exists("NickName",$param) and $param["NickName"] !== null) {
            $this->NickName = $param["NickName"];
        }

        if (array_key_exists("PhoneNumber",$param) and $param["PhoneNumber"] !== null) {
            $this->PhoneNumber = $param["PhoneNumber"];
        }

        if (array_key_exists("EmailAddress",$param) and $param["EmailAddress"] !== null) {
            $this->EmailAddress = $param["EmailAddress"];
        }

        if (array_key_exists("CookieHash",$param) and $param["CookieHash"] !== null) {
            $this->CookieHash = $param["CookieHash"];
        }

        if (array_key_exists("UserAgent",$param) and $param["UserAgent"] !== null) {
            $this->UserAgent = $param["UserAgent"];
        }

        if (array_key_exists("XForwardedFor",$param) and $param["XForwardedFor"] !== null) {
            $this->XForwardedFor = $param["XForwardedFor"];
        }

        if (array_key_exists("MacAddress",$param) and $param["MacAddress"] !== null) {
            $this->MacAddress = $param["MacAddress"];
        }

        if (array_key_exists("VendorId",$param) and $param["VendorId"] !== null) {
            $this->VendorId = $param["VendorId"];
        }

        if (array_key_exists("Imei",$param) and $param["Imei"] !== null) {
            $this->Imei = $param["Imei"];
        }
    }
}
