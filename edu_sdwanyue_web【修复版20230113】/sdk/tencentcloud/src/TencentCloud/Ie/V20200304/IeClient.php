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

namespace TencentCloud\Ie\V20200304;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Ie\V20200304\Models as Models;

/**
 * @method Models\CreateEditingTaskResponse CreateEditingTask(Models\CreateEditingTaskRequest $req) 创建智能编辑任务，可以同时选择视频标签识别、分类识别、智能拆条、智能集锦、智能封面和片头片尾识别中的一项或者多项能力。
 * @method Models\DescribeEditingTaskResultResponse DescribeEditingTaskResult(Models\DescribeEditingTaskResultRequest $req) 获取智能编辑任务结果。
 */

class IeClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "ie.tencentcloudapi.com";

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
        $respClass = "TencentCloud"."\\".ucfirst("ie")."\\"."V20200304\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
