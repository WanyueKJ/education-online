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
namespace TencentCloud\Tiw\V20190919\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateTranscode请求参数结构体
 *
 * @method integer getSdkAppId() 获取客户的SdkAppId
 * @method void setSdkAppId(integer $SdkAppId) 设置客户的SdkAppId
 * @method string getUrl() 获取需要进行转码文件地址
 * @method void setUrl(string $Url) 设置需要进行转码文件地址
 * @method boolean getIsStaticPPT() 获取是否为静态PPT，默认为False；
如果IsStaticPPT为False，后缀名为.ppt或.pptx的文档会动态转码成HTML5页面，其他格式的文档会静态转码成图片；如果IsStaticPPT为True，所有格式的文档会静态转码成图片；
 * @method void setIsStaticPPT(boolean $IsStaticPPT) 设置是否为静态PPT，默认为False；
如果IsStaticPPT为False，后缀名为.ppt或.pptx的文档会动态转码成HTML5页面，其他格式的文档会静态转码成图片；如果IsStaticPPT为True，所有格式的文档会静态转码成图片；
 * @method string getMinResolution() 获取转码后文档的最小分辨率，不传、传空字符串或分辨率格式错误则使用文档原分辨率

注意分辨率宽高中间为英文字母"xyz"的"x"
 * @method void setMinResolution(string $MinResolution) 设置转码后文档的最小分辨率，不传、传空字符串或分辨率格式错误则使用文档原分辨率

注意分辨率宽高中间为英文字母"xyz"的"x"
 * @method string getThumbnailResolution() 获取动态PPT转码可以为文件生成该分辨率的缩略图，不传、传空字符串或分辨率格式错误则不生成缩略图，分辨率格式同MinResolution

静态转码这个参数不起作用
 * @method void setThumbnailResolution(string $ThumbnailResolution) 设置动态PPT转码可以为文件生成该分辨率的缩略图，不传、传空字符串或分辨率格式错误则不生成缩略图，分辨率格式同MinResolution

静态转码这个参数不起作用
 * @method string getCompressFileType() 获取转码文件压缩格式，不传、传空字符串或不是指定的格式则不生成压缩文件，目前支持如下压缩格式：

zip： 生成`.zip`压缩包
tar.gz： 生成`.tar.gz`压缩包
 * @method void setCompressFileType(string $CompressFileType) 设置转码文件压缩格式，不传、传空字符串或不是指定的格式则不生成压缩文件，目前支持如下压缩格式：

zip： 生成`.zip`压缩包
tar.gz： 生成`.tar.gz`压缩包
 */
class CreateTranscodeRequest extends AbstractModel
{
    /**
     * @var integer 客户的SdkAppId
     */
    public $SdkAppId;

    /**
     * @var string 需要进行转码文件地址
     */
    public $Url;

    /**
     * @var boolean 是否为静态PPT，默认为False；
如果IsStaticPPT为False，后缀名为.ppt或.pptx的文档会动态转码成HTML5页面，其他格式的文档会静态转码成图片；如果IsStaticPPT为True，所有格式的文档会静态转码成图片；
     */
    public $IsStaticPPT;

    /**
     * @var string 转码后文档的最小分辨率，不传、传空字符串或分辨率格式错误则使用文档原分辨率

注意分辨率宽高中间为英文字母"xyz"的"x"
     */
    public $MinResolution;

    /**
     * @var string 动态PPT转码可以为文件生成该分辨率的缩略图，不传、传空字符串或分辨率格式错误则不生成缩略图，分辨率格式同MinResolution

静态转码这个参数不起作用
     */
    public $ThumbnailResolution;

    /**
     * @var string 转码文件压缩格式，不传、传空字符串或不是指定的格式则不生成压缩文件，目前支持如下压缩格式：

zip： 生成`.zip`压缩包
tar.gz： 生成`.tar.gz`压缩包
     */
    public $CompressFileType;

    /**
     * @param integer $SdkAppId 客户的SdkAppId
     * @param string $Url 需要进行转码文件地址
     * @param boolean $IsStaticPPT 是否为静态PPT，默认为False；
如果IsStaticPPT为False，后缀名为.ppt或.pptx的文档会动态转码成HTML5页面，其他格式的文档会静态转码成图片；如果IsStaticPPT为True，所有格式的文档会静态转码成图片；
     * @param string $MinResolution 转码后文档的最小分辨率，不传、传空字符串或分辨率格式错误则使用文档原分辨率

注意分辨率宽高中间为英文字母"xyz"的"x"
     * @param string $ThumbnailResolution 动态PPT转码可以为文件生成该分辨率的缩略图，不传、传空字符串或分辨率格式错误则不生成缩略图，分辨率格式同MinResolution

静态转码这个参数不起作用
     * @param string $CompressFileType 转码文件压缩格式，不传、传空字符串或不是指定的格式则不生成压缩文件，目前支持如下压缩格式：

zip： 生成`.zip`压缩包
tar.gz： 生成`.tar.gz`压缩包
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
        if (array_key_exists("SdkAppId",$param) and $param["SdkAppId"] !== null) {
            $this->SdkAppId = $param["SdkAppId"];
        }

        if (array_key_exists("Url",$param) and $param["Url"] !== null) {
            $this->Url = $param["Url"];
        }

        if (array_key_exists("IsStaticPPT",$param) and $param["IsStaticPPT"] !== null) {
            $this->IsStaticPPT = $param["IsStaticPPT"];
        }

        if (array_key_exists("MinResolution",$param) and $param["MinResolution"] !== null) {
            $this->MinResolution = $param["MinResolution"];
        }

        if (array_key_exists("ThumbnailResolution",$param) and $param["ThumbnailResolution"] !== null) {
            $this->ThumbnailResolution = $param["ThumbnailResolution"];
        }

        if (array_key_exists("CompressFileType",$param) and $param["CompressFileType"] !== null) {
            $this->CompressFileType = $param["CompressFileType"];
        }
    }
}
