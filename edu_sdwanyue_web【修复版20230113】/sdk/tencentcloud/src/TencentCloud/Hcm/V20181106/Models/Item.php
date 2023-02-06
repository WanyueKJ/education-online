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
namespace TencentCloud\Hcm\V20181106\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 识别出的算术式信息及评估结果
 *
 * @method string getItem() 获取识别的算式是否正确
 * @method void setItem(string $Item) 设置识别的算式是否正确
 * @method string getItemString() 获取识别的算式
 * @method void setItemString(string $ItemString) 设置识别的算式
 * @method ItemCoord getItemCoord() 获取识别的算式在图片上的位置信息
 * @method void setItemCoord(ItemCoord $ItemCoord) 设置识别的算式在图片上的位置信息
 * @method string getAnswer() 获取推荐的答案，暂不支持多个关系运算符、无关系运算符、单位换算错题的推荐答案返回。
 * @method void setAnswer(string $Answer) 设置推荐的答案，暂不支持多个关系运算符、无关系运算符、单位换算错题的推荐答案返回。
 * @method string getExpressionType() 获取算式题型编号，如加减乘除四则题型，具体题型及编号如下：1 加减乘除四则 2 加减乘除已知结果求运算因子3 判断大小 4 约等于估算 5 带余数除法 6 分数四则运算 7 单位换算 8 竖式加减法 9 竖式乘除法 10 脱式计算 11 解方程
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setExpressionType(string $ExpressionType) 设置算式题型编号，如加减乘除四则题型，具体题型及编号如下：1 加减乘除四则 2 加减乘除已知结果求运算因子3 判断大小 4 约等于估算 5 带余数除法 6 分数四则运算 7 单位换算 8 竖式加减法 9 竖式乘除法 10 脱式计算 11 解方程
注意：此字段可能返回 null，表示取不到有效值。
 */
class Item extends AbstractModel
{
    /**
     * @var string 识别的算式是否正确
     */
    public $Item;

    /**
     * @var string 识别的算式
     */
    public $ItemString;

    /**
     * @var ItemCoord 识别的算式在图片上的位置信息
     */
    public $ItemCoord;

    /**
     * @var string 推荐的答案，暂不支持多个关系运算符、无关系运算符、单位换算错题的推荐答案返回。
     */
    public $Answer;

    /**
     * @var string 算式题型编号，如加减乘除四则题型，具体题型及编号如下：1 加减乘除四则 2 加减乘除已知结果求运算因子3 判断大小 4 约等于估算 5 带余数除法 6 分数四则运算 7 单位换算 8 竖式加减法 9 竖式乘除法 10 脱式计算 11 解方程
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ExpressionType;

    /**
     * @param string $Item 识别的算式是否正确
     * @param string $ItemString 识别的算式
     * @param ItemCoord $ItemCoord 识别的算式在图片上的位置信息
     * @param string $Answer 推荐的答案，暂不支持多个关系运算符、无关系运算符、单位换算错题的推荐答案返回。
     * @param string $ExpressionType 算式题型编号，如加减乘除四则题型，具体题型及编号如下：1 加减乘除四则 2 加减乘除已知结果求运算因子3 判断大小 4 约等于估算 5 带余数除法 6 分数四则运算 7 单位换算 8 竖式加减法 9 竖式乘除法 10 脱式计算 11 解方程
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("Item",$param) and $param["Item"] !== null) {
            $this->Item = $param["Item"];
        }

        if (array_key_exists("ItemString",$param) and $param["ItemString"] !== null) {
            $this->ItemString = $param["ItemString"];
        }

        if (array_key_exists("ItemCoord",$param) and $param["ItemCoord"] !== null) {
            $this->ItemCoord = new ItemCoord();
            $this->ItemCoord->deserialize($param["ItemCoord"]);
        }

        if (array_key_exists("Answer",$param) and $param["Answer"] !== null) {
            $this->Answer = $param["Answer"];
        }

        if (array_key_exists("ExpressionType",$param) and $param["ExpressionType"] !== null) {
            $this->ExpressionType = $param["ExpressionType"];
        }
    }
}
