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
namespace TencentCloud\Ocr\V20181119\Models;
use TencentCloud\Common\AbstractModel;

/**
 * BankCardOCR返回参数结构体
 *
 * @method string getCardNo() 获取卡号
 * @method void setCardNo(string $CardNo) 设置卡号
 * @method string getBankInfo() 获取银行信息
 * @method void setBankInfo(string $BankInfo) 设置银行信息
 * @method string getValidDate() 获取有效期
 * @method void setValidDate(string $ValidDate) 设置有效期
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */
class BankCardOCRResponse extends AbstractModel
{
    /**
     * @var string 卡号
     */
    public $CardNo;

    /**
     * @var string 银行信息
     */
    public $BankInfo;

    /**
     * @var string 有效期
     */
    public $ValidDate;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param string $CardNo 卡号
     * @param string $BankInfo 银行信息
     * @param string $ValidDate 有效期
     * @param string $RequestId 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("CardNo",$param) and $param["CardNo"] !== null) {
            $this->CardNo = $param["CardNo"];
        }

        if (array_key_exists("BankInfo",$param) and $param["BankInfo"] !== null) {
            $this->BankInfo = $param["BankInfo"];
        }

        if (array_key_exists("ValidDate",$param) and $param["ValidDate"] !== null) {
            $this->ValidDate = $param["ValidDate"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
