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

namespace TencentCloud\Ame\V20190916;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Ame\V20190916\Models as Models;

/**
 * @method Models\DescribeItemsResponse DescribeItems(Models\DescribeItemsRequest $req) 分类内容下歌曲列表获取，根据CategoryID或CategoryCode
 * @method Models\DescribeLyricResponse DescribeLyric(Models\DescribeLyricRequest $req) 根据接口的模式及歌曲ID来取得歌词信息。
 * @method Models\DescribeMusicResponse DescribeMusic(Models\DescribeMusicRequest $req) 根据接口的模式及歌曲ID来取得对应权限的歌曲播放地址等信息。
 * @method Models\DescribeStationsResponse DescribeStations(Models\DescribeStationsRequest $req) 获取素材库列表时使用
 * @method Models\ReportDataResponse ReportData(Models\ReportDataRequest $req) 客户上报用户数据功能，为了更好的为用户提供优质服务
 */

class AmeClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "ame.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2019-09-16";

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
        $respClass = "TencentCloud"."\\".ucfirst("ame")."\\"."V20190916\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
