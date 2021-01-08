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
namespace TencentCloud\Vpc\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 用于描述弹性公网IP的费用对象
 *
 * @method integer getPeriod() 获取购买实例的时长
 * @method void setPeriod(integer $Period) 设置购买实例的时长
 * @method string getRenewFlag() 获取自动续费标志
 * @method void setRenewFlag(string $RenewFlag) 设置自动续费标志
 */
class AddressChargePrepaid extends AbstractModel
{
    /**
     * @var integer 购买实例的时长
     */
    public $Period;

    /**
     * @var string 自动续费标志
     */
    public $RenewFlag;

    /**
     * @param integer $Period 购买实例的时长
     * @param string $RenewFlag 自动续费标志
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
        if (array_key_exists("Period",$param) and $param["Period"] !== null) {
            $this->Period = $param["Period"];
        }

        if (array_key_exists("RenewFlag",$param) and $param["RenewFlag"] !== null) {
            $this->RenewFlag = $param["RenewFlag"];
        }
    }
}
