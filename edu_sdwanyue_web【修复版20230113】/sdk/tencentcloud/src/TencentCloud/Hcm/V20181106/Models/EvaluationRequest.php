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
namespace TencentCloud\Hcm\V20181106\Models;
use TencentCloud\Common\AbstractModel;

/**
 * Evaluation请求参数结构体
 *
 * @method string getSessionId() 获取图片唯一标识，一张图片一个SessionId；
 * @method void setSessionId(string $SessionId) 设置图片唯一标识，一张图片一个SessionId；
 * @method string getImage() 获取图片数据，需要使用base64对图片的二进制数据进行编码，与url参数二者填一即可；
 * @method void setImage(string $Image) 设置图片数据，需要使用base64对图片的二进制数据进行编码，与url参数二者填一即可；
 * @method string getHcmAppid() 获取业务应用ID，与账号应用APPID无关，是用来方便客户管理服务的参数，新的 HcmAppid 可以在[控制台](https://console.cloud.tencent.com/hcm)【应用管理】下新建。
 * @method void setHcmAppid(string $HcmAppid) 设置业务应用ID，与账号应用APPID无关，是用来方便客户管理服务的参数，新的 HcmAppid 可以在[控制台](https://console.cloud.tencent.com/hcm)【应用管理】下新建。
 * @method string getUrl() 获取图片url，与Image参数二者填一即可；
 * @method void setUrl(string $Url) 设置图片url，与Image参数二者填一即可；
 * @method boolean getSupportHorizontalImage() 获取横屏拍摄开关，若开启则支持传输横屏拍摄的图片；
 * @method void setSupportHorizontalImage(boolean $SupportHorizontalImage) 设置横屏拍摄开关，若开启则支持传输横屏拍摄的图片；
 * @method boolean getRejectNonArithmeticImage() 获取拒绝非速算图（如风景图、人物图）开关，若开启，则遇到非速算图会快速返回拒绝的结果，但极端情况下可能会影响评估结果（比如算式截图贴到风景画里可能被判为非速算图直接返回了）。
 * @method void setRejectNonArithmeticImage(boolean $RejectNonArithmeticImage) 设置拒绝非速算图（如风景图、人物图）开关，若开启，则遇到非速算图会快速返回拒绝的结果，但极端情况下可能会影响评估结果（比如算式截图贴到风景画里可能被判为非速算图直接返回了）。
 * @method integer getIsAsync() 获取异步模式标识，0：同步模式，1：异步模式。默认为同步模式
 * @method void setIsAsync(integer $IsAsync) 设置异步模式标识，0：同步模式，1：异步模式。默认为同步模式
 */
class EvaluationRequest extends AbstractModel
{
    /**
     * @var string 图片唯一标识，一张图片一个SessionId；
     */
    public $SessionId;

    /**
     * @var string 图片数据，需要使用base64对图片的二进制数据进行编码，与url参数二者填一即可；
     */
    public $Image;

    /**
     * @var string 业务应用ID，与账号应用APPID无关，是用来方便客户管理服务的参数，新的 HcmAppid 可以在[控制台](https://console.cloud.tencent.com/hcm)【应用管理】下新建。
     */
    public $HcmAppid;

    /**
     * @var string 图片url，与Image参数二者填一即可；
     */
    public $Url;

    /**
     * @var boolean 横屏拍摄开关，若开启则支持传输横屏拍摄的图片；
     */
    public $SupportHorizontalImage;

    /**
     * @var boolean 拒绝非速算图（如风景图、人物图）开关，若开启，则遇到非速算图会快速返回拒绝的结果，但极端情况下可能会影响评估结果（比如算式截图贴到风景画里可能被判为非速算图直接返回了）。
     */
    public $RejectNonArithmeticImage;

    /**
     * @var integer 异步模式标识，0：同步模式，1：异步模式。默认为同步模式
     */
    public $IsAsync;

    /**
     * @param string $SessionId 图片唯一标识，一张图片一个SessionId；
     * @param string $Image 图片数据，需要使用base64对图片的二进制数据进行编码，与url参数二者填一即可；
     * @param string $HcmAppid 业务应用ID，与账号应用APPID无关，是用来方便客户管理服务的参数，新的 HcmAppid 可以在[控制台](https://console.cloud.tencent.com/hcm)【应用管理】下新建。
     * @param string $Url 图片url，与Image参数二者填一即可；
     * @param boolean $SupportHorizontalImage 横屏拍摄开关，若开启则支持传输横屏拍摄的图片；
     * @param boolean $RejectNonArithmeticImage 拒绝非速算图（如风景图、人物图）开关，若开启，则遇到非速算图会快速返回拒绝的结果，但极端情况下可能会影响评估结果（比如算式截图贴到风景画里可能被判为非速算图直接返回了）。
     * @param integer $IsAsync 异步模式标识，0：同步模式，1：异步模式。默认为同步模式
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
        if (array_key_exists("SessionId",$param) and $param["SessionId"] !== null) {
            $this->SessionId = $param["SessionId"];
        }

        if (array_key_exists("Image",$param) and $param["Image"] !== null) {
            $this->Image = $param["Image"];
        }

        if (array_key_exists("HcmAppid",$param) and $param["HcmAppid"] !== null) {
            $this->HcmAppid = $param["HcmAppid"];
        }

        if (array_key_exists("Url",$param) and $param["Url"] !== null) {
            $this->Url = $param["Url"];
        }

        if (array_key_exists("SupportHorizontalImage",$param) and $param["SupportHorizontalImage"] !== null) {
            $this->SupportHorizontalImage = $param["SupportHorizontalImage"];
        }

        if (array_key_exists("RejectNonArithmeticImage",$param) and $param["RejectNonArithmeticImage"] !== null) {
            $this->RejectNonArithmeticImage = $param["RejectNonArithmeticImage"];
        }

        if (array_key_exists("IsAsync",$param) and $param["IsAsync"] !== null) {
            $this->IsAsync = $param["IsAsync"];
        }
    }
}
