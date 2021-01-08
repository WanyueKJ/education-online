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

namespace TencentCloud\Iotexplorer\V20190423;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Iotexplorer\V20190423\Models as Models;

/**
 * @method Models\CallDeviceActionAsyncResponse CallDeviceActionAsync(Models\CallDeviceActionAsyncRequest $req) 提供给用户异步调用设备动作的能力
 * @method Models\CallDeviceActionSyncResponse CallDeviceActionSync(Models\CallDeviceActionSyncRequest $req) 为用户提供同步调用设备动作的能力。
 * @method Models\ControlDeviceDataResponse ControlDeviceData(Models\ControlDeviceDataRequest $req) 根据设备产品ID、设备名称，设置控制设备的属性数据。
 * @method Models\CreateDeviceResponse CreateDevice(Models\CreateDeviceRequest $req) 创建设备
 * @method Models\CreateProjectResponse CreateProject(Models\CreateProjectRequest $req) 为用户提供新建项目的能力，用于集中管理产品和应用。
 * @method Models\CreateStudioProductResponse CreateStudioProduct(Models\CreateStudioProductRequest $req) 为用户提供新建产品的能力，用于管理用户的设备
 * @method Models\DeleteDeviceResponse DeleteDevice(Models\DeleteDeviceRequest $req) 删除设备
 * @method Models\DeleteProjectResponse DeleteProject(Models\DeleteProjectRequest $req) 提供删除某个项目的能力
 * @method Models\DeleteStudioProductResponse DeleteStudioProduct(Models\DeleteStudioProductRequest $req) 提供删除某个项目下产品的能力
 * @method Models\DescribeDeviceResponse DescribeDevice(Models\DescribeDeviceRequest $req) 用于查看某个设备的详细信息
 * @method Models\DescribeDeviceDataResponse DescribeDeviceData(Models\DescribeDeviceDataRequest $req) 根据设备产品ID、设备名称，获取设备上报的属性数据。
 * @method Models\DescribeDeviceDataHistoryResponse DescribeDeviceDataHistory(Models\DescribeDeviceDataHistoryRequest $req) 获取设备在指定时间范围内上报的历史数据。
 * @method Models\DescribeModelDefinitionResponse DescribeModelDefinition(Models\DescribeModelDefinitionRequest $req) 查询产品配置的数据模板信息
 * @method Models\DescribeProjectResponse DescribeProject(Models\DescribeProjectRequest $req) 查询项目详情
 * @method Models\DescribeStudioProductResponse DescribeStudioProduct(Models\DescribeStudioProductRequest $req) 提供查看茶品详细信息的能力，包括产品的ID、数据协议、认证类型等重要参数
 * @method Models\GetDeviceListResponse GetDeviceList(Models\GetDeviceListRequest $req) 用于查询某个产品下的设备列表
 * @method Models\GetProjectListResponse GetProjectList(Models\GetProjectListRequest $req) 提供查询用户所创建的项目列表查询功能。
 * @method Models\GetStudioProductListResponse GetStudioProductList(Models\GetStudioProductListRequest $req) 提供查询某个项目下所有产品信息的能力。
 * @method Models\ListEventHistoryResponse ListEventHistory(Models\ListEventHistoryRequest $req) 获取设备的历史事件
 * @method Models\ModifyModelDefinitionResponse ModifyModelDefinition(Models\ModifyModelDefinitionRequest $req) 提供修改产品的数据模板的能力
 * @method Models\ModifyProjectResponse ModifyProject(Models\ModifyProjectRequest $req) 修改项目
 * @method Models\ModifyStudioProductResponse ModifyStudioProduct(Models\ModifyStudioProductRequest $req) 提供修改产品的名称和描述等信息的能力，对于已发布产品不允许进行修改。
 * @method Models\ReleaseStudioProductResponse ReleaseStudioProduct(Models\ReleaseStudioProductRequest $req) 产品开发完成并测试通过后，通过发布产品将产品设置为发布状态
 * @method Models\SearchStudioProductResponse SearchStudioProduct(Models\SearchStudioProductRequest $req) 提供根据产品名称查找产品的能力
 */

class IotexplorerClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "iotexplorer.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2019-04-23";

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
        $respClass = "TencentCloud"."\\".ucfirst("iotexplorer")."\\"."V20190423\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
