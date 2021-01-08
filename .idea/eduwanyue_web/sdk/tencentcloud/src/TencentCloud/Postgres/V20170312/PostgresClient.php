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

namespace TencentCloud\Postgres\V20170312;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Postgres\V20170312\Models as Models;

/**
 * @method Models\CloseDBExtranetAccessResponse CloseDBExtranetAccess(Models\CloseDBExtranetAccessRequest $req) 本接口（CloseDBExtranetAccess）用于关闭实例外网链接。
 * @method Models\CloseServerlessDBExtranetAccessResponse CloseServerlessDBExtranetAccess(Models\CloseServerlessDBExtranetAccessRequest $req) 关闭serverlessDB实例外网
 * @method Models\CreateDBInstancesResponse CreateDBInstances(Models\CreateDBInstancesRequest $req) 本接口 (CreateDBInstances) 用于创建一个或者多个PostgreSQL实例。
 * @method Models\CreateServerlessDBInstanceResponse CreateServerlessDBInstance(Models\CreateServerlessDBInstanceRequest $req) 本接口 (CreateServerlessDBInstance) 用于创建一个ServerlessDB实例，创建成功返回实例ID。
 * @method Models\DeleteServerlessDBInstanceResponse DeleteServerlessDBInstance(Models\DeleteServerlessDBInstanceRequest $req) 本接口 (DeleteServerlessDBInstance) 用于删除一个ServerlessDB实例。
 * @method Models\DescribeAccountsResponse DescribeAccounts(Models\DescribeAccountsRequest $req) 本接口（DescribeAccounts）用于获取实例用户列表。
 * @method Models\DescribeDBBackupsResponse DescribeDBBackups(Models\DescribeDBBackupsRequest $req) 本接口（DescribeDBBackups）用于查询实例备份列表。
 * @method Models\DescribeDBErrlogsResponse DescribeDBErrlogs(Models\DescribeDBErrlogsRequest $req) 本接口（DescribeDBErrlogs）用于获取错误日志。
 * @method Models\DescribeDBInstanceAttributeResponse DescribeDBInstanceAttribute(Models\DescribeDBInstanceAttributeRequest $req) 本接口 (DescribeDBInstanceAttribute) 用于查询某个实例的详情信息。
 * @method Models\DescribeDBInstancesResponse DescribeDBInstances(Models\DescribeDBInstancesRequest $req) 本接口 (DescribeDBInstances) 用于查询一个或多个实例的详细信息。
 * @method Models\DescribeDBSlowlogsResponse DescribeDBSlowlogs(Models\DescribeDBSlowlogsRequest $req) 本接口（DescribeDBSlowlogs）用于获取慢查询日志。
 * @method Models\DescribeDBXlogsResponse DescribeDBXlogs(Models\DescribeDBXlogsRequest $req) 本接口（DescribeDBXlogs）用于获取实例Xlog列表。
 * @method Models\DescribeDatabasesResponse DescribeDatabases(Models\DescribeDatabasesRequest $req) 接口（DescribeDatabases）用来拉取数据库列表
 * @method Models\DescribeOrdersResponse DescribeOrders(Models\DescribeOrdersRequest $req) 本接口（DescribeOrders）用于获取订单信息。
 * @method Models\DescribeProductConfigResponse DescribeProductConfig(Models\DescribeProductConfigRequest $req) 本接口 (DescribeProductConfig) 用于查询售卖规格配置。
 * @method Models\DescribeRegionsResponse DescribeRegions(Models\DescribeRegionsRequest $req) 本接口 (DescribeRegions) 用于查询售卖地域信息。
 * @method Models\DescribeServerlessDBInstancesResponse DescribeServerlessDBInstances(Models\DescribeServerlessDBInstancesRequest $req) 用于查询一个或多个serverlessDB实例的详细信息
 * @method Models\DescribeZonesResponse DescribeZones(Models\DescribeZonesRequest $req) 本接口 (DescribeZones) 用于查询支持的可用区信息。
 * @method Models\DestroyDBInstanceResponse DestroyDBInstance(Models\DestroyDBInstanceRequest $req) 本接口 (DestroyDBInstance) 用于销毁指定DBInstanceId对应的实例。
 * @method Models\InitDBInstancesResponse InitDBInstances(Models\InitDBInstancesRequest $req) 本接口 (InitDBInstances) 用于初始化云数据库PostgreSQL实例。
 * @method Models\InquiryPriceCreateDBInstancesResponse InquiryPriceCreateDBInstances(Models\InquiryPriceCreateDBInstancesRequest $req) 本接口 (InquiryPriceCreateDBInstances) 用于查询购买一个或多个实例的价格信息。
 * @method Models\InquiryPriceRenewDBInstanceResponse InquiryPriceRenewDBInstance(Models\InquiryPriceRenewDBInstanceRequest $req) 本接口（InquiryPriceRenewDBInstance）用于查询续费实例的价格。
 * @method Models\InquiryPriceUpgradeDBInstanceResponse InquiryPriceUpgradeDBInstance(Models\InquiryPriceUpgradeDBInstanceRequest $req) 本接口（InquiryPriceUpgradeDBInstance）用于查询升级实例的价格。
 * @method Models\ModifyAccountRemarkResponse ModifyAccountRemark(Models\ModifyAccountRemarkRequest $req) 本接口（ModifyAccountRemark）用于修改帐号备注。
 * @method Models\ModifyDBInstanceNameResponse ModifyDBInstanceName(Models\ModifyDBInstanceNameRequest $req) 本接口（ModifyDBInstanceName）用于修改postgresql实例名字。
 * @method Models\ModifyDBInstancesProjectResponse ModifyDBInstancesProject(Models\ModifyDBInstancesProjectRequest $req) 本接口（ModifyDBInstancesProject）用于将实例转至其他项目。
 * @method Models\OpenDBExtranetAccessResponse OpenDBExtranetAccess(Models\OpenDBExtranetAccessRequest $req) 本接口（OpenDBExtranetAccess）用于开通外网。
 * @method Models\OpenServerlessDBExtranetAccessResponse OpenServerlessDBExtranetAccess(Models\OpenServerlessDBExtranetAccessRequest $req) 开通serverlessDB实例外网
 * @method Models\RenewInstanceResponse RenewInstance(Models\RenewInstanceRequest $req) 本接口（RenewInstance）用于续费实例。
 * @method Models\ResetAccountPasswordResponse ResetAccountPassword(Models\ResetAccountPasswordRequest $req) 本接口（ResetAccountPassword）用于重置实例的账户密码。
 * @method Models\RestartDBInstanceResponse RestartDBInstance(Models\RestartDBInstanceRequest $req) 本接口（RestartDBInstance）用于重启实例。
 * @method Models\SetAutoRenewFlagResponse SetAutoRenewFlag(Models\SetAutoRenewFlagRequest $req) 本接口（SetAutoRenewFlag）用于设置自动续费。
 * @method Models\UpgradeDBInstanceResponse UpgradeDBInstance(Models\UpgradeDBInstanceRequest $req) 本接口（UpgradeDBInstance）用于升级实例。
 */

class PostgresClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "postgres.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2017-03-12";

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
        $respClass = "TencentCloud"."\\".ucfirst("postgres")."\\"."V20170312\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
