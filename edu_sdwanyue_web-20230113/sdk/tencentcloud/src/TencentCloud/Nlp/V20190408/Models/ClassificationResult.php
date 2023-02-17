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
namespace TencentCloud\Nlp\V20190408\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 文本分类结果
 *
 * @method string getFirstClassName() 获取一级分类名称
 * @method void setFirstClassName(string $FirstClassName) 设置一级分类名称
 * @method float getFirstClassProbability() 获取一级分类概率
 * @method void setFirstClassProbability(float $FirstClassProbability) 设置一级分类概率
 * @method string getSecondClassName() 获取二级分类名称
 * @method void setSecondClassName(string $SecondClassName) 设置二级分类名称
 * @method float getSecondClassProbability() 获取二级分类概率
 * @method void setSecondClassProbability(float $SecondClassProbability) 设置二级分类概率
 */
class ClassificationResult extends AbstractModel
{
    /**
     * @var string 一级分类名称
     */
    public $FirstClassName;

    /**
     * @var float 一级分类概率
     */
    public $FirstClassProbability;

    /**
     * @var string 二级分类名称
     */
    public $SecondClassName;

    /**
     * @var float 二级分类概率
     */
    public $SecondClassProbability;

    /**
     * @param string $FirstClassName 一级分类名称
     * @param float $FirstClassProbability 一级分类概率
     * @param string $SecondClassName 二级分类名称
     * @param float $SecondClassProbability 二级分类概率
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
        if (array_key_exists("FirstClassName",$param) and $param["FirstClassName"] !== null) {
            $this->FirstClassName = $param["FirstClassName"];
        }

        if (array_key_exists("FirstClassProbability",$param) and $param["FirstClassProbability"] !== null) {
            $this->FirstClassProbability = $param["FirstClassProbability"];
        }

        if (array_key_exists("SecondClassName",$param) and $param["SecondClassName"] !== null) {
            $this->SecondClassName = $param["SecondClassName"];
        }

        if (array_key_exists("SecondClassProbability",$param) and $param["SecondClassProbability"] !== null) {
            $this->SecondClassProbability = $param["SecondClassProbability"];
        }
    }
}
