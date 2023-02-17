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
 * MCU混流布局参数
 *
 * @method integer getTemplate() 获取混流布局模板ID，0为悬浮模板(默认);1为九宫格模板;2为屏幕分享模板
 * @method void setTemplate(integer $Template) 设置混流布局模板ID，0为悬浮模板(默认);1为九宫格模板;2为屏幕分享模板
 * @method string getMainVideoUserId() 获取屏幕分享模板中有效，代表左侧大画面对应的用户ID
 * @method void setMainVideoUserId(string $MainVideoUserId) 设置屏幕分享模板中有效，代表左侧大画面对应的用户ID
 * @method integer getMainVideoStreamType() 获取屏幕分享模板中有效，代表左侧大画面对应的流类型，0为摄像头，1为屏幕分享
 * @method void setMainVideoStreamType(integer $MainVideoStreamType) 设置屏幕分享模板中有效，代表左侧大画面对应的流类型，0为摄像头，1为屏幕分享
 */
class LayoutParams extends AbstractModel
{
    /**
     * @var integer 混流布局模板ID，0为悬浮模板(默认);1为九宫格模板;2为屏幕分享模板
     */
    public $Template;

    /**
     * @var string 屏幕分享模板中有效，代表左侧大画面对应的用户ID
     */
    public $MainVideoUserId;

    /**
     * @var integer 屏幕分享模板中有效，代表左侧大画面对应的流类型，0为摄像头，1为屏幕分享
     */
    public $MainVideoStreamType;

    /**
     * @param integer $Template 混流布局模板ID，0为悬浮模板(默认);1为九宫格模板;2为屏幕分享模板
     * @param string $MainVideoUserId 屏幕分享模板中有效，代表左侧大画面对应的用户ID
     * @param integer $MainVideoStreamType 屏幕分享模板中有效，代表左侧大画面对应的流类型，0为摄像头，1为屏幕分享
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
        if (array_key_exists("Template",$param) and $param["Template"] !== null) {
            $this->Template = $param["Template"];
        }

        if (array_key_exists("MainVideoUserId",$param) and $param["MainVideoUserId"] !== null) {
            $this->MainVideoUserId = $param["MainVideoUserId"];
        }

        if (array_key_exists("MainVideoStreamType",$param) and $param["MainVideoStreamType"] !== null) {
            $this->MainVideoStreamType = $param["MainVideoStreamType"];
        }
    }
}
