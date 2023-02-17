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
namespace TencentCloud\Live\V20180801\Models;
use TencentCloud\Common\AbstractModel;

/**
 * ModifyLiveTranscodeTemplate请求参数结构体
 *
 * @method integer getTemplateId() 获取模板 Id。
 * @method void setTemplateId(integer $TemplateId) 设置模板 Id。
 * @method string getVcodec() 获取视频编码：
h264/h265。
 * @method void setVcodec(string $Vcodec) 设置视频编码：
h264/h265。
 * @method string getAcodec() 获取音频编码：
aac/mp3。
 * @method void setAcodec(string $Acodec) 设置音频编码：
aac/mp3。
 * @method integer getAudioBitrate() 获取音频码率，默认0。
范围：0-500。
 * @method void setAudioBitrate(integer $AudioBitrate) 设置音频码率，默认0。
范围：0-500。
 * @method string getDescription() 获取模板描述。
 * @method void setDescription(string $Description) 设置模板描述。
 * @method integer getVideoBitrate() 获取视频码率。范围：100kbps - 8000kbps。
注意：码率必须是100的倍数。
 * @method void setVideoBitrate(integer $VideoBitrate) 设置视频码率。范围：100kbps - 8000kbps。
注意：码率必须是100的倍数。
 * @method integer getWidth() 获取宽。0-3000。
 * @method void setWidth(integer $Width) 设置宽。0-3000。
 * @method integer getNeedVideo() 获取是否保留视频，0：否，1：是。默认1。
 * @method void setNeedVideo(integer $NeedVideo) 设置是否保留视频，0：否，1：是。默认1。
 * @method integer getNeedAudio() 获取是否保留音频，0：否，1：是。默认1。
 * @method void setNeedAudio(integer $NeedAudio) 设置是否保留音频，0：否，1：是。默认1。
 * @method integer getHeight() 获取高。0-3000。
 * @method void setHeight(integer $Height) 设置高。0-3000。
 * @method integer getFps() 获取帧率。0-200。
 * @method void setFps(integer $Fps) 设置帧率。0-200。
 * @method integer getGop() 获取关键帧间隔，单位：秒。0-50。
 * @method void setGop(integer $Gop) 设置关键帧间隔，单位：秒。0-50。
 * @method integer getRotate() 获取旋转角度。
0 90 180 270。
 * @method void setRotate(integer $Rotate) 设置旋转角度。
0 90 180 270。
 * @method string getProfile() 获取编码质量：
baseline/main/high。
 * @method void setProfile(string $Profile) 设置编码质量：
baseline/main/high。
 * @method integer getBitrateToOrig() 获取是否不超过原始码率。0：否，1：是。默认0。
 * @method void setBitrateToOrig(integer $BitrateToOrig) 设置是否不超过原始码率。0：否，1：是。默认0。
 * @method integer getHeightToOrig() 获取是否不超过原始高。0：否，1：是。默认0。
 * @method void setHeightToOrig(integer $HeightToOrig) 设置是否不超过原始高。0：否，1：是。默认0。
 * @method integer getFpsToOrig() 获取是否不超过原始帧率。0：否，1：是。默认0。
 * @method void setFpsToOrig(integer $FpsToOrig) 设置是否不超过原始帧率。0：否，1：是。默认0。
 * @method float getAdaptBitratePercent() 获取极速高清相比 VideoBitrate 少多少码率，0.1到0.5。
 * @method void setAdaptBitratePercent(float $AdaptBitratePercent) 设置极速高清相比 VideoBitrate 少多少码率，0.1到0.5。
 */
class ModifyLiveTranscodeTemplateRequest extends AbstractModel
{
    /**
     * @var integer 模板 Id。
     */
    public $TemplateId;

    /**
     * @var string 视频编码：
h264/h265。
     */
    public $Vcodec;

    /**
     * @var string 音频编码：
aac/mp3。
     */
    public $Acodec;

    /**
     * @var integer 音频码率，默认0。
范围：0-500。
     */
    public $AudioBitrate;

    /**
     * @var string 模板描述。
     */
    public $Description;

    /**
     * @var integer 视频码率。范围：100kbps - 8000kbps。
注意：码率必须是100的倍数。
     */
    public $VideoBitrate;

    /**
     * @var integer 宽。0-3000。
     */
    public $Width;

    /**
     * @var integer 是否保留视频，0：否，1：是。默认1。
     */
    public $NeedVideo;

    /**
     * @var integer 是否保留音频，0：否，1：是。默认1。
     */
    public $NeedAudio;

    /**
     * @var integer 高。0-3000。
     */
    public $Height;

    /**
     * @var integer 帧率。0-200。
     */
    public $Fps;

    /**
     * @var integer 关键帧间隔，单位：秒。0-50。
     */
    public $Gop;

    /**
     * @var integer 旋转角度。
0 90 180 270。
     */
    public $Rotate;

    /**
     * @var string 编码质量：
baseline/main/high。
     */
    public $Profile;

    /**
     * @var integer 是否不超过原始码率。0：否，1：是。默认0。
     */
    public $BitrateToOrig;

    /**
     * @var integer 是否不超过原始高。0：否，1：是。默认0。
     */
    public $HeightToOrig;

    /**
     * @var integer 是否不超过原始帧率。0：否，1：是。默认0。
     */
    public $FpsToOrig;

    /**
     * @var float 极速高清相比 VideoBitrate 少多少码率，0.1到0.5。
     */
    public $AdaptBitratePercent;

    /**
     * @param integer $TemplateId 模板 Id。
     * @param string $Vcodec 视频编码：
h264/h265。
     * @param string $Acodec 音频编码：
aac/mp3。
     * @param integer $AudioBitrate 音频码率，默认0。
范围：0-500。
     * @param string $Description 模板描述。
     * @param integer $VideoBitrate 视频码率。范围：100kbps - 8000kbps。
注意：码率必须是100的倍数。
     * @param integer $Width 宽。0-3000。
     * @param integer $NeedVideo 是否保留视频，0：否，1：是。默认1。
     * @param integer $NeedAudio 是否保留音频，0：否，1：是。默认1。
     * @param integer $Height 高。0-3000。
     * @param integer $Fps 帧率。0-200。
     * @param integer $Gop 关键帧间隔，单位：秒。0-50。
     * @param integer $Rotate 旋转角度。
0 90 180 270。
     * @param string $Profile 编码质量：
baseline/main/high。
     * @param integer $BitrateToOrig 是否不超过原始码率。0：否，1：是。默认0。
     * @param integer $HeightToOrig 是否不超过原始高。0：否，1：是。默认0。
     * @param integer $FpsToOrig 是否不超过原始帧率。0：否，1：是。默认0。
     * @param float $AdaptBitratePercent 极速高清相比 VideoBitrate 少多少码率，0.1到0.5。
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
        if (array_key_exists("TemplateId",$param) and $param["TemplateId"] !== null) {
            $this->TemplateId = $param["TemplateId"];
        }

        if (array_key_exists("Vcodec",$param) and $param["Vcodec"] !== null) {
            $this->Vcodec = $param["Vcodec"];
        }

        if (array_key_exists("Acodec",$param) and $param["Acodec"] !== null) {
            $this->Acodec = $param["Acodec"];
        }

        if (array_key_exists("AudioBitrate",$param) and $param["AudioBitrate"] !== null) {
            $this->AudioBitrate = $param["AudioBitrate"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }

        if (array_key_exists("VideoBitrate",$param) and $param["VideoBitrate"] !== null) {
            $this->VideoBitrate = $param["VideoBitrate"];
        }

        if (array_key_exists("Width",$param) and $param["Width"] !== null) {
            $this->Width = $param["Width"];
        }

        if (array_key_exists("NeedVideo",$param) and $param["NeedVideo"] !== null) {
            $this->NeedVideo = $param["NeedVideo"];
        }

        if (array_key_exists("NeedAudio",$param) and $param["NeedAudio"] !== null) {
            $this->NeedAudio = $param["NeedAudio"];
        }

        if (array_key_exists("Height",$param) and $param["Height"] !== null) {
            $this->Height = $param["Height"];
        }

        if (array_key_exists("Fps",$param) and $param["Fps"] !== null) {
            $this->Fps = $param["Fps"];
        }

        if (array_key_exists("Gop",$param) and $param["Gop"] !== null) {
            $this->Gop = $param["Gop"];
        }

        if (array_key_exists("Rotate",$param) and $param["Rotate"] !== null) {
            $this->Rotate = $param["Rotate"];
        }

        if (array_key_exists("Profile",$param) and $param["Profile"] !== null) {
            $this->Profile = $param["Profile"];
        }

        if (array_key_exists("BitrateToOrig",$param) and $param["BitrateToOrig"] !== null) {
            $this->BitrateToOrig = $param["BitrateToOrig"];
        }

        if (array_key_exists("HeightToOrig",$param) and $param["HeightToOrig"] !== null) {
            $this->HeightToOrig = $param["HeightToOrig"];
        }

        if (array_key_exists("FpsToOrig",$param) and $param["FpsToOrig"] !== null) {
            $this->FpsToOrig = $param["FpsToOrig"];
        }

        if (array_key_exists("AdaptBitratePercent",$param) and $param["AdaptBitratePercent"] !== null) {
            $this->AdaptBitratePercent = $param["AdaptBitratePercent"];
        }
    }
}
