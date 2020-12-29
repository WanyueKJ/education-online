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

namespace TencentCloud\Faceid\V20180301;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Faceid\V20180301\Models as Models;

/**
 * @method Models\BankCard2EVerificationResponse BankCard2EVerification(Models\BankCard2EVerificationRequest $req) 本接口用于校验姓名和银行卡号的真实性和一致性。
 * @method Models\BankCard4EVerificationResponse BankCard4EVerification(Models\BankCard4EVerificationRequest $req) 本接口用于输入银行卡号、姓名、开户证件号、开户手机号，校验信息的真实性和一致性。
 * @method Models\BankCardVerificationResponse BankCardVerification(Models\BankCardVerificationRequest $req) 本接口用于银行卡号、姓名、开户证件号信息的真实性和一致性。
 * @method Models\DetectAuthResponse DetectAuth(Models\DetectAuthRequest $req) 每次调用人脸核身SaaS化服务前，需先调用本接口获取BizToken，用来串联核身流程，在验证完成后，用于获取验证结果信息。
 * @method Models\GetActionSequenceResponse GetActionSequence(Models\GetActionSequenceRequest $req) 使用动作活体检测模式前，需调用本接口获取动作顺序。
 * @method Models\GetDetectInfoResponse GetDetectInfo(Models\GetDetectInfoRequest $req) 完成验证后，用BizToken调用本接口获取结果信息，BizToken生成后三天内（3\*24\*3,600秒）可多次拉取。
 * @method Models\GetDetectInfoEnhancedResponse GetDetectInfoEnhanced(Models\GetDetectInfoEnhancedRequest $req) 完成验证后，用BizToken调用本接口获取结果信息，BizToken生成后三天内（3\*24\*3,600秒）可多次拉取。
 * @method Models\GetLiveCodeResponse GetLiveCode(Models\GetLiveCodeRequest $req) 使用数字活体检测模式前，需调用本接口获取数字验证码。
 * @method Models\IdCardOCRVerificationResponse IdCardOCRVerification(Models\IdCardOCRVerificationRequest $req) 本接口用于校验姓名和身份证号的真实性和一致性，您可以通过输入姓名和身份证号或传入身份证人像面照片提供所需验证信息。
 * @method Models\IdCardVerificationResponse IdCardVerification(Models\IdCardVerificationRequest $req) 传入姓名和身份证号，校验两者的真实性和一致性。
 * @method Models\ImageRecognitionResponse ImageRecognition(Models\ImageRecognitionRequest $req) 传入照片和身份信息，判断该照片与公安权威库的证件照是否属于同一个人。
 * @method Models\LivenessResponse Liveness(Models\LivenessRequest $req) 活体检测
 * @method Models\LivenessCompareResponse LivenessCompare(Models\LivenessCompareRequest $req) 传入视频和照片，先判断视频中是否为真人，判断为真人后，再判断该视频中的人与上传照片是否属于同一个人。
 * @method Models\LivenessRecognitionResponse LivenessRecognition(Models\LivenessRecognitionRequest $req) 传入视频和身份信息，先判断视频中是否为真人，判断为真人后，再判断该视频中的人与公安权威库的证件照是否属于同一个人。
 * @method Models\MinorsVerificationResponse MinorsVerification(Models\MinorsVerificationRequest $req) 未成年人守护接口是通过传入手机号或姓名和身份证号，结合权威数据源和腾讯健康守护可信模型，判断该信息是否真实且年满18周岁。腾讯健康守护可信模型覆盖了上十亿手机库源，覆盖率高、准确率高，如果不在库中的手机号，还可以通过姓名+身份证进行兜底验证。
 * @method Models\MobileNetworkTimeVerificationResponse MobileNetworkTimeVerification(Models\MobileNetworkTimeVerificationRequest $req) 本接口用于查询手机号在网时长，输入手机号进行查询。
 * @method Models\MobileStatusResponse MobileStatus(Models\MobileStatusRequest $req) 本接口用于验证手机号的状态，您可以输入手机号进行查询。
 * @method Models\PhoneVerificationResponse PhoneVerification(Models\PhoneVerificationRequest $req) 本接口用于校验手机号、姓名和身份证号的真实性和一致性。
 */

class FaceidClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "faceid.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2018-03-01";

    /**
     * @param Credential $credential
     * @param string $region
     * @param ClientProfile|null $profile
     * @throws TencentCloudSDKException
     */
    function __construct($credential, $region, $profile=null)
    {
        parent::__construct($this->endpoint, $this->version, $credential, $region, $profile);
    }

    public function returnResponse($action, $response)
    {
        $respClass = "TencentCloud"."\\".ucfirst("faceid")."\\"."V20180301\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
