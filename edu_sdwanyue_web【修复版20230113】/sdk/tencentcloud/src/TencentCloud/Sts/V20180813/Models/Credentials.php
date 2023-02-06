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
namespace TencentCloud\Sts\V20180813\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 临时证书
 *
 * @method string getToken() 获取token
 * @method void setToken(string $Token) 设置token
 * @method string getTmpSecretId() 获取临时证书密钥ID
 * @method void setTmpSecretId(string $TmpSecretId) 设置临时证书密钥ID
 * @method string getTmpSecretKey() 获取临时证书密钥Key
 * @method void setTmpSecretKey(string $TmpSecretKey) 设置临时证书密钥Key
 */
class Credentials extends AbstractModel
{
    /**
     * @var string token
     */
    public $Token;

    /**
     * @var string 临时证书密钥ID
     */
    public $TmpSecretId;

    /**
     * @var string 临时证书密钥Key
     */
    public $TmpSecretKey;

    /**
     * @param string $Token token
     * @param string $TmpSecretId 临时证书密钥ID
     * @param string $TmpSecretKey 临时证书密钥Key
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
        if (array_key_exists("Token",$param) and $param["Token"] !== null) {
            $this->Token = $param["Token"];
        }

        if (array_key_exists("TmpSecretId",$param) and $param["TmpSecretId"] !== null) {
            $this->TmpSecretId = $param["TmpSecretId"];
        }

        if (array_key_exists("TmpSecretKey",$param) and $param["TmpSecretKey"] !== null) {
            $this->TmpSecretKey = $param["TmpSecretKey"];
        }
    }
}
