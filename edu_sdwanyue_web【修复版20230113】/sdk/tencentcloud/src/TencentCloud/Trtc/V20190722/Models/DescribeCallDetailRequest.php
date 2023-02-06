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
 * DescribeCallDetail请求参数结构体
 *
 * @method string getCommId() 获取通话ID（唯一标识一次通话）= sdkappid+roomgString（房间号）+房间创建时间（unix时间戳，s）。通过 DescribeRoomInformation（查询房间列表）接口获取。
 * @method void setCommId(string $CommId) 设置通话ID（唯一标识一次通话）= sdkappid+roomgString（房间号）+房间创建时间（unix时间戳，s）。通过 DescribeRoomInformation（查询房间列表）接口获取。
 * @method integer getStartTime() 获取查询开始时间
 * @method void setStartTime(integer $StartTime) 设置查询开始时间
 * @method integer getEndTime() 获取查询结束时间
 * @method void setEndTime(integer $EndTime) 设置查询结束时间
 * @method string getSdkAppId() 获取用户sdkappid
 * @method void setSdkAppId(string $SdkAppId) 设置用户sdkappid
 * @method array getUserIds() 获取需查询的用户数组，不填默认返回6个用户,最多可填6个用户
 * @method void setUserIds(array $UserIds) 设置需查询的用户数组，不填默认返回6个用户,最多可填6个用户
 * @method array getDataType() 获取需查询的指标，不填则只返回用户列表，填all则返回所有指标。
appCpu：APP CPU使用率；
sysCpu：系统 CPU使用率；
aBit：上/下行音频码率；
aBlock：音频卡顿时长；
vBit：上/下行视频码率；
vCapFps：视频采集帧率；
vEncFps：视频发送帧率；
vDecFps：渲染帧率；
vBlock：视频卡顿时长；
aLoss：上/下行音频丢包；
vLoss：上/下行视频丢包；
vWidth：上/下行分辨率宽；
vHeight：上/下行分辨率高
 * @method void setDataType(array $DataType) 设置需查询的指标，不填则只返回用户列表，填all则返回所有指标。
appCpu：APP CPU使用率；
sysCpu：系统 CPU使用率；
aBit：上/下行音频码率；
aBlock：音频卡顿时长；
vBit：上/下行视频码率；
vCapFps：视频采集帧率；
vEncFps：视频发送帧率；
vDecFps：渲染帧率；
vBlock：视频卡顿时长；
aLoss：上/下行音频丢包；
vLoss：上/下行视频丢包；
vWidth：上/下行分辨率宽；
vHeight：上/下行分辨率高
 */
class DescribeCallDetailRequest extends AbstractModel
{
    /**
     * @var string 通话ID（唯一标识一次通话）= sdkappid+roomgString（房间号）+房间创建时间（unix时间戳，s）。通过 DescribeRoomInformation（查询房间列表）接口获取。
     */
    public $CommId;

    /**
     * @var integer 查询开始时间
     */
    public $StartTime;

    /**
     * @var integer 查询结束时间
     */
    public $EndTime;

    /**
     * @var string 用户sdkappid
     */
    public $SdkAppId;

    /**
     * @var array 需查询的用户数组，不填默认返回6个用户,最多可填6个用户
     */
    public $UserIds;

    /**
     * @var array 需查询的指标，不填则只返回用户列表，填all则返回所有指标。
appCpu：APP CPU使用率；
sysCpu：系统 CPU使用率；
aBit：上/下行音频码率；
aBlock：音频卡顿时长；
vBit：上/下行视频码率；
vCapFps：视频采集帧率；
vEncFps：视频发送帧率；
vDecFps：渲染帧率；
vBlock：视频卡顿时长；
aLoss：上/下行音频丢包；
vLoss：上/下行视频丢包；
vWidth：上/下行分辨率宽；
vHeight：上/下行分辨率高
     */
    public $DataType;

    /**
     * @param string $CommId 通话ID（唯一标识一次通话）= sdkappid+roomgString（房间号）+房间创建时间（unix时间戳，s）。通过 DescribeRoomInformation（查询房间列表）接口获取。
     * @param integer $StartTime 查询开始时间
     * @param integer $EndTime 查询结束时间
     * @param string $SdkAppId 用户sdkappid
     * @param array $UserIds 需查询的用户数组，不填默认返回6个用户,最多可填6个用户
     * @param array $DataType 需查询的指标，不填则只返回用户列表，填all则返回所有指标。
appCpu：APP CPU使用率；
sysCpu：系统 CPU使用率；
aBit：上/下行音频码率；
aBlock：音频卡顿时长；
vBit：上/下行视频码率；
vCapFps：视频采集帧率；
vEncFps：视频发送帧率；
vDecFps：渲染帧率；
vBlock：视频卡顿时长；
aLoss：上/下行音频丢包；
vLoss：上/下行视频丢包；
vWidth：上/下行分辨率宽；
vHeight：上/下行分辨率高
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
        if (array_key_exists("CommId",$param) and $param["CommId"] !== null) {
            $this->CommId = $param["CommId"];
        }

        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("SdkAppId",$param) and $param["SdkAppId"] !== null) {
            $this->SdkAppId = $param["SdkAppId"];
        }

        if (array_key_exists("UserIds",$param) and $param["UserIds"] !== null) {
            $this->UserIds = $param["UserIds"];
        }

        if (array_key_exists("DataType",$param) and $param["DataType"] !== null) {
            $this->DataType = $param["DataType"];
        }
    }
}
