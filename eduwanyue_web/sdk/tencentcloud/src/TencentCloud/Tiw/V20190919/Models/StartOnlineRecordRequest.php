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
namespace TencentCloud\Tiw\V20190919\Models;
use TencentCloud\Common\AbstractModel;

/**
 * StartOnlineRecord请求参数结构体
 *
 * @method integer getSdkAppId() 获取客户的SdkAppId
 * @method void setSdkAppId(integer $SdkAppId) 设置客户的SdkAppId
 * @method integer getRoomId() 获取需要录制的房间号
 * @method void setRoomId(integer $RoomId) 设置需要录制的房间号
 * @method string getRecordUserId() 获取用于实时录制服务进房的用户Id，格式为`tic_record_user_${RoomId}_${Random}`，其中 `${RoomId}` 与录制房间号对应，`${Random}`为一个随机字符串。
实时录制服务会使用这个用户Id进房进行录制房间内的音视频与白板，为了防止进房冲突，请保证此 用户Id不重复
 * @method void setRecordUserId(string $RecordUserId) 设置用于实时录制服务进房的用户Id，格式为`tic_record_user_${RoomId}_${Random}`，其中 `${RoomId}` 与录制房间号对应，`${Random}`为一个随机字符串。
实时录制服务会使用这个用户Id进房进行录制房间内的音视频与白板，为了防止进房冲突，请保证此 用户Id不重复
 * @method string getRecordUserSig() 获取与RecordUserId对应的签名
 * @method void setRecordUserSig(string $RecordUserSig) 设置与RecordUserId对应的签名
 * @method string getGroupId() 获取白板的 IM 群组 Id，默认同房间号
 * @method void setGroupId(string $GroupId) 设置白板的 IM 群组 Id，默认同房间号
 * @method Concat getConcat() 获取实时录制视频拼接参数
 * @method void setConcat(Concat $Concat) 设置实时录制视频拼接参数
 * @method Whiteboard getWhiteboard() 获取实时录制白板参数，例如白板宽高等
 * @method void setWhiteboard(Whiteboard $Whiteboard) 设置实时录制白板参数，例如白板宽高等
 * @method MixStream getMixStream() 获取实时录制混流参数
特别说明：
1. 混流功能需要根据额外开通， 请联系腾讯云互动白板客服人员
2. 使用混流功能，必须提供 Extras 参数，且 Extras 参数中必须包含 "MIX_STREAM"
 * @method void setMixStream(MixStream $MixStream) 设置实时录制混流参数
特别说明：
1. 混流功能需要根据额外开通， 请联系腾讯云互动白板客服人员
2. 使用混流功能，必须提供 Extras 参数，且 Extras 参数中必须包含 "MIX_STREAM"
 * @method array getExtras() 获取使用到的高级功能列表
可以选值列表：
MIX_STREAM - 混流功能
 * @method void setExtras(array $Extras) 设置使用到的高级功能列表
可以选值列表：
MIX_STREAM - 混流功能
 * @method boolean getAudioFileNeeded() 获取是否需要在结果回调中返回各路流的纯音频录制文件，文件格式为mp3
 * @method void setAudioFileNeeded(boolean $AudioFileNeeded) 设置是否需要在结果回调中返回各路流的纯音频录制文件，文件格式为mp3
 */
class StartOnlineRecordRequest extends AbstractModel
{
    /**
     * @var integer 客户的SdkAppId
     */
    public $SdkAppId;

    /**
     * @var integer 需要录制的房间号
     */
    public $RoomId;

    /**
     * @var string 用于实时录制服务进房的用户Id，格式为`tic_record_user_${RoomId}_${Random}`，其中 `${RoomId}` 与录制房间号对应，`${Random}`为一个随机字符串。
实时录制服务会使用这个用户Id进房进行录制房间内的音视频与白板，为了防止进房冲突，请保证此 用户Id不重复
     */
    public $RecordUserId;

    /**
     * @var string 与RecordUserId对应的签名
     */
    public $RecordUserSig;

    /**
     * @var string 白板的 IM 群组 Id，默认同房间号
     */
    public $GroupId;

    /**
     * @var Concat 实时录制视频拼接参数
     */
    public $Concat;

    /**
     * @var Whiteboard 实时录制白板参数，例如白板宽高等
     */
    public $Whiteboard;

    /**
     * @var MixStream 实时录制混流参数
特别说明：
1. 混流功能需要根据额外开通， 请联系腾讯云互动白板客服人员
2. 使用混流功能，必须提供 Extras 参数，且 Extras 参数中必须包含 "MIX_STREAM"
     */
    public $MixStream;

    /**
     * @var array 使用到的高级功能列表
可以选值列表：
MIX_STREAM - 混流功能
     */
    public $Extras;

    /**
     * @var boolean 是否需要在结果回调中返回各路流的纯音频录制文件，文件格式为mp3
     */
    public $AudioFileNeeded;

    /**
     * @param integer $SdkAppId 客户的SdkAppId
     * @param integer $RoomId 需要录制的房间号
     * @param string $RecordUserId 用于实时录制服务进房的用户Id，格式为`tic_record_user_${RoomId}_${Random}`，其中 `${RoomId}` 与录制房间号对应，`${Random}`为一个随机字符串。
实时录制服务会使用这个用户Id进房进行录制房间内的音视频与白板，为了防止进房冲突，请保证此 用户Id不重复
     * @param string $RecordUserSig 与RecordUserId对应的签名
     * @param string $GroupId 白板的 IM 群组 Id，默认同房间号
     * @param Concat $Concat 实时录制视频拼接参数
     * @param Whiteboard $Whiteboard 实时录制白板参数，例如白板宽高等
     * @param MixStream $MixStream 实时录制混流参数
特别说明：
1. 混流功能需要根据额外开通， 请联系腾讯云互动白板客服人员
2. 使用混流功能，必须提供 Extras 参数，且 Extras 参数中必须包含 "MIX_STREAM"
     * @param array $Extras 使用到的高级功能列表
可以选值列表：
MIX_STREAM - 混流功能
     * @param boolean $AudioFileNeeded 是否需要在结果回调中返回各路流的纯音频录制文件，文件格式为mp3
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

        if (array_key_exists("RoomId",$param) and $param["RoomId"] !== null) {
            $this->RoomId = $param["RoomId"];
        }

        if (array_key_exists("RecordUserId",$param) and $param["RecordUserId"] !== null) {
            $this->RecordUserId = $param["RecordUserId"];
        }

        if (array_key_exists("RecordUserSig",$param) and $param["RecordUserSig"] !== null) {
            $this->RecordUserSig = $param["RecordUserSig"];
        }

        if (array_key_exists("GroupId",$param) and $param["GroupId"] !== null) {
            $this->GroupId = $param["GroupId"];
        }

        if (array_key_exists("Concat",$param) and $param["Concat"] !== null) {
            $this->Concat = new Concat();
            $this->Concat->deserialize($param["Concat"]);
        }

        if (array_key_exists("Whiteboard",$param) and $param["Whiteboard"] !== null) {
            $this->Whiteboard = new Whiteboard();
            $this->Whiteboard->deserialize($param["Whiteboard"]);
        }

        if (array_key_exists("MixStream",$param) and $param["MixStream"] !== null) {
            $this->MixStream = new MixStream();
            $this->MixStream->deserialize($param["MixStream"]);
        }

        if (array_key_exists("Extras",$param) and $param["Extras"] !== null) {
            $this->Extras = $param["Extras"];
        }

        if (array_key_exists("AudioFileNeeded",$param) and $param["AudioFileNeeded"] !== null) {
            $this->AudioFileNeeded = $param["AudioFileNeeded"];
        }
    }
}
