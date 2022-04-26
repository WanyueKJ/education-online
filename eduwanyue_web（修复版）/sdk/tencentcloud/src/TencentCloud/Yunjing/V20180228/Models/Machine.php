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
namespace TencentCloud\Yunjing\V20180228\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 主机列表
 *
 * @method string getMachineName() 获取主机名称。
 * @method void setMachineName(string $MachineName) 设置主机名称。
 * @method string getMachineOs() 获取主机系统。
 * @method void setMachineOs(string $MachineOs) 设置主机系统。
 * @method string getMachineStatus() 获取主机状态。
<li>OFFLINE: 离线  </li>
<li>ONLINE: 在线</li>
<li>MACHINE_STOPPED: 已关机</li>
 * @method void setMachineStatus(string $MachineStatus) 设置主机状态。
<li>OFFLINE: 离线  </li>
<li>ONLINE: 在线</li>
<li>MACHINE_STOPPED: 已关机</li>
 * @method string getUuid() 获取云镜客户端唯一Uuid，若客户端长时间不在线将返回空字符。
 * @method void setUuid(string $Uuid) 设置云镜客户端唯一Uuid，若客户端长时间不在线将返回空字符。
 * @method string getQuuid() 获取CVM或BM机器唯一Uuid。
 * @method void setQuuid(string $Quuid) 设置CVM或BM机器唯一Uuid。
 * @method integer getVulNum() 获取漏洞数。
 * @method void setVulNum(integer $VulNum) 设置漏洞数。
 * @method string getMachineIp() 获取主机IP。
 * @method void setMachineIp(string $MachineIp) 设置主机IP。
 * @method boolean getIsProVersion() 获取是否是专业版。
<li>true： 是</li>
<li>false：否</li>
 * @method void setIsProVersion(boolean $IsProVersion) 设置是否是专业版。
<li>true： 是</li>
<li>false：否</li>
 * @method string getMachineWanIp() 获取主机外网IP。
 * @method void setMachineWanIp(string $MachineWanIp) 设置主机外网IP。
 * @method string getPayMode() 获取主机状态。
<li>POSTPAY: 表示后付费，即按量计费  </li>
<li>PREPAY: 表示预付费，即包年包月</li>
 * @method void setPayMode(string $PayMode) 设置主机状态。
<li>POSTPAY: 表示后付费，即按量计费  </li>
<li>PREPAY: 表示预付费，即包年包月</li>
 * @method integer getMalwareNum() 获取木马数。
 * @method void setMalwareNum(integer $MalwareNum) 设置木马数。
 * @method array getTag() 获取标签信息
 * @method void setTag(array $Tag) 设置标签信息
 */
class Machine extends AbstractModel
{
    /**
     * @var string 主机名称。
     */
    public $MachineName;

    /**
     * @var string 主机系统。
     */
    public $MachineOs;

    /**
     * @var string 主机状态。
<li>OFFLINE: 离线  </li>
<li>ONLINE: 在线</li>
<li>MACHINE_STOPPED: 已关机</li>
     */
    public $MachineStatus;

    /**
     * @var string 云镜客户端唯一Uuid，若客户端长时间不在线将返回空字符。
     */
    public $Uuid;

    /**
     * @var string CVM或BM机器唯一Uuid。
     */
    public $Quuid;

    /**
     * @var integer 漏洞数。
     */
    public $VulNum;

    /**
     * @var string 主机IP。
     */
    public $MachineIp;

    /**
     * @var boolean 是否是专业版。
<li>true： 是</li>
<li>false：否</li>
     */
    public $IsProVersion;

    /**
     * @var string 主机外网IP。
     */
    public $MachineWanIp;

    /**
     * @var string 主机状态。
<li>POSTPAY: 表示后付费，即按量计费  </li>
<li>PREPAY: 表示预付费，即包年包月</li>
     */
    public $PayMode;

    /**
     * @var integer 木马数。
     */
    public $MalwareNum;

    /**
     * @var array 标签信息
     */
    public $Tag;

    /**
     * @param string $MachineName 主机名称。
     * @param string $MachineOs 主机系统。
     * @param string $MachineStatus 主机状态。
<li>OFFLINE: 离线  </li>
<li>ONLINE: 在线</li>
<li>MACHINE_STOPPED: 已关机</li>
     * @param string $Uuid 云镜客户端唯一Uuid，若客户端长时间不在线将返回空字符。
     * @param string $Quuid CVM或BM机器唯一Uuid。
     * @param integer $VulNum 漏洞数。
     * @param string $MachineIp 主机IP。
     * @param boolean $IsProVersion 是否是专业版。
<li>true： 是</li>
<li>false：否</li>
     * @param string $MachineWanIp 主机外网IP。
     * @param string $PayMode 主机状态。
<li>POSTPAY: 表示后付费，即按量计费  </li>
<li>PREPAY: 表示预付费，即包年包月</li>
     * @param integer $MalwareNum 木马数。
     * @param array $Tag 标签信息
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
        if (array_key_exists("MachineName",$param) and $param["MachineName"] !== null) {
            $this->MachineName = $param["MachineName"];
        }

        if (array_key_exists("MachineOs",$param) and $param["MachineOs"] !== null) {
            $this->MachineOs = $param["MachineOs"];
        }

        if (array_key_exists("MachineStatus",$param) and $param["MachineStatus"] !== null) {
            $this->MachineStatus = $param["MachineStatus"];
        }

        if (array_key_exists("Uuid",$param) and $param["Uuid"] !== null) {
            $this->Uuid = $param["Uuid"];
        }

        if (array_key_exists("Quuid",$param) and $param["Quuid"] !== null) {
            $this->Quuid = $param["Quuid"];
        }

        if (array_key_exists("VulNum",$param) and $param["VulNum"] !== null) {
            $this->VulNum = $param["VulNum"];
        }

        if (array_key_exists("MachineIp",$param) and $param["MachineIp"] !== null) {
            $this->MachineIp = $param["MachineIp"];
        }

        if (array_key_exists("IsProVersion",$param) and $param["IsProVersion"] !== null) {
            $this->IsProVersion = $param["IsProVersion"];
        }

        if (array_key_exists("MachineWanIp",$param) and $param["MachineWanIp"] !== null) {
            $this->MachineWanIp = $param["MachineWanIp"];
        }

        if (array_key_exists("PayMode",$param) and $param["PayMode"] !== null) {
            $this->PayMode = $param["PayMode"];
        }

        if (array_key_exists("MalwareNum",$param) and $param["MalwareNum"] !== null) {
            $this->MalwareNum = $param["MalwareNum"];
        }

        if (array_key_exists("Tag",$param) and $param["Tag"] !== null) {
            $this->Tag = [];
            foreach ($param["Tag"] as $key => $value){
                $obj = new MachineTag();
                $obj->deserialize($value);
                array_push($this->Tag, $obj);
            }
        }
    }
}
