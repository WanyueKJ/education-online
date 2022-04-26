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
namespace TencentCloud\Cms\V20190321\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 文本识别结果详情
 *
 * @method integer getEvilFlag() 获取是否恶意 0：正常 1：可疑
 * @method void setEvilFlag(integer $EvilFlag) 设置是否恶意 0：正常 1：可疑
 * @method integer getEvilType() 获取恶意类型
100：正常
20001：政治
20002：色情 
20006：涉毒违法
20007：谩骂
20105：广告引流 
24001：暴恐
 * @method void setEvilType(integer $EvilType) 设置恶意类型
100：正常
20001：政治
20002：色情 
20006：涉毒违法
20007：谩骂
20105：广告引流 
24001：暴恐
 * @method TextOutputComm getCommon() 获取消息类公共相关参数
 * @method void setCommon(TextOutputComm $Common) 设置消息类公共相关参数
 * @method TextOutputID getID() 获取消息类ID信息
 * @method void setID(TextOutputID $ID) 设置消息类ID信息
 * @method TextOutputRes getRes() 获取消息类输出结果
 * @method void setRes(TextOutputRes $Res) 设置消息类输出结果
 * @method array getKeywords() 获取命中的关键词
 * @method void setKeywords(array $Keywords) 设置命中的关键词
 */
class TextData extends AbstractModel
{
    /**
     * @var integer 是否恶意 0：正常 1：可疑
     */
    public $EvilFlag;

    /**
     * @var integer 恶意类型
100：正常
20001：政治
20002：色情 
20006：涉毒违法
20007：谩骂
20105：广告引流 
24001：暴恐
     */
    public $EvilType;

    /**
     * @var TextOutputComm 消息类公共相关参数
     */
    public $Common;

    /**
     * @var TextOutputID 消息类ID信息
     */
    public $ID;

    /**
     * @var TextOutputRes 消息类输出结果
     */
    public $Res;

    /**
     * @var array 命中的关键词
     */
    public $Keywords;

    /**
     * @param integer $EvilFlag 是否恶意 0：正常 1：可疑
     * @param integer $EvilType 恶意类型
100：正常
20001：政治
20002：色情 
20006：涉毒违法
20007：谩骂
20105：广告引流 
24001：暴恐
     * @param TextOutputComm $Common 消息类公共相关参数
     * @param TextOutputID $ID 消息类ID信息
     * @param TextOutputRes $Res 消息类输出结果
     * @param array $Keywords 命中的关键词
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
        if (array_key_exists("EvilFlag",$param) and $param["EvilFlag"] !== null) {
            $this->EvilFlag = $param["EvilFlag"];
        }

        if (array_key_exists("EvilType",$param) and $param["EvilType"] !== null) {
            $this->EvilType = $param["EvilType"];
        }

        if (array_key_exists("Common",$param) and $param["Common"] !== null) {
            $this->Common = new TextOutputComm();
            $this->Common->deserialize($param["Common"]);
        }

        if (array_key_exists("ID",$param) and $param["ID"] !== null) {
            $this->ID = new TextOutputID();
            $this->ID->deserialize($param["ID"]);
        }

        if (array_key_exists("Res",$param) and $param["Res"] !== null) {
            $this->Res = new TextOutputRes();
            $this->Res->deserialize($param["Res"]);
        }

        if (array_key_exists("Keywords",$param) and $param["Keywords"] !== null) {
            $this->Keywords = $param["Keywords"];
        }
    }
}
