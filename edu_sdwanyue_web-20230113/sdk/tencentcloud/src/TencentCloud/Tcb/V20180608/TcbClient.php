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

namespace TencentCloud\Tcb\V20180608;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Tcb\V20180608\Models as Models;

/**
 * @method Models\CheckTcbServiceResponse CheckTcbService(Models\CheckTcbServiceRequest $req) 检查是否开通Tcb服务
 * @method Models\CommonServiceAPIResponse CommonServiceAPI(Models\CommonServiceAPIRequest $req) TCB云API统一入口
 * @method Models\CreateAuthDomainResponse CreateAuthDomain(Models\CreateAuthDomainRequest $req) 增加安全域名
 * @method Models\CreateHostingDomainResponse CreateHostingDomain(Models\CreateHostingDomainRequest $req) 创建托管域名
 * @method Models\CreateStaticStoreResponse CreateStaticStore(Models\CreateStaticStoreRequest $req) 创建静态托管资源，包括COS和CDN，异步任务创建，查看创建结果需要根据DescribeStaticStore接口来查看
 * @method Models\DeleteEndUserResponse DeleteEndUser(Models\DeleteEndUserRequest $req) 删除终端用户
 * @method Models\DescribeAuthDomainsResponse DescribeAuthDomains(Models\DescribeAuthDomainsRequest $req) 获取安全域名列表
 * @method Models\DescribeDatabaseACLResponse DescribeDatabaseACL(Models\DescribeDatabaseACLRequest $req) 获取数据库权限
 * @method Models\DescribeEndUsersResponse DescribeEndUsers(Models\DescribeEndUsersRequest $req) 获取终端用户列表
 * @method Models\DescribeEnvFreeQuotaResponse DescribeEnvFreeQuota(Models\DescribeEnvFreeQuotaRequest $req) 查询后付费免费配额信息
 * @method Models\DescribeEnvLimitResponse DescribeEnvLimit(Models\DescribeEnvLimitRequest $req) 查询环境个数上限
 * @method Models\DescribeEnvsResponse DescribeEnvs(Models\DescribeEnvsRequest $req) 获取环境列表，含环境下的各个资源信息。尤其是各资源的唯一标识，是请求各资源的关键参数
 * @method Models\DescribeQuotaDataResponse DescribeQuotaData(Models\DescribeQuotaDataRequest $req) 查询指定指标的配额使用量
 * @method Models\DestroyEnvResponse DestroyEnv(Models\DestroyEnvRequest $req) 销毁环境
 * @method Models\DestroyStaticStoreResponse DestroyStaticStore(Models\DestroyStaticStoreRequest $req) 销毁静态托管资源，该接口创建异步销毁任务，资源最终状态可从DestroyStaticStore接口查看
 * @method Models\ModifyDatabaseACLResponse ModifyDatabaseACL(Models\ModifyDatabaseACLRequest $req) 修改数据库权限
 * @method Models\ModifyEnvResponse ModifyEnv(Models\ModifyEnvRequest $req) 更新环境信息
 * @method Models\ReinstateEnvResponse ReinstateEnv(Models\ReinstateEnvRequest $req) 针对已隔离的免费环境，可以通过本接口将其恢复访问。
 */

class TcbClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "tcb.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2018-06-08";

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
        $respClass = "TencentCloud"."\\".ucfirst("tcb")."\\"."V20180608\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
