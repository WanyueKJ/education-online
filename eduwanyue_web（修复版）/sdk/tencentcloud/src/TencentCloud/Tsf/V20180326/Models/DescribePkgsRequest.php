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
namespace TencentCloud\Tsf\V20180326\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribePkgs请求参数结构体
 *
 * @method string getApplicationId() 获取应用ID（只传入应用ID，返回该应用下所有软件包信息）
 * @method void setApplicationId(string $ApplicationId) 设置应用ID（只传入应用ID，返回该应用下所有软件包信息）
 * @method string getSearchWord() 获取查询关键字（支持根据包ID，包名，包版本号搜索）
 * @method void setSearchWord(string $SearchWord) 设置查询关键字（支持根据包ID，包名，包版本号搜索）
 * @method string getOrderBy() 获取排序关键字（默认为"UploadTime"：上传时间）
 * @method void setOrderBy(string $OrderBy) 设置排序关键字（默认为"UploadTime"：上传时间）
 * @method integer getOrderType() 获取升序：0/降序：1（默认降序）
 * @method void setOrderType(integer $OrderType) 设置升序：0/降序：1（默认降序）
 * @method integer getOffset() 获取查询起始偏移
 * @method void setOffset(integer $Offset) 设置查询起始偏移
 * @method integer getLimit() 获取返回数量限制
 * @method void setLimit(integer $Limit) 设置返回数量限制
 */
class DescribePkgsRequest extends AbstractModel
{
    /**
     * @var string 应用ID（只传入应用ID，返回该应用下所有软件包信息）
     */
    public $ApplicationId;

    /**
     * @var string 查询关键字（支持根据包ID，包名，包版本号搜索）
     */
    public $SearchWord;

    /**
     * @var string 排序关键字（默认为"UploadTime"：上传时间）
     */
    public $OrderBy;

    /**
     * @var integer 升序：0/降序：1（默认降序）
     */
    public $OrderType;

    /**
     * @var integer 查询起始偏移
     */
    public $Offset;

    /**
     * @var integer 返回数量限制
     */
    public $Limit;

    /**
     * @param string $ApplicationId 应用ID（只传入应用ID，返回该应用下所有软件包信息）
     * @param string $SearchWord 查询关键字（支持根据包ID，包名，包版本号搜索）
     * @param string $OrderBy 排序关键字（默认为"UploadTime"：上传时间）
     * @param integer $OrderType 升序：0/降序：1（默认降序）
     * @param integer $Offset 查询起始偏移
     * @param integer $Limit 返回数量限制
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
        if (array_key_exists("ApplicationId",$param) and $param["ApplicationId"] !== null) {
            $this->ApplicationId = $param["ApplicationId"];
        }

        if (array_key_exists("SearchWord",$param) and $param["SearchWord"] !== null) {
            $this->SearchWord = $param["SearchWord"];
        }

        if (array_key_exists("OrderBy",$param) and $param["OrderBy"] !== null) {
            $this->OrderBy = $param["OrderBy"];
        }

        if (array_key_exists("OrderType",$param) and $param["OrderType"] !== null) {
            $this->OrderType = $param["OrderType"];
        }

        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }
    }
}
