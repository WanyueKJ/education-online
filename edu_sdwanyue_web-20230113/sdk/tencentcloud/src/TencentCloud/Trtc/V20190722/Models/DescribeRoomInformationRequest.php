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
namespace TencentCloud\Trtc\V20190722\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeRoomInformation请求参数结构体
 *
 * @method string getSdkAppId() 获取用户sdkappid
 * @method void setSdkAppId(string $SdkAppId) 设置用户sdkappid
 * @method integer getStartTime() 获取查询开始时间
 * @method void setStartTime(integer $StartTime) 设置查询开始时间
 * @method integer getEndTime() 获取查询结束时间
 * @method void setEndTime(integer $EndTime) 设置查询结束时间
 * @method string getRoomId() 获取数字房间号
 * @method void setRoomId(string $RoomId) 设置数字房间号
 * @method string getPageNumber() 获取分页index（不填默认只返回10个）
 * @method void setPageNumber(string $PageNumber) 设置分页index（不填默认只返回10个）
 * @method string getPageSize() 获取分页大小（不填默认返回10个,最多不超过100条）
 * @method void setPageSize(string $PageSize) 设置分页大小（不填默认返回10个,最多不超过100条）
 */
class DescribeRoomInformationRequest extends AbstractModel
{
    /**
     * @var string 用户sdkappid
     */
    public $SdkAppId;

    /**
     * @var integer 查询开始时间
     */
    public $StartTime;

    /**
     * @var integer 查询结束时间
     */
    public $EndTime;

    /**
     * @var string 数字房间号
     */
    public $RoomId;

    /**
     * @var string 分页index（不填默认只返回10个）
     */
    public $PageNumber;

    /**
     * @var string 分页大小（不填默认返回10个,最多不超过100条）
     */
    public $PageSize;

    /**
     * @param string $SdkAppId 用户sdkappid
     * @param integer $StartTime 查询开始时间
     * @param integer $EndTime 查询结束时间
     * @param string $RoomId 数字房间号
     * @param string $PageNumber 分页index（不填默认只返回10个）
     * @param string $PageSize 分页大小（不填默认返回10个,最多不超过100条）
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
        if (array_key_exists("SdkAppId",$param) and $param["SdkAppId"] !== null) {
            $this->SdkAppId = $param["SdkAppId"];
        }

        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("RoomId",$param) and $param["RoomId"] !== null) {
            $this->RoomId = $param["RoomId"];
        }

        if (array_key_exists("PageNumber",$param) and $param["PageNumber"] !== null) {
            $this->PageNumber = $param["PageNumber"];
        }

        if (array_key_exists("PageSize",$param) and $param["PageSize"] !== null) {
            $this->PageSize = $param["PageSize"];
        }
    }
}
