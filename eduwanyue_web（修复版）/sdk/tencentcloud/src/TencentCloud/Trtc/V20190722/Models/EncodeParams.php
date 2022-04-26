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
 * MCU混流输出流编码参数
 *
 * @method integer getAudioSampleRate() 获取混流-输出流音频采样率
 * @method void setAudioSampleRate(integer $AudioSampleRate) 设置混流-输出流音频采样率
 * @method integer getAudioBitrate() 获取混流-输出流音频码率，单位Kbps
 * @method void setAudioBitrate(integer $AudioBitrate) 设置混流-输出流音频码率，单位Kbps
 * @method integer getAudioChannels() 获取混流-输出流音频声道数
 * @method void setAudioChannels(integer $AudioChannels) 设置混流-输出流音频声道数
 * @method integer getVideoWidth() 获取混流-输出流宽，音视频输出时必填
 * @method void setVideoWidth(integer $VideoWidth) 设置混流-输出流宽，音视频输出时必填
 * @method integer getVideoHeight() 获取混流-输出流高，音视频输出时必填
 * @method void setVideoHeight(integer $VideoHeight) 设置混流-输出流高，音视频输出时必填
 * @method integer getVideoBitrate() 获取混流-输出流码率，单位Kbps，音视频输出时必填
 * @method void setVideoBitrate(integer $VideoBitrate) 设置混流-输出流码率，单位Kbps，音视频输出时必填
 * @method integer getVideoFramerate() 获取混流-输出流帧率，音视频输出时必填
 * @method void setVideoFramerate(integer $VideoFramerate) 设置混流-输出流帧率，音视频输出时必填
 * @method integer getVideoGop() 获取混流-输出流gop，音视频输出时必填
 * @method void setVideoGop(integer $VideoGop) 设置混流-输出流gop，音视频输出时必填
 * @method integer getBackgroundColor() 获取混流-输出流背景色
 * @method void setBackgroundColor(integer $BackgroundColor) 设置混流-输出流背景色
 */
class EncodeParams extends AbstractModel
{
    /**
     * @var integer 混流-输出流音频采样率
     */
    public $AudioSampleRate;

    /**
     * @var integer 混流-输出流音频码率，单位Kbps
     */
    public $AudioBitrate;

    /**
     * @var integer 混流-输出流音频声道数
     */
    public $AudioChannels;

    /**
     * @var integer 混流-输出流宽，音视频输出时必填
     */
    public $VideoWidth;

    /**
     * @var integer 混流-输出流高，音视频输出时必填
     */
    public $VideoHeight;

    /**
     * @var integer 混流-输出流码率，单位Kbps，音视频输出时必填
     */
    public $VideoBitrate;

    /**
     * @var integer 混流-输出流帧率，音视频输出时必填
     */
    public $VideoFramerate;

    /**
     * @var integer 混流-输出流gop，音视频输出时必填
     */
    public $VideoGop;

    /**
     * @var integer 混流-输出流背景色
     */
    public $BackgroundColor;

    /**
     * @param integer $AudioSampleRate 混流-输出流音频采样率
     * @param integer $AudioBitrate 混流-输出流音频码率，单位Kbps
     * @param integer $AudioChannels 混流-输出流音频声道数
     * @param integer $VideoWidth 混流-输出流宽，音视频输出时必填
     * @param integer $VideoHeight 混流-输出流高，音视频输出时必填
     * @param integer $VideoBitrate 混流-输出流码率，单位Kbps，音视频输出时必填
     * @param integer $VideoFramerate 混流-输出流帧率，音视频输出时必填
     * @param integer $VideoGop 混流-输出流gop，音视频输出时必填
     * @param integer $BackgroundColor 混流-输出流背景色
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
        if (array_key_exists("AudioSampleRate",$param) and $param["AudioSampleRate"] !== null) {
            $this->AudioSampleRate = $param["AudioSampleRate"];
        }

        if (array_key_exists("AudioBitrate",$param) and $param["AudioBitrate"] !== null) {
            $this->AudioBitrate = $param["AudioBitrate"];
        }

        if (array_key_exists("AudioChannels",$param) and $param["AudioChannels"] !== null) {
            $this->AudioChannels = $param["AudioChannels"];
        }

        if (array_key_exists("VideoWidth",$param) and $param["VideoWidth"] !== null) {
            $this->VideoWidth = $param["VideoWidth"];
        }

        if (array_key_exists("VideoHeight",$param) and $param["VideoHeight"] !== null) {
            $this->VideoHeight = $param["VideoHeight"];
        }

        if (array_key_exists("VideoBitrate",$param) and $param["VideoBitrate"] !== null) {
            $this->VideoBitrate = $param["VideoBitrate"];
        }

        if (array_key_exists("VideoFramerate",$param) and $param["VideoFramerate"] !== null) {
            $this->VideoFramerate = $param["VideoFramerate"];
        }

        if (array_key_exists("VideoGop",$param) and $param["VideoGop"] !== null) {
            $this->VideoGop = $param["VideoGop"];
        }

        if (array_key_exists("BackgroundColor",$param) and $param["BackgroundColor"] !== null) {
            $this->BackgroundColor = $param["BackgroundColor"];
        }
    }
}
