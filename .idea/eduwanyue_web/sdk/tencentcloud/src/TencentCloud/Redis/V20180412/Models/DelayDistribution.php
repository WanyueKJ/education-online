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
namespace TencentCloud\Redis\V20180412\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 延时分布详情
 *
 * @method integer getLadder() 获取分布阶梯
 * @method void setLadder(integer $Ladder) 设置分布阶梯
 * @method integer getSize() 获取大小
 * @method void setSize(integer $Size) 设置大小
 * @method integer getUpdatetime() 获取修改时间
 * @method void setUpdatetime(integer $Updatetime) 设置修改时间
 */
class DelayDistribution extends AbstractModel
{
    /**
     * @var integer 分布阶梯
     */
    public $Ladder;

    /**
     * @var integer 大小
     */
    public $Size;

    /**
     * @var integer 修改时间
     */
    public $Updatetime;

    /**
     * @param integer $Ladder 分布阶梯
     * @param integer $Size 大小
     * @param integer $Updatetime 修改时间
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
        if (array_key_exists("Ladder",$param) and $param["Ladder"] !== null) {
            $this->Ladder = $param["Ladder"];
        }

        if (array_key_exists("Size",$param) and $param["Size"] !== null) {
            $this->Size = $param["Size"];
        }

        if (array_key_exists("Updatetime",$param) and $param["Updatetime"] !== null) {
            $this->Updatetime = $param["Updatetime"];
        }
    }
}
