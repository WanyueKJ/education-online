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

namespace TencentCloud\Ecm\V20190719;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Ecm\V20190719\Models as Models;

/**
 * @method Models\AllocateAddressesResponse AllocateAddresses(Models\AllocateAddressesRequest $req) 申请一个或多个弹性公网IP（简称 EIP）
 * @method Models\AssignPrivateIpAddressesResponse AssignPrivateIpAddresses(Models\AssignPrivateIpAddressesRequest $req) 弹性网卡申请内网 IP
 * @method Models\AssociateAddressResponse AssociateAddress(Models\AssociateAddressRequest $req) 将弹性公网IP（简称 EIP）绑定到实例或弹性网卡的指定内网 IP 上。
将 EIP 绑定到实例（CVM）上，其本质是将 EIP 绑定到实例上主网卡的主内网 IP 上。
将 EIP 绑定到主网卡的主内网IP上，绑定过程会把其上绑定的普通公网 IP 自动解绑并释放。
将 EIP 绑定到指定网卡的内网 IP上（非主网卡的主内网IP），则必须先解绑该 EIP，才能再绑定新的。
将 EIP 绑定到NAT网关，请使用接口EipBindNatGateway
EIP 如果欠费或被封堵，则不能被绑定。
只有状态为 UNBIND 的 EIP 才能够被绑定。
 * @method Models\AttachNetworkInterfaceResponse AttachNetworkInterface(Models\AttachNetworkInterfaceRequest $req) 弹性网卡绑定云主机
 * @method Models\CreateModuleResponse CreateModule(Models\CreateModuleRequest $req) 创建模块
 * @method Models\CreateNetworkInterfaceResponse CreateNetworkInterface(Models\CreateNetworkInterfaceRequest $req) 创建弹性网卡
 * @method Models\CreateSubnetResponse CreateSubnet(Models\CreateSubnetRequest $req) 创建子网
 * @method Models\CreateVpcResponse CreateVpc(Models\CreateVpcRequest $req) 创建私有网络
 * @method Models\DeleteImageResponse DeleteImage(Models\DeleteImageRequest $req) 删除镜像
 * @method Models\DeleteModuleResponse DeleteModule(Models\DeleteModuleRequest $req) 删除业务模块
 * @method Models\DeleteNetworkInterfaceResponse DeleteNetworkInterface(Models\DeleteNetworkInterfaceRequest $req) 删除弹性网卡
 * @method Models\DeleteSubnetResponse DeleteSubnet(Models\DeleteSubnetRequest $req) 删除子网
 * @method Models\DeleteVpcResponse DeleteVpc(Models\DeleteVpcRequest $req) 删除私有网络
 * @method Models\DescribeAddressQuotaResponse DescribeAddressQuota(Models\DescribeAddressQuotaRequest $req) 查询您账户的弹性公网IP（简称 EIP）在当前地域的配额信息
 * @method Models\DescribeAddressesResponse DescribeAddresses(Models\DescribeAddressesRequest $req) 查询弹性公网IP列表
 * @method Models\DescribeBaseOverviewResponse DescribeBaseOverview(Models\DescribeBaseOverviewRequest $req) 获取概览页统计的基本数据
 * @method Models\DescribeConfigResponse DescribeConfig(Models\DescribeConfigRequest $req) 获取带宽硬盘等数据的限制
 * @method Models\DescribeImageResponse DescribeImage(Models\DescribeImageRequest $req) 展示镜像列表
 * @method Models\DescribeInstanceTypeConfigResponse DescribeInstanceTypeConfig(Models\DescribeInstanceTypeConfigRequest $req) 获取机型配置列表
 * @method Models\DescribeInstanceVncUrlResponse DescribeInstanceVncUrl(Models\DescribeInstanceVncUrlRequest $req) 查询实例管理终端地址
 * @method Models\DescribeInstancesResponse DescribeInstances(Models\DescribeInstancesRequest $req) 获取实例的相关信息。
 * @method Models\DescribeInstancesDeniedActionsResponse DescribeInstancesDeniedActions(Models\DescribeInstancesDeniedActionsRequest $req) 通过实例id获取当前禁止的操作
 * @method Models\DescribeModuleResponse DescribeModule(Models\DescribeModuleRequest $req) 获取模块列表
 * @method Models\DescribeModuleDetailResponse DescribeModuleDetail(Models\DescribeModuleDetailRequest $req) 展示模块详细信息
 * @method Models\DescribeNetworkInterfacesResponse DescribeNetworkInterfaces(Models\DescribeNetworkInterfacesRequest $req) 查询弹性网卡列表
 * @method Models\DescribeNodeResponse DescribeNode(Models\DescribeNodeRequest $req) 获取节点列表
 * @method Models\DescribePeakBaseOverviewResponse DescribePeakBaseOverview(Models\DescribePeakBaseOverviewRequest $req) CPU 内存 硬盘等基础信息峰值数据
 * @method Models\DescribePeakNetworkOverviewResponse DescribePeakNetworkOverview(Models\DescribePeakNetworkOverviewRequest $req) 获取网络峰值数据
 * @method Models\DescribeSubnetsResponse DescribeSubnets(Models\DescribeSubnetsRequest $req) 查询子网列表
 * @method Models\DescribeTaskResultResponse DescribeTaskResult(Models\DescribeTaskResultRequest $req) 查询EIP异步任务执行结果
 * @method Models\DescribeVpcsResponse DescribeVpcs(Models\DescribeVpcsRequest $req) 查询私有网络列表
 * @method Models\DetachNetworkInterfaceResponse DetachNetworkInterface(Models\DetachNetworkInterfaceRequest $req) 弹性网卡解绑云主机
 * @method Models\DisassociateAddressResponse DisassociateAddress(Models\DisassociateAddressRequest $req) 解绑弹性公网IP（简称 EIP）
只有状态为 BIND 和 BIND_ENI 的 EIP 才能进行解绑定操作。
EIP 如果被封堵，则不能进行解绑定操作。
 * @method Models\ImportImageResponse ImportImage(Models\ImportImageRequest $req) 从CVM产品导入镜像到ECM
 * @method Models\MigrateNetworkInterfaceResponse MigrateNetworkInterface(Models\MigrateNetworkInterfaceRequest $req) 弹性网卡迁移
 * @method Models\MigratePrivateIpAddressResponse MigratePrivateIpAddress(Models\MigratePrivateIpAddressRequest $req) 弹性网卡内网IP迁移。
该接口用于将一个内网IP从一个弹性网卡上迁移到另外一个弹性网卡，主IP地址不支持迁移。
迁移前后的弹性网卡必须在同一个子网内。
 * @method Models\ModifyAddressAttributeResponse ModifyAddressAttribute(Models\ModifyAddressAttributeRequest $req) 修改弹性公网IP属性
 * @method Models\ModifyAddressesBandwidthResponse ModifyAddressesBandwidth(Models\ModifyAddressesBandwidthRequest $req) 调整弹性公网IP带宽

 * @method Models\ModifyInstancesAttributeResponse ModifyInstancesAttribute(Models\ModifyInstancesAttributeRequest $req) 修改实例的属性。
 * @method Models\ModifyModuleImageResponse ModifyModuleImage(Models\ModifyModuleImageRequest $req) ModifyModuleImage
 * @method Models\ModifyModuleNameResponse ModifyModuleName(Models\ModifyModuleNameRequest $req) 修改模块名称
 * @method Models\ModifyModuleNetworkResponse ModifyModuleNetwork(Models\ModifyModuleNetworkRequest $req) 修改模块默认带宽上限
 * @method Models\ModifySubnetAttributeResponse ModifySubnetAttribute(Models\ModifySubnetAttributeRequest $req) 修改子网属性
 * @method Models\ModifyVpcAttributeResponse ModifyVpcAttribute(Models\ModifyVpcAttributeRequest $req) 修改私有网络（VPC）的相关属性
 * @method Models\RebootInstancesResponse RebootInstances(Models\RebootInstancesRequest $req) 只有状态为RUNNING的实例才可以进行此操作；接口调用成功时，实例会进入REBOOTING状态；重启实例成功时，实例会进入RUNNING状态；支持强制重启，强制重启的效果等同于关闭物理计算机的电源开关再重新启动。强制重启可能会导致数据丢失或文件系统损坏，请仅在服务器不能正常重启时使用。
 * @method Models\ReleaseAddressesResponse ReleaseAddresses(Models\ReleaseAddressesRequest $req) 释放一个或多个弹性公网IP（简称 EIP）。
该操作不可逆，释放后 EIP 关联的 IP 地址将不再属于您的名下。
只有状态为 UNBIND 的 EIP 才能进行释放操作。
 * @method Models\RemovePrivateIpAddressesResponse RemovePrivateIpAddresses(Models\RemovePrivateIpAddressesRequest $req) 弹性网卡退还内网 IP。
退还弹性网卡上的辅助内网IP，接口自动解关联弹性公网 IP。不能退还弹性网卡的主内网IP。
 * @method Models\ResetInstancesResponse ResetInstances(Models\ResetInstancesRequest $req) 重装实例，若指定了ImageId参数，则使用指定的镜像重装；否则按照当前实例使用的镜像进行重装；若未指定密码，则密码通过站内信形式随后发送。
 * @method Models\ResetInstancesMaxBandwidthResponse ResetInstancesMaxBandwidth(Models\ResetInstancesMaxBandwidthRequest $req) 重置实例的最大带宽上限。
 * @method Models\ResetInstancesPasswordResponse ResetInstancesPassword(Models\ResetInstancesPasswordRequest $req) 重置处于运行中状态的实例的密码，需要显式指定强制关机参数ForceStop。如果没有显式指定强制关机参数，则只有处于关机状态的实例才允许执行重置密码操作。
 * @method Models\RunInstancesResponse RunInstances(Models\RunInstancesRequest $req) 创建ECM实例
 * @method Models\StartInstancesResponse StartInstances(Models\StartInstancesRequest $req) 只有状态为STOPPED的实例才可以进行此操作；接口调用成功时，实例会进入STARTING状态；启动实例成功时，实例会进入RUNNING状态。
 * @method Models\StopInstancesResponse StopInstances(Models\StopInstancesRequest $req) 只有处于"RUNNING"状态的实例才能够进行关机操作；
调用成功时，实例会进入STOPPING状态；关闭实例成功时，实例会进入STOPPED状态；
支持强制关闭，强制关机的效果等同于关闭物理计算机的电源开关，强制关机可能会导致数据丢失或文件系统损坏，请仅在服务器不能正常关机时使用。
 * @method Models\TerminateInstancesResponse TerminateInstances(Models\TerminateInstancesRequest $req) 销毁实例
 */

class EcmClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "ecm.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2019-07-19";

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
        $respClass = "TencentCloud"."\\".ucfirst("ecm")."\\"."V20190719\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
