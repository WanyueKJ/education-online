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
namespace TencentCloud\Faceid\V20180301\Models;
use TencentCloud\Common\AbstractModel;

/**
 * MinorsVerification返回参数结构体
 *
 * @method string getResult() 获取结果码，收费情况如下。
收费结果码：
0: 成年
-1: 未成年
-2: 手机号未实名
-3: 姓名和身份证号不一致

不收费结果码：
-4: 非法身份证号（长度、校验位等不正确）
-5: 非法姓名（长度、格式等不正确）
-6: 数据源服务异常
-7: 数据源中无此身份证记录
-8: 公安比对系统升级中，请稍后再试
 * @method void setResult(string $Result) 设置结果码，收费情况如下。
收费结果码：
0: 成年
-1: 未成年
-2: 手机号未实名
-3: 姓名和身份证号不一致

不收费结果码：
-4: 非法身份证号（长度、校验位等不正确）
-5: 非法姓名（长度、格式等不正确）
-6: 数据源服务异常
-7: 数据源中无此身份证记录
-8: 公安比对系统升级中，请稍后再试
 * @method string getDescription() 获取业务结果描述。
 * @method void setDescription(string $Description) 设置业务结果描述。
 * @method string getAgeRange() 获取当结果码为0或者-1时，该字段的值为年龄区间。
格式为[a,b)，表示年龄在a岁以上（包括a岁），b岁以下（不包括b岁）。若b为+时表示没有上限。
 * @method void setAgeRange(string $AgeRange) 设置当结果码为0或者-1时，该字段的值为年龄区间。
格式为[a,b)，表示年龄在a岁以上（包括a岁），b岁以下（不包括b岁）。若b为+时表示没有上限。
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */
class MinorsVerificationResponse extends AbstractModel
{
    /**
     * @var string 结果码，收费情况如下。
收费结果码：
0: 成年
-1: 未成年
-2: 手机号未实名
-3: 姓名和身份证号不一致

不收费结果码：
-4: 非法身份证号（长度、校验位等不正确）
-5: 非法姓名（长度、格式等不正确）
-6: 数据源服务异常
-7: 数据源中无此身份证记录
-8: 公安比对系统升级中，请稍后再试
     */
    public $Result;

    /**
     * @var string 业务结果描述。
     */
    public $Description;

    /**
     * @var string 当结果码为0或者-1时，该字段的值为年龄区间。
格式为[a,b)，表示年龄在a岁以上（包括a岁），b岁以下（不包括b岁）。若b为+时表示没有上限。
     */
    public $AgeRange;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param string $Result 结果码，收费情况如下。
收费结果码：
0: 成年
-1: 未成年
-2: 手机号未实名
-3: 姓名和身份证号不一致

不收费结果码：
-4: 非法身份证号（长度、校验位等不正确）
-5: 非法姓名（长度、格式等不正确）
-6: 数据源服务异常
-7: 数据源中无此身份证记录
-8: 公安比对系统升级中，请稍后再试
     * @param string $Description 业务结果描述。
     * @param string $AgeRange 当结果码为0或者-1时，该字段的值为年龄区间。
格式为[a,b)，表示年龄在a岁以上（包括a岁），b岁以下（不包括b岁）。若b为+时表示没有上限。
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
        if (array_key_exists("Result",$param) and $param["Result"] !== null) {
            $this->Result = $param["Result"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }

        if (array_key_exists("AgeRange",$param) and $param["AgeRange"] !== null) {
            $this->AgeRange = $param["AgeRange"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
