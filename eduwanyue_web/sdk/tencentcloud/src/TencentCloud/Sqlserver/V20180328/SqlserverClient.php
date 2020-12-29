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

namespace TencentCloud\Sqlserver\V20180328;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Sqlserver\V20180328\Models as Models;

/**
 * @method Models\CreateAccountResponse CreateAccount(Models\CreateAccountRequest $req) 本接口（CreateAccount）用于创建实例账号
 * @method Models\CreateBackupResponse CreateBackup(Models\CreateBackupRequest $req) 本接口(CreateBackup)用于创建备份。
 * @method Models\CreateDBResponse CreateDB(Models\CreateDBRequest $req) 本接口（CreateDB）用于创建数据库。
 * @method Models\CreateDBInstancesResponse CreateDBInstances(Models\CreateDBInstancesRequest $req) 本接口（CreateDBInstances）用于创建实例。
 * @method Models\CreateMigrationResponse CreateMigration(Models\CreateMigrationRequest $req) 本接口（CreateMigration）作用是创建一个迁移任务
 * @method Models\DeleteAccountResponse DeleteAccount(Models\DeleteAccountRequest $req) 本接口（DeleteAccount）用于删除实例账号。
 * @method Models\DeleteDBResponse DeleteDB(Models\DeleteDBRequest $req) 本接口(DeleteDB)用于删除数据库。
 * @method Models\DeleteMigrationResponse DeleteMigration(Models\DeleteMigrationRequest $req) 本接口（DeleteMigration）用于删除迁移任务
 * @method Models\DescribeAccountsResponse DescribeAccounts(Models\DescribeAccountsRequest $req) 本接口（DescribeAccounts）用于拉取实例账户列表。
 * @method Models\DescribeBackupsResponse DescribeBackups(Models\DescribeBackupsRequest $req) 本接口(DescribeBackups)用于查询备份列表。
 * @method Models\DescribeDBInstancesResponse DescribeDBInstances(Models\DescribeDBInstancesRequest $req) 本接口(DescribeDBInstances)用于查询实例列表。
 * @method Models\DescribeDBsResponse DescribeDBs(Models\DescribeDBsRequest $req) 本接口（DescribeDBs）用于查询数据库列表。
 * @method Models\DescribeFlowStatusResponse DescribeFlowStatus(Models\DescribeFlowStatusRequest $req) 本接口(DescribeFlowStatus)用于查询流程状态。
 * @method Models\DescribeMigrationDetailResponse DescribeMigrationDetail(Models\DescribeMigrationDetailRequest $req) 本接口（DescribeMigrationDetail）用于查询迁移任务的详细情况
 * @method Models\DescribeMigrationsResponse DescribeMigrations(Models\DescribeMigrationsRequest $req) 本接口（DescribeMigrations）根据输入的限定条件，查询符合条件的迁移任务列表
 * @method Models\DescribeOrdersResponse DescribeOrders(Models\DescribeOrdersRequest $req) 本接口（DescribeOrders）用于查询订单信息
 * @method Models\DescribeProductConfigResponse DescribeProductConfig(Models\DescribeProductConfigRequest $req) 本接口 (DescribeProductConfig) 用于查询售卖规格配置。
 * @method Models\DescribeRegionsResponse DescribeRegions(Models\DescribeRegionsRequest $req) 本接口 (DescribeRegions) 用于查询售卖地域信息。
 * @method Models\DescribeRollbackTimeResponse DescribeRollbackTime(Models\DescribeRollbackTimeRequest $req) 本接口（DescribeRollbackTime）用于查询实例可回档时间范围
 * @method Models\DescribeSlowlogsResponse DescribeSlowlogs(Models\DescribeSlowlogsRequest $req) 本接口（DescribeSlowlogs）用于获取慢查询日志文件信息
 * @method Models\DescribeZonesResponse DescribeZones(Models\DescribeZonesRequest $req) 本接口 (DescribeZones) 用于查询当前可售卖的可用区信息。
 * @method Models\InquiryPriceCreateDBInstancesResponse InquiryPriceCreateDBInstances(Models\InquiryPriceCreateDBInstancesRequest $req) 本接口（InquiryPriceCreateDBInstances）用于查询申请实例价格。
 * @method Models\InquiryPriceRenewDBInstanceResponse InquiryPriceRenewDBInstance(Models\InquiryPriceRenewDBInstanceRequest $req) 本接口（InquiryPriceRenewDBInstance）用于查询续费实例的价格。
 * @method Models\InquiryPriceUpgradeDBInstanceResponse InquiryPriceUpgradeDBInstance(Models\InquiryPriceUpgradeDBInstanceRequest $req) 本接口（InquiryPriceUpgradeDBInstance）用于查询升级实例的价格。
 * @method Models\ModifyAccountPrivilegeResponse ModifyAccountPrivilege(Models\ModifyAccountPrivilegeRequest $req) 本接口（ModifyAccountPrivilege）用于修改实例账户权限。
 * @method Models\ModifyAccountRemarkResponse ModifyAccountRemark(Models\ModifyAccountRemarkRequest $req) 本接口（ModifyAccountRemark）用于修改账户备注。
 * @method Models\ModifyDBInstanceNameResponse ModifyDBInstanceName(Models\ModifyDBInstanceNameRequest $req) 本接口（ModifyDBInstanceName）用于修改实例名字。
 * @method Models\ModifyDBInstanceProjectResponse ModifyDBInstanceProject(Models\ModifyDBInstanceProjectRequest $req) 本接口（ModifyDBInstanceProject）用于修改数据库实例所属项目。
 * @method Models\ModifyDBInstanceRenewFlagResponse ModifyDBInstanceRenewFlag(Models\ModifyDBInstanceRenewFlagRequest $req) 本接口（ModifyDBInstanceRenewFlag）用于修改实例续费标记
 * @method Models\ModifyDBNameResponse ModifyDBName(Models\ModifyDBNameRequest $req) 本接口（ModifyDBName）用于更新数据库名。
 * @method Models\ModifyDBRemarkResponse ModifyDBRemark(Models\ModifyDBRemarkRequest $req) 本接口（ModifyDBRemark）用于修改数据库备注。
 * @method Models\ModifyMigrationResponse ModifyMigration(Models\ModifyMigrationRequest $req) 本接口（ModifyMigration）可以修改已有的迁移任务信息
 * @method Models\RenewDBInstanceResponse RenewDBInstance(Models\RenewDBInstanceRequest $req) 本接口（RenewDBInstance）用于续费实例。
 * @method Models\ResetAccountPasswordResponse ResetAccountPassword(Models\ResetAccountPasswordRequest $req) 本接口（ResetAccountPassword）用于重置实例的账户密码。
 * @method Models\RestartDBInstanceResponse RestartDBInstance(Models\RestartDBInstanceRequest $req) 本接口（RestartDBInstance）用于重启数据库实例。
 * @method Models\RestoreInstanceResponse RestoreInstance(Models\RestoreInstanceRequest $req) 本接口（RestoreInstance）用于根据备份文件恢复实例。
 * @method Models\RollbackInstanceResponse RollbackInstance(Models\RollbackInstanceRequest $req) 本接口（RollbackInstance）用于回档实例
 * @method Models\RunMigrationResponse RunMigration(Models\RunMigrationRequest $req) 本接口（RunMigration）用于启动迁移任务，开始迁移
 * @method Models\TerminateDBInstanceResponse TerminateDBInstance(Models\TerminateDBInstanceRequest $req) 本接口(TerminateDBInstance)用于主动销毁按量计费实例。
 * @method Models\UpgradeDBInstanceResponse UpgradeDBInstance(Models\UpgradeDBInstanceRequest $req) 本接口（UpgradeDBInstance）用于升级实例
 */

class SqlserverClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "sqlserver.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2018-03-28";

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
        $respClass = "TencentCloud"."\\".ucfirst("sqlserver")."\\"."V20180328\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
