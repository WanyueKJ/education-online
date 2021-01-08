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
namespace TencentCloud\Gse\V20191112\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribePlayerSessions请求参数结构体
 *
 * @method string getGameServerSessionId() 获取游戏服务器会话ID
 * @method void setGameServerSessionId(string $GameServerSessionId) 设置游戏服务器会话ID
 * @method integer getLimit() 获取单次查询记录数上限
 * @method void setLimit(integer $Limit) 设置单次查询记录数上限
 * @method string getNextToken() 获取页偏移，用于查询下一页
 * @method void setNextToken(string $NextToken) 设置页偏移，用于查询下一页
 * @method string getPlayerId() 获取玩家ID
 * @method void setPlayerId(string $PlayerId) 设置玩家ID
 * @method string getPlayerSessionId() 获取玩家会话ID
 * @method void setPlayerSessionId(string $PlayerSessionId) 设置玩家会话ID
 * @method string getPlayerSessionStatusFilter() 获取玩家会话状态（RESERVED,ACTIVE,COMPLETED,TIMEDOUT）
 * @method void setPlayerSessionStatusFilter(string $PlayerSessionStatusFilter) 设置玩家会话状态（RESERVED,ACTIVE,COMPLETED,TIMEDOUT）
 */
class DescribePlayerSessionsRequest extends AbstractModel
{
    /**
     * @var string 游戏服务器会话ID
     */
    public $GameServerSessionId;

    /**
     * @var integer 单次查询记录数上限
     */
    public $Limit;

    /**
     * @var string 页偏移，用于查询下一页
     */
    public $NextToken;

    /**
     * @var string 玩家ID
     */
    public $PlayerId;

    /**
     * @var string 玩家会话ID
     */
    public $PlayerSessionId;

    /**
     * @var string 玩家会话状态（RESERVED,ACTIVE,COMPLETED,TIMEDOUT）
     */
    public $PlayerSessionStatusFilter;

    /**
     * @param string $GameServerSessionId 游戏服务器会话ID
     * @param integer $Limit 单次查询记录数上限
     * @param string $NextToken 页偏移，用于查询下一页
     * @param string $PlayerId 玩家ID
     * @param string $PlayerSessionId 玩家会话ID
     * @param string $PlayerSessionStatusFilter 玩家会话状态（RESERVED,ACTIVE,COMPLETED,TIMEDOUT）
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
        if (array_key_exists("GameServerSessionId",$param) and $param["GameServerSessionId"] !== null) {
            $this->GameServerSessionId = $param["GameServerSessionId"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }

        if (array_key_exists("NextToken",$param) and $param["NextToken"] !== null) {
            $this->NextToken = $param["NextToken"];
        }

        if (array_key_exists("PlayerId",$param) and $param["PlayerId"] !== null) {
            $this->PlayerId = $param["PlayerId"];
        }

        if (array_key_exists("PlayerSessionId",$param) and $param["PlayerSessionId"] !== null) {
            $this->PlayerSessionId = $param["PlayerSessionId"];
        }

        if (array_key_exists("PlayerSessionStatusFilter",$param) and $param["PlayerSessionStatusFilter"] !== null) {
            $this->PlayerSessionStatusFilter = $param["PlayerSessionStatusFilter"];
        }
    }
}
