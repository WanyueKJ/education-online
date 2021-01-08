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
namespace TencentCloud\Kms\V20190118\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateWhiteBoxKey返回参数结构体
 *
 * @method string getEncryptKey() 获取用于加密的密钥，base64编码
 * @method void setEncryptKey(string $EncryptKey) 设置用于加密的密钥，base64编码
 * @method string getDecryptKey() 获取用于解密的密钥，base64编码
 * @method void setDecryptKey(string $DecryptKey) 设置用于解密的密钥，base64编码
 * @method string getKeyId() 获取白盒密钥的全局唯一标识符
 * @method void setKeyId(string $KeyId) 设置白盒密钥的全局唯一标识符
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */
class CreateWhiteBoxKeyResponse extends AbstractModel
{
    /**
     * @var string 用于加密的密钥，base64编码
     */
    public $EncryptKey;

    /**
     * @var string 用于解密的密钥，base64编码
     */
    public $DecryptKey;

    /**
     * @var string 白盒密钥的全局唯一标识符
     */
    public $KeyId;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param string $EncryptKey 用于加密的密钥，base64编码
     * @param string $DecryptKey 用于解密的密钥，base64编码
     * @param string $KeyId 白盒密钥的全局唯一标识符
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
        if (array_key_exists("EncryptKey",$param) and $param["EncryptKey"] !== null) {
            $this->EncryptKey = $param["EncryptKey"];
        }

        if (array_key_exists("DecryptKey",$param) and $param["DecryptKey"] !== null) {
            $this->DecryptKey = $param["DecryptKey"];
        }

        if (array_key_exists("KeyId",$param) and $param["KeyId"] !== null) {
            $this->KeyId = $param["KeyId"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
