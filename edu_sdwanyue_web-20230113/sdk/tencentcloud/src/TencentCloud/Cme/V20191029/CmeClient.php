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

namespace TencentCloud\Cme\V20191029;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Cme\V20191029\Models as Models;

/**
 * @method Models\AddTeamMemberResponse AddTeamMember(Models\AddTeamMemberRequest $req) 向一个团队中团队成员，并且指定成员的角色。
 * @method Models\CreateClassResponse CreateClass(Models\CreateClassRequest $req) 新增分类，用于管理素材。
<li>分类层数不能超过10；</li>
<li>子分类数不能超过10。</li>
 * @method Models\CreateLinkResponse CreateLink(Models\CreateLinkRequest $req)  创建素材链接或分类路径链接，将源资源信息链接到目标。
 * @method Models\CreateProjectResponse CreateProject(Models\CreateProjectRequest $req) 创建云剪的编辑项目，支持创建视频剪辑及直播剪辑两大类项目。

 * @method Models\CreateTeamResponse CreateTeam(Models\CreateTeamRequest $req) 创建一个团队。
 * @method Models\DeleteClassResponse DeleteClass(Models\DeleteClassRequest $req) 删除分类信息，删除时检验下述限制：
<li>分类路径必须存在；</li>
<li>分类下没有绑定素材。</li>
 * @method Models\DeleteLoginStatusResponse DeleteLoginStatus(Models\DeleteLoginStatusRequest $req) 删除用户登录态，使用户登出云剪平台。
 * @method Models\DeleteMaterialResponse DeleteMaterial(Models\DeleteMaterialRequest $req) 根据素材 Id 删除素材。
 * @method Models\DeleteProjectResponse DeleteProject(Models\DeleteProjectRequest $req) 删除云剪编辑项目。
 * @method Models\DeleteTeamResponse DeleteTeam(Models\DeleteTeamRequest $req) 删除一个团队。
<li>要删除的团队必须没有归属的素材；</li>
<li>要删除的团队必须没有归属的分类。</li>
 * @method Models\DeleteTeamMembersResponse DeleteTeamMembers(Models\DeleteTeamMembersRequest $req) 将团队成员从团队中删除，默认只有 Owner 及管理员才有此权限。
 * @method Models\DescribeClassResponse DescribeClass(Models\DescribeClassRequest $req) 获取指定归属者下所有的分类信息。
 * @method Models\DescribeJoinTeamsResponse DescribeJoinTeams(Models\DescribeJoinTeamsRequest $req) 获取指定的团队成员所加入的团队列表。
 * @method Models\DescribeLoginStatusResponse DescribeLoginStatus(Models\DescribeLoginStatusRequest $req) 查询指定用户的登录态。
 * @method Models\DescribeMaterialsResponse DescribeMaterials(Models\DescribeMaterialsRequest $req) 根据素材 Id 批量获取素材详情。
 * @method Models\DescribeProjectsResponse DescribeProjects(Models\DescribeProjectsRequest $req) 支持根据多种条件过滤出项目列表。
 * @method Models\DescribeResourceAuthorizationResponse DescribeResourceAuthorization(Models\DescribeResourceAuthorizationRequest $req) 查询指定资源的授权列表。
 * @method Models\DescribeSharedSpaceResponse DescribeSharedSpace(Models\DescribeSharedSpaceRequest $req) 获取共享空间。当实体A对实体B授权某资源以后，实体B的共享空间就会增加实体A。
 * @method Models\DescribeTaskDetailResponse DescribeTaskDetail(Models\DescribeTaskDetailRequest $req) 获取任务详情信息，包含下面几个部分：
<li>任务基础信息：包括任务状态、错误信息、创建时间等；</li>
<li>导出项目输出信息：包括输出的素材 Id 等。</li>
 * @method Models\DescribeTasksResponse DescribeTasks(Models\DescribeTasksRequest $req) 获取任务列表，支持条件筛选，返回对应的任务基础信息列表。
 * @method Models\DescribeTeamMembersResponse DescribeTeamMembers(Models\DescribeTeamMembersRequest $req) 获取指定成员 ID 的信息，同时支持拉取所有团队成员信息。
 * @method Models\DescribeTeamsResponse DescribeTeams(Models\DescribeTeamsRequest $req) 获取指定团队的信息。
 * @method Models\ExportVideoByEditorTrackDataResponse ExportVideoByEditorTrackData(Models\ExportVideoByEditorTrackDataRequest $req) 使用在线编辑轨道数据直接导出视频。
 * @method Models\ExportVideoEditProjectResponse ExportVideoEditProject(Models\ExportVideoEditProjectRequest $req) 导出视频编辑项目，支持指定输出的模板。
 * @method Models\FlattenListMediaResponse FlattenListMedia(Models\FlattenListMediaRequest $req) 平铺分类路径下及其子分类下的所有素材。
 * @method Models\GrantResourceAuthorizationResponse GrantResourceAuthorization(Models\GrantResourceAuthorizationRequest $req) 资源所属实体对目标实体授予目标资源的相应权限。
 * @method Models\ImportMaterialResponse ImportMaterial(Models\ImportMaterialRequest $req) 将云点播媒资文件导入到云剪素材库。
 * @method Models\ImportMediaToProjectResponse ImportMediaToProject(Models\ImportMediaToProjectRequest $req) 将云点播中的媒资添加到素材库中，供后续视频编辑使用。
 * @method Models\ListMediaResponse ListMedia(Models\ListMediaRequest $req)  浏览当前分类路径下的资源，包括素材和子分类。
 * @method Models\ModifyMaterialResponse ModifyMaterial(Models\ModifyMaterialRequest $req) 修改素材信息，支持修改素材名称、分类路径、标签等信息。
 * @method Models\ModifyProjectResponse ModifyProject(Models\ModifyProjectRequest $req) 修改云剪编辑项目的信息。
 * @method Models\ModifyTeamResponse ModifyTeam(Models\ModifyTeamRequest $req) 修改团队信息，目前支持修改的操作有：
<li>修改团队名称。</li>
 * @method Models\ModifyTeamMemberResponse ModifyTeamMember(Models\ModifyTeamMemberRequest $req) 修改团队成员信息，包括成员备注、角色等。
 * @method Models\MoveClassResponse MoveClass(Models\MoveClassRequest $req) 移动某一个分类到另外一个分类下，也可用于分类重命名。
<li>如果 SourceClassPath = /素材/视频/NBA，DestinationClassPath = /素材/视频/篮球，当 DestinationClassPath 不存在时候，操作结果为重命名 ClassPath，如果 DestinationClassPath 存在时候，操作结果为产生新目录 /素材/视频/篮球/NBA。</li>
 * @method Models\RevokeResourceAuthorizationResponse RevokeResourceAuthorization(Models\RevokeResourceAuthorizationRequest $req)  资源所属实体对目标实体回收目标资源的相应权限，若原本没有相应权限则不产生变更。
 * @method Models\SearchMaterialResponse SearchMaterial(Models\SearchMaterialRequest $req) 根据检索条件搜索素材，返回素材的基本信息。
 */

class CmeClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "cme.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2019-10-29";

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
        $respClass = "TencentCloud"."\\".ucfirst("cme")."\\"."V20191029\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
