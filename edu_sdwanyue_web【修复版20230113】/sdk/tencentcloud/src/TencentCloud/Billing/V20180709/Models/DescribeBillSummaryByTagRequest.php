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
namespace TencentCloud\Billing\V20180709\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeBillSummaryByTag请求参数结构体
 *
 * @method string getPayerUin() 获取查询账单数据的用户UIN
 * @method void setPayerUin(string $PayerUin) 设置查询账单数据的用户UIN
 * @method string getBeginTime() 获取目前只支持传当月开始，且必须和EndTime为相同月份，例 2018-09-01 00:00:00
 * @method void setBeginTime(string $BeginTime) 设置目前只支持传当月开始，且必须和EndTime为相同月份，例 2018-09-01 00:00:00
 * @method string getEndTime() 获取目前只支持传当月结束，且必须和BeginTime为相同月份，例 2018-09-30 23:59:59
 * @method void setEndTime(string $EndTime) 设置目前只支持传当月结束，且必须和BeginTime为相同月份，例 2018-09-30 23:59:59
 * @method string getTagKey() 获取分账标签键
 * @method void setTagKey(string $TagKey) 设置分账标签键
 */
class DescribeBillSummaryByTagRequest extends AbstractModel
{
    /**
     * @var string 查询账单数据的用户UIN
     */
    public $PayerUin;

    /**
     * @var string 目前只支持传当月开始，且必须和EndTime为相同月份，例 2018-09-01 00:00:00
     */
    public $BeginTime;

    /**
     * @var string 目前只支持传当月结束，且必须和BeginTime为相同月份，例 2018-09-30 23:59:59
     */
    public $EndTime;

    /**
     * @var string 分账标签键
     */
    public $TagKey;

    /**
     * @param string $PayerUin 查询账单数据的用户UIN
     * @param string $BeginTime 目前只支持传当月开始，且必须和EndTime为相同月份，例 2018-09-01 00:00:00
     * @param string $EndTime 目前只支持传当月结束，且必须和BeginTime为相同月份，例 2018-09-30 23:59:59
     * @param string $TagKey 分账标签键
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
        if (array_key_exists("PayerUin",$param) and $param["PayerUin"] !== null) {
            $this->PayerUin = $param["PayerUin"];
        }

        if (array_key_exists("BeginTime",$param) and $param["BeginTime"] !== null) {
            $this->BeginTime = $param["BeginTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("TagKey",$param) and $param["TagKey"] !== null) {
            $this->TagKey = $param["TagKey"];
        }
    }
}
