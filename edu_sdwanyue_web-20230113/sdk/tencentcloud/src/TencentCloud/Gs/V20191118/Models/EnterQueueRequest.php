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
namespace TencentCloud\Gs\V20191118\Models;
use TencentCloud\Common\AbstractModel;

/**
 * EnterQueue请求参数结构体
 *
 * @method boolean getFirst() 获取true：第一次请求排队 false：已在排队中，查询当前排名
 * @method void setFirst(boolean $First) 设置true：第一次请求排队 false：已在排队中，查询当前排名
 * @method string getGameId() 获取游戏ID
 * @method void setGameId(string $GameId) 设置游戏ID
 * @method string getUserId() 获取用户ID
 * @method void setUserId(string $UserId) 设置用户ID
 * @method integer getSetNumber() 获取资源池编号
 * @method void setSetNumber(integer $SetNumber) 设置资源池编号
 */
class EnterQueueRequest extends AbstractModel
{
    /**
     * @var boolean true：第一次请求排队 false：已在排队中，查询当前排名
     */
    public $First;

    /**
     * @var string 游戏ID
     */
    public $GameId;

    /**
     * @var string 用户ID
     */
    public $UserId;

    /**
     * @var integer 资源池编号
     */
    public $SetNumber;

    /**
     * @param boolean $First true：第一次请求排队 false：已在排队中，查询当前排名
     * @param string $GameId 游戏ID
     * @param string $UserId 用户ID
     * @param integer $SetNumber 资源池编号
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
        if (array_key_exists("First",$param) and $param["First"] !== null) {
            $this->First = $param["First"];
        }

        if (array_key_exists("GameId",$param) and $param["GameId"] !== null) {
            $this->GameId = $param["GameId"];
        }

        if (array_key_exists("UserId",$param) and $param["UserId"] !== null) {
            $this->UserId = $param["UserId"];
        }

        if (array_key_exists("SetNumber",$param) and $param["SetNumber"] !== null) {
            $this->SetNumber = $param["SetNumber"];
        }
    }
}
