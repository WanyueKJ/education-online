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
namespace TencentCloud\Bri\V20190328\Models;
use TencentCloud\Common\AbstractModel;

/**
 * BRI请求
 *
 * @method string getService() 获取业务名, 必须是以下五个业务名之一(bri_num,bri_dev,bri_ip_bri_apk,bri_url)
 * @method void setService(string $Service) 设置业务名, 必须是以下五个业务名之一(bri_num,bri_dev,bri_ip_bri_apk,bri_url)
 * @method string getCertMd5() 获取Apk证书Md5  (业务名为bri_apk时必填，除非已填FileMd5)
 * @method void setCertMd5(string $CertMd5) 设置Apk证书Md5  (业务名为bri_apk时必填，除非已填FileMd5)
 * @method string getFileMd5() 获取Apk文件Md5 (业务名为bri_apk时必填，除非已填PackageName,CertMd5,FileSize)
 * @method void setFileMd5(string $FileMd5) 设置Apk文件Md5 (业务名为bri_apk时必填，除非已填PackageName,CertMd5,FileSize)
 * @method integer getFileSize() 获取Apk文件大小  (业务名为bri_apk时必填，除非已填FileMd5)
 * @method void setFileSize(integer $FileSize) 设置Apk文件大小  (业务名为bri_apk时必填，除非已填FileMd5)
 * @method string getImei() 获取安卓设备的Imei (业务名为bri_dev时必填)
 * @method void setImei(string $Imei) 设置安卓设备的Imei (业务名为bri_dev时必填)
 * @method string getIp() 获取点分格式的IP (业务名为bri_ip时必填)
 * @method void setIp(string $Ip) 设置点分格式的IP (业务名为bri_ip时必填)
 * @method string getPackageName() 获取Apk安装包名 (业务名为bri_apk时必填，除非已填FileMd5)
 * @method void setPackageName(string $PackageName) 设置Apk安装包名 (业务名为bri_apk时必填，除非已填FileMd5)
 * @method string getPhoneNumber() 获取电话号码 (业务名为bri_num时必填)
 * @method void setPhoneNumber(string $PhoneNumber) 设置电话号码 (业务名为bri_num时必填)
 * @method string getUrl() 获取网址 (业务名为bri_url时必填)
 * @method void setUrl(string $Url) 设置网址 (业务名为bri_url时必填)
 */
class BRIRequest extends AbstractModel
{
    /**
     * @var string 业务名, 必须是以下五个业务名之一(bri_num,bri_dev,bri_ip_bri_apk,bri_url)
     */
    public $Service;

    /**
     * @var string Apk证书Md5  (业务名为bri_apk时必填，除非已填FileMd5)
     */
    public $CertMd5;

    /**
     * @var string Apk文件Md5 (业务名为bri_apk时必填，除非已填PackageName,CertMd5,FileSize)
     */
    public $FileMd5;

    /**
     * @var integer Apk文件大小  (业务名为bri_apk时必填，除非已填FileMd5)
     */
    public $FileSize;

    /**
     * @var string 安卓设备的Imei (业务名为bri_dev时必填)
     */
    public $Imei;

    /**
     * @var string 点分格式的IP (业务名为bri_ip时必填)
     */
    public $Ip;

    /**
     * @var string Apk安装包名 (业务名为bri_apk时必填，除非已填FileMd5)
     */
    public $PackageName;

    /**
     * @var string 电话号码 (业务名为bri_num时必填)
     */
    public $PhoneNumber;

    /**
     * @var string 网址 (业务名为bri_url时必填)
     */
    public $Url;

    /**
     * @param string $Service 业务名, 必须是以下五个业务名之一(bri_num,bri_dev,bri_ip_bri_apk,bri_url)
     * @param string $CertMd5 Apk证书Md5  (业务名为bri_apk时必填，除非已填FileMd5)
     * @param string $FileMd5 Apk文件Md5 (业务名为bri_apk时必填，除非已填PackageName,CertMd5,FileSize)
     * @param integer $FileSize Apk文件大小  (业务名为bri_apk时必填，除非已填FileMd5)
     * @param string $Imei 安卓设备的Imei (业务名为bri_dev时必填)
     * @param string $Ip 点分格式的IP (业务名为bri_ip时必填)
     * @param string $PackageName Apk安装包名 (业务名为bri_apk时必填，除非已填FileMd5)
     * @param string $PhoneNumber 电话号码 (业务名为bri_num时必填)
     * @param string $Url 网址 (业务名为bri_url时必填)
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
        if (array_key_exists("Service",$param) and $param["Service"] !== null) {
            $this->Service = $param["Service"];
        }

        if (array_key_exists("CertMd5",$param) and $param["CertMd5"] !== null) {
            $this->CertMd5 = $param["CertMd5"];
        }

        if (array_key_exists("FileMd5",$param) and $param["FileMd5"] !== null) {
            $this->FileMd5 = $param["FileMd5"];
        }

        if (array_key_exists("FileSize",$param) and $param["FileSize"] !== null) {
            $this->FileSize = $param["FileSize"];
        }

        if (array_key_exists("Imei",$param) and $param["Imei"] !== null) {
            $this->Imei = $param["Imei"];
        }

        if (array_key_exists("Ip",$param) and $param["Ip"] !== null) {
            $this->Ip = $param["Ip"];
        }

        if (array_key_exists("PackageName",$param) and $param["PackageName"] !== null) {
            $this->PackageName = $param["PackageName"];
        }

        if (array_key_exists("PhoneNumber",$param) and $param["PhoneNumber"] !== null) {
            $this->PhoneNumber = $param["PhoneNumber"];
        }

        if (array_key_exists("Url",$param) and $param["Url"] !== null) {
            $this->Url = $param["Url"];
        }
    }
}
