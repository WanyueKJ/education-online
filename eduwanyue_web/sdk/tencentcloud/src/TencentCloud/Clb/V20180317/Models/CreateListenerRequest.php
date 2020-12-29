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
namespace TencentCloud\Clb\V20180317\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateListener请求参数结构体
 *
 * @method string getLoadBalancerId() 获取负载均衡实例 ID
 * @method void setLoadBalancerId(string $LoadBalancerId) 设置负载均衡实例 ID
 * @method array getPorts() 获取要将监听器创建到哪些端口，每个端口对应一个新的监听器
 * @method void setPorts(array $Ports) 设置要将监听器创建到哪些端口，每个端口对应一个新的监听器
 * @method string getProtocol() 获取监听器协议： TCP | UDP | HTTP | HTTPS | TCP_SSL（TCP_SSL 正在内测中，如需使用请通过工单申请）
 * @method void setProtocol(string $Protocol) 设置监听器协议： TCP | UDP | HTTP | HTTPS | TCP_SSL（TCP_SSL 正在内测中，如需使用请通过工单申请）
 * @method array getListenerNames() 获取要创建的监听器名称列表，名称与Ports数组按序一一对应，如不需立即命名，则无需提供此参数
 * @method void setListenerNames(array $ListenerNames) 设置要创建的监听器名称列表，名称与Ports数组按序一一对应，如不需立即命名，则无需提供此参数
 * @method HealthCheck getHealthCheck() 获取健康检查相关参数，此参数仅适用于TCP/UDP/TCP_SSL监听器
 * @method void setHealthCheck(HealthCheck $HealthCheck) 设置健康检查相关参数，此参数仅适用于TCP/UDP/TCP_SSL监听器
 * @method CertificateInput getCertificate() 获取证书相关信息，此参数仅适用于TCP_SSL监听器和未开启SNI特性的HTTPS监听器。
 * @method void setCertificate(CertificateInput $Certificate) 设置证书相关信息，此参数仅适用于TCP_SSL监听器和未开启SNI特性的HTTPS监听器。
 * @method integer getSessionExpireTime() 获取会话保持时间，单位：秒。可选值：30~3600，默认 0，表示不开启。此参数仅适用于TCP/UDP监听器。
 * @method void setSessionExpireTime(integer $SessionExpireTime) 设置会话保持时间，单位：秒。可选值：30~3600，默认 0，表示不开启。此参数仅适用于TCP/UDP监听器。
 * @method string getScheduler() 获取监听器转发的方式。可选值：WRR、LEAST_CONN
分别表示按权重轮询、最小连接数， 默认为 WRR。此参数仅适用于TCP/UDP/TCP_SSL监听器。
 * @method void setScheduler(string $Scheduler) 设置监听器转发的方式。可选值：WRR、LEAST_CONN
分别表示按权重轮询、最小连接数， 默认为 WRR。此参数仅适用于TCP/UDP/TCP_SSL监听器。
 * @method integer getSniSwitch() 获取是否开启SNI特性，此参数仅适用于HTTPS监听器。
 * @method void setSniSwitch(integer $SniSwitch) 设置是否开启SNI特性，此参数仅适用于HTTPS监听器。
 */
class CreateListenerRequest extends AbstractModel
{
    /**
     * @var string 负载均衡实例 ID
     */
    public $LoadBalancerId;

    /**
     * @var array 要将监听器创建到哪些端口，每个端口对应一个新的监听器
     */
    public $Ports;

    /**
     * @var string 监听器协议： TCP | UDP | HTTP | HTTPS | TCP_SSL（TCP_SSL 正在内测中，如需使用请通过工单申请）
     */
    public $Protocol;

    /**
     * @var array 要创建的监听器名称列表，名称与Ports数组按序一一对应，如不需立即命名，则无需提供此参数
     */
    public $ListenerNames;

    /**
     * @var HealthCheck 健康检查相关参数，此参数仅适用于TCP/UDP/TCP_SSL监听器
     */
    public $HealthCheck;

    /**
     * @var CertificateInput 证书相关信息，此参数仅适用于TCP_SSL监听器和未开启SNI特性的HTTPS监听器。
     */
    public $Certificate;

    /**
     * @var integer 会话保持时间，单位：秒。可选值：30~3600，默认 0，表示不开启。此参数仅适用于TCP/UDP监听器。
     */
    public $SessionExpireTime;

    /**
     * @var string 监听器转发的方式。可选值：WRR、LEAST_CONN
分别表示按权重轮询、最小连接数， 默认为 WRR。此参数仅适用于TCP/UDP/TCP_SSL监听器。
     */
    public $Scheduler;

    /**
     * @var integer 是否开启SNI特性，此参数仅适用于HTTPS监听器。
     */
    public $SniSwitch;

    /**
     * @param string $LoadBalancerId 负载均衡实例 ID
     * @param array $Ports 要将监听器创建到哪些端口，每个端口对应一个新的监听器
     * @param string $Protocol 监听器协议： TCP | UDP | HTTP | HTTPS | TCP_SSL（TCP_SSL 正在内测中，如需使用请通过工单申请）
     * @param array $ListenerNames 要创建的监听器名称列表，名称与Ports数组按序一一对应，如不需立即命名，则无需提供此参数
     * @param HealthCheck $HealthCheck 健康检查相关参数，此参数仅适用于TCP/UDP/TCP_SSL监听器
     * @param CertificateInput $Certificate 证书相关信息，此参数仅适用于TCP_SSL监听器和未开启SNI特性的HTTPS监听器。
     * @param integer $SessionExpireTime 会话保持时间，单位：秒。可选值：30~3600，默认 0，表示不开启。此参数仅适用于TCP/UDP监听器。
     * @param string $Scheduler 监听器转发的方式。可选值：WRR、LEAST_CONN
分别表示按权重轮询、最小连接数， 默认为 WRR。此参数仅适用于TCP/UDP/TCP_SSL监听器。
     * @param integer $SniSwitch 是否开启SNI特性，此参数仅适用于HTTPS监听器。
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("LoadBalancerId",$param) and $param["LoadBalancerId"] !== null) {
            $this->LoadBalancerId = $param["LoadBalancerId"];
        }

        if (array_key_exists("Ports",$param) and $param["Ports"] !== null) {
            $this->Ports = $param["Ports"];
        }

        if (array_key_exists("Protocol",$param) and $param["Protocol"] !== null) {
            $this->Protocol = $param["Protocol"];
        }

        if (array_key_exists("ListenerNames",$param) and $param["ListenerNames"] !== null) {
            $this->ListenerNames = $param["ListenerNames"];
        }

        if (array_key_exists("HealthCheck",$param) and $param["HealthCheck"] !== null) {
            $this->HealthCheck = new HealthCheck();
            $this->HealthCheck->deserialize($param["HealthCheck"]);
        }

        if (array_key_exists("Certificate",$param) and $param["Certificate"] !== null) {
            $this->Certificate = new CertificateInput();
            $this->Certificate->deserialize($param["Certificate"]);
        }

        if (array_key_exists("SessionExpireTime",$param) and $param["SessionExpireTime"] !== null) {
            $this->SessionExpireTime = $param["SessionExpireTime"];
        }

        if (array_key_exists("Scheduler",$param) and $param["Scheduler"] !== null) {
            $this->Scheduler = $param["Scheduler"];
        }

        if (array_key_exists("SniSwitch",$param) and $param["SniSwitch"] !== null) {
            $this->SniSwitch = $param["SniSwitch"];
        }
    }
}
