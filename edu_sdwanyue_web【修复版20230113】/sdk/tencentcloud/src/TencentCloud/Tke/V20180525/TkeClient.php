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

namespace TencentCloud\Tke\V20180525;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Tke\V20180525\Models as Models;

/**
 * @method Models\AddExistedInstancesResponse AddExistedInstances(Models\AddExistedInstancesRequest $req) 添加已经存在的实例到集群
 * @method Models\CreateClusterResponse CreateCluster(Models\CreateClusterRequest $req) 创建集群
 * @method Models\CreateClusterAsGroupResponse CreateClusterAsGroup(Models\CreateClusterAsGroupRequest $req) 为已经存在的集群创建伸缩组
 * @method Models\CreateClusterEndpointResponse CreateClusterEndpoint(Models\CreateClusterEndpointRequest $req) 创建集群访问端口(独立集群开启内网/外网访问，托管集群支持开启内网访问)
 * @method Models\CreateClusterEndpointVipResponse CreateClusterEndpointVip(Models\CreateClusterEndpointVipRequest $req) 创建托管集群外网访问端口（老的方式，仅支持托管集群外网端口）
 * @method Models\CreateClusterInstancesResponse CreateClusterInstances(Models\CreateClusterInstancesRequest $req) 扩展(新建)集群节点
 * @method Models\CreateClusterRouteResponse CreateClusterRoute(Models\CreateClusterRouteRequest $req) 创建集群路由
 * @method Models\CreateClusterRouteTableResponse CreateClusterRouteTable(Models\CreateClusterRouteTableRequest $req) 创建集群路由表
 * @method Models\DeleteClusterResponse DeleteCluster(Models\DeleteClusterRequest $req) 删除集群(YUNAPI V3版本)
 * @method Models\DeleteClusterAsGroupsResponse DeleteClusterAsGroups(Models\DeleteClusterAsGroupsRequest $req) 删除集群伸缩组
 * @method Models\DeleteClusterEndpointResponse DeleteClusterEndpoint(Models\DeleteClusterEndpointRequest $req) 删除集群访问端口(独立集群开启内网/外网访问，托管集群支持开启内网访问)
 * @method Models\DeleteClusterEndpointVipResponse DeleteClusterEndpointVip(Models\DeleteClusterEndpointVipRequest $req) 删除托管集群外网访问端口（老的方式，仅支持托管集群外网端口）
 * @method Models\DeleteClusterInstancesResponse DeleteClusterInstances(Models\DeleteClusterInstancesRequest $req) 删除集群中的实例
 * @method Models\DeleteClusterRouteResponse DeleteClusterRoute(Models\DeleteClusterRouteRequest $req) 删除集群路由
 * @method Models\DeleteClusterRouteTableResponse DeleteClusterRouteTable(Models\DeleteClusterRouteTableRequest $req) 删除集群路由表
 * @method Models\DescribeClusterAsGroupOptionResponse DescribeClusterAsGroupOption(Models\DescribeClusterAsGroupOptionRequest $req) 集群弹性伸缩配置
 * @method Models\DescribeClusterAsGroupsResponse DescribeClusterAsGroups(Models\DescribeClusterAsGroupsRequest $req) 集群关联的伸缩组列表
 * @method Models\DescribeClusterEndpointStatusResponse DescribeClusterEndpointStatus(Models\DescribeClusterEndpointStatusRequest $req) 查询集群访问端口状态(独立集群开启内网/外网访问，托管集群支持开启内网访问)
 * @method Models\DescribeClusterEndpointVipStatusResponse DescribeClusterEndpointVipStatus(Models\DescribeClusterEndpointVipStatusRequest $req) 查询集群开启端口流程状态(仅支持托管集群外网端口)
 * @method Models\DescribeClusterInstancesResponse DescribeClusterInstances(Models\DescribeClusterInstancesRequest $req)  查询集群下节点实例信息 
 * @method Models\DescribeClusterRouteTablesResponse DescribeClusterRouteTables(Models\DescribeClusterRouteTablesRequest $req) 查询集群路由表
 * @method Models\DescribeClusterRoutesResponse DescribeClusterRoutes(Models\DescribeClusterRoutesRequest $req) 查询集群路由
 * @method Models\DescribeClusterSecurityResponse DescribeClusterSecurity(Models\DescribeClusterSecurityRequest $req) 集群的密钥信息
 * @method Models\DescribeClustersResponse DescribeClusters(Models\DescribeClustersRequest $req) 查询集群列表
 * @method Models\DescribeExistedInstancesResponse DescribeExistedInstances(Models\DescribeExistedInstancesRequest $req) 查询已经存在的节点，判断是否可以加入集群
 * @method Models\DescribeImagesResponse DescribeImages(Models\DescribeImagesRequest $req) 获取镜像信息
 * @method Models\DescribeRegionsResponse DescribeRegions(Models\DescribeRegionsRequest $req) 获取容器服务支持的所有地域
 * @method Models\DescribeRouteTableConflictsResponse DescribeRouteTableConflicts(Models\DescribeRouteTableConflictsRequest $req) 查询路由表冲突列表
 * @method Models\ModifyClusterAsGroupAttributeResponse ModifyClusterAsGroupAttribute(Models\ModifyClusterAsGroupAttributeRequest $req) 修改集群伸缩组属性
 * @method Models\ModifyClusterAttributeResponse ModifyClusterAttribute(Models\ModifyClusterAttributeRequest $req) 修改集群属性
 * @method Models\ModifyClusterEndpointSPResponse ModifyClusterEndpointSP(Models\ModifyClusterEndpointSPRequest $req) 修改托管集群外网端口的安全策略（老的方式，仅支持托管集群外网端口）
 */

class TkeClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "tke.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2018-05-25";

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
        $respClass = "TencentCloud"."\\".ucfirst("tke")."\\"."V20180525\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
