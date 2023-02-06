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

namespace TencentCloud\Ft\V20200304;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Ft\V20200304\Models as Models;

/**
 * @method Models\ChangeAgePicResponse ChangeAgePic(Models\ChangeAgePicRequest $req) 用户上传一张人脸图片，基于人脸编辑与生成算法，输出一张人脸变老或变年轻的图片，支持实现人脸不同年龄的变化。
 * @method Models\SwapGenderPicResponse SwapGenderPic(Models\SwapGenderPicRequest $req) 用户上传一张人脸图片，基于人脸编辑与生成算法，输出一张人脸性别转换的图片。男变女可实现美颜、淡妆、加刘海和长发的效果；女变男可实现加胡须、变短发的效果。 
 */

class FtClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "ft.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2020-03-04";

    /**
     * @param Credential $credential
     * @param string $region
     * @param ClientProfile|null $profile
     * @throws TencentCloudSDKException
     */
    function __construct($credential, $region, $profile=null)
    {
        parent::__construct($this->endpoint, $this->version, $credential, $region, $profile);
    }

    public function returnResponse($action, $response)
    {
        $respClass = "TencentCloud"."\\".ucfirst("ft")."\\"."V20200304\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
