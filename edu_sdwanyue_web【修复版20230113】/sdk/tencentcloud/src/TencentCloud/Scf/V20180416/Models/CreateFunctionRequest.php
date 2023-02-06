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
namespace TencentCloud\Scf\V20180416\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateFunction请求参数结构体
 *
 * @method string getFunctionName() 获取创建的函数名称，函数名称支持26个英文字母大小写、数字、连接符和下划线，第一个字符只能以字母开头，最后一个字符不能为连接符或者下划线，名称长度2-60
 * @method void setFunctionName(string $FunctionName) 设置创建的函数名称，函数名称支持26个英文字母大小写、数字、连接符和下划线，第一个字符只能以字母开头，最后一个字符不能为连接符或者下划线，名称长度2-60
 * @method Code getCode() 获取函数的代码. 注意：不能同时指定Cos与ZipFile
 * @method void setCode(Code $Code) 设置函数的代码. 注意：不能同时指定Cos与ZipFile
 * @method string getHandler() 获取函数处理方法名称，名称格式支持 "文件名称.方法名称" 形式，文件名称和函数名称之间以"."隔开，文件名称和函数名称要求以字母开始和结尾，中间允许插入字母、数字、下划线和连接符，文件名称和函数名字的长度要求是 2-60 个字符
 * @method void setHandler(string $Handler) 设置函数处理方法名称，名称格式支持 "文件名称.方法名称" 形式，文件名称和函数名称之间以"."隔开，文件名称和函数名称要求以字母开始和结尾，中间允许插入字母、数字、下划线和连接符，文件名称和函数名字的长度要求是 2-60 个字符
 * @method string getDescription() 获取函数描述,最大支持 1000 个英文字母、数字、空格、逗号、换行符和英文句号，支持中文
 * @method void setDescription(string $Description) 设置函数描述,最大支持 1000 个英文字母、数字、空格、逗号、换行符和英文句号，支持中文
 * @method integer getMemorySize() 获取函数运行时内存大小，默认为 128M，可选范围 64、128MB-3072MB，并且以 128MB 为阶梯
 * @method void setMemorySize(integer $MemorySize) 设置函数运行时内存大小，默认为 128M，可选范围 64、128MB-3072MB，并且以 128MB 为阶梯
 * @method integer getTimeout() 获取函数最长执行时间，单位为秒，可选值范围 1-900 秒，默认为 3 秒
 * @method void setTimeout(integer $Timeout) 设置函数最长执行时间，单位为秒，可选值范围 1-900 秒，默认为 3 秒
 * @method Environment getEnvironment() 获取函数的环境变量
 * @method void setEnvironment(Environment $Environment) 设置函数的环境变量
 * @method string getRuntime() 获取函数运行环境，目前仅支持 Python2.7，Python3.6，Nodejs6.10，Nodejs8.9，Nodejs10.15， PHP5， PHP7，Golang1 和 Java8，默认Python2.7
 * @method void setRuntime(string $Runtime) 设置函数运行环境，目前仅支持 Python2.7，Python3.6，Nodejs6.10，Nodejs8.9，Nodejs10.15， PHP5， PHP7，Golang1 和 Java8，默认Python2.7
 * @method VpcConfig getVpcConfig() 获取函数的私有网络配置
 * @method void setVpcConfig(VpcConfig $VpcConfig) 设置函数的私有网络配置
 * @method string getNamespace() 获取函数所属命名空间
 * @method void setNamespace(string $Namespace) 设置函数所属命名空间
 * @method string getRole() 获取函数绑定的角色
 * @method void setRole(string $Role) 设置函数绑定的角色
 * @method string getClsLogsetId() 获取函数日志投递到的CLS LogsetID
 * @method void setClsLogsetId(string $ClsLogsetId) 设置函数日志投递到的CLS LogsetID
 * @method string getClsTopicId() 获取函数日志投递到的CLS TopicID
 * @method void setClsTopicId(string $ClsTopicId) 设置函数日志投递到的CLS TopicID
 * @method string getType() 获取函数类型，默认值为Event，创建触发器函数请填写Event，创建HTTP函数级服务请填写HTTP
 * @method void setType(string $Type) 设置函数类型，默认值为Event，创建触发器函数请填写Event，创建HTTP函数级服务请填写HTTP
 * @method string getCodeSource() 获取CodeSource 代码来源，支持以下'ZipFile', 'Cos', 'Demo', 'TempCos', 'Git'之一，使用Git来源必须指定此字段
 * @method void setCodeSource(string $CodeSource) 设置CodeSource 代码来源，支持以下'ZipFile', 'Cos', 'Demo', 'TempCos', 'Git'之一，使用Git来源必须指定此字段
 * @method array getLayers() 获取函数要关联的Layer版本列表，Layer会按照在列表中顺序依次覆盖。
 * @method void setLayers(array $Layers) 设置函数要关联的Layer版本列表，Layer会按照在列表中顺序依次覆盖。
 * @method DeadLetterConfig getDeadLetterConfig() 获取死信队列参数
 * @method void setDeadLetterConfig(DeadLetterConfig $DeadLetterConfig) 设置死信队列参数
 * @method PublicNetConfigIn getPublicNetConfig() 获取公网访问配置
 * @method void setPublicNetConfig(PublicNetConfigIn $PublicNetConfig) 设置公网访问配置
 */
class CreateFunctionRequest extends AbstractModel
{
    /**
     * @var string 创建的函数名称，函数名称支持26个英文字母大小写、数字、连接符和下划线，第一个字符只能以字母开头，最后一个字符不能为连接符或者下划线，名称长度2-60
     */
    public $FunctionName;

    /**
     * @var Code 函数的代码. 注意：不能同时指定Cos与ZipFile
     */
    public $Code;

    /**
     * @var string 函数处理方法名称，名称格式支持 "文件名称.方法名称" 形式，文件名称和函数名称之间以"."隔开，文件名称和函数名称要求以字母开始和结尾，中间允许插入字母、数字、下划线和连接符，文件名称和函数名字的长度要求是 2-60 个字符
     */
    public $Handler;

    /**
     * @var string 函数描述,最大支持 1000 个英文字母、数字、空格、逗号、换行符和英文句号，支持中文
     */
    public $Description;

    /**
     * @var integer 函数运行时内存大小，默认为 128M，可选范围 64、128MB-3072MB，并且以 128MB 为阶梯
     */
    public $MemorySize;

    /**
     * @var integer 函数最长执行时间，单位为秒，可选值范围 1-900 秒，默认为 3 秒
     */
    public $Timeout;

    /**
     * @var Environment 函数的环境变量
     */
    public $Environment;

    /**
     * @var string 函数运行环境，目前仅支持 Python2.7，Python3.6，Nodejs6.10，Nodejs8.9，Nodejs10.15， PHP5， PHP7，Golang1 和 Java8，默认Python2.7
     */
    public $Runtime;

    /**
     * @var VpcConfig 函数的私有网络配置
     */
    public $VpcConfig;

    /**
     * @var string 函数所属命名空间
     */
    public $Namespace;

    /**
     * @var string 函数绑定的角色
     */
    public $Role;

    /**
     * @var string 函数日志投递到的CLS LogsetID
     */
    public $ClsLogsetId;

    /**
     * @var string 函数日志投递到的CLS TopicID
     */
    public $ClsTopicId;

    /**
     * @var string 函数类型，默认值为Event，创建触发器函数请填写Event，创建HTTP函数级服务请填写HTTP
     */
    public $Type;

    /**
     * @var string CodeSource 代码来源，支持以下'ZipFile', 'Cos', 'Demo', 'TempCos', 'Git'之一，使用Git来源必须指定此字段
     */
    public $CodeSource;

    /**
     * @var array 函数要关联的Layer版本列表，Layer会按照在列表中顺序依次覆盖。
     */
    public $Layers;

    /**
     * @var DeadLetterConfig 死信队列参数
     */
    public $DeadLetterConfig;

    /**
     * @var PublicNetConfigIn 公网访问配置
     */
    public $PublicNetConfig;

    /**
     * @param string $FunctionName 创建的函数名称，函数名称支持26个英文字母大小写、数字、连接符和下划线，第一个字符只能以字母开头，最后一个字符不能为连接符或者下划线，名称长度2-60
     * @param Code $Code 函数的代码. 注意：不能同时指定Cos与ZipFile
     * @param string $Handler 函数处理方法名称，名称格式支持 "文件名称.方法名称" 形式，文件名称和函数名称之间以"."隔开，文件名称和函数名称要求以字母开始和结尾，中间允许插入字母、数字、下划线和连接符，文件名称和函数名字的长度要求是 2-60 个字符
     * @param string $Description 函数描述,最大支持 1000 个英文字母、数字、空格、逗号、换行符和英文句号，支持中文
     * @param integer $MemorySize 函数运行时内存大小，默认为 128M，可选范围 64、128MB-3072MB，并且以 128MB 为阶梯
     * @param integer $Timeout 函数最长执行时间，单位为秒，可选值范围 1-900 秒，默认为 3 秒
     * @param Environment $Environment 函数的环境变量
     * @param string $Runtime 函数运行环境，目前仅支持 Python2.7，Python3.6，Nodejs6.10，Nodejs8.9，Nodejs10.15， PHP5， PHP7，Golang1 和 Java8，默认Python2.7
     * @param VpcConfig $VpcConfig 函数的私有网络配置
     * @param string $Namespace 函数所属命名空间
     * @param string $Role 函数绑定的角色
     * @param string $ClsLogsetId 函数日志投递到的CLS LogsetID
     * @param string $ClsTopicId 函数日志投递到的CLS TopicID
     * @param string $Type 函数类型，默认值为Event，创建触发器函数请填写Event，创建HTTP函数级服务请填写HTTP
     * @param string $CodeSource CodeSource 代码来源，支持以下'ZipFile', 'Cos', 'Demo', 'TempCos', 'Git'之一，使用Git来源必须指定此字段
     * @param array $Layers 函数要关联的Layer版本列表，Layer会按照在列表中顺序依次覆盖。
     * @param DeadLetterConfig $DeadLetterConfig 死信队列参数
     * @param PublicNetConfigIn $PublicNetConfig 公网访问配置
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
        if (array_key_exists("FunctionName",$param) and $param["FunctionName"] !== null) {
            $this->FunctionName = $param["FunctionName"];
        }

        if (array_key_exists("Code",$param) and $param["Code"] !== null) {
            $this->Code = new Code();
            $this->Code->deserialize($param["Code"]);
        }

        if (array_key_exists("Handler",$param) and $param["Handler"] !== null) {
            $this->Handler = $param["Handler"];
        }

        if (array_key_exists("Description",$param) and $param["Description"] !== null) {
            $this->Description = $param["Description"];
        }

        if (array_key_exists("MemorySize",$param) and $param["MemorySize"] !== null) {
            $this->MemorySize = $param["MemorySize"];
        }

        if (array_key_exists("Timeout",$param) and $param["Timeout"] !== null) {
            $this->Timeout = $param["Timeout"];
        }

        if (array_key_exists("Environment",$param) and $param["Environment"] !== null) {
            $this->Environment = new Environment();
            $this->Environment->deserialize($param["Environment"]);
        }

        if (array_key_exists("Runtime",$param) and $param["Runtime"] !== null) {
            $this->Runtime = $param["Runtime"];
        }

        if (array_key_exists("VpcConfig",$param) and $param["VpcConfig"] !== null) {
            $this->VpcConfig = new VpcConfig();
            $this->VpcConfig->deserialize($param["VpcConfig"]);
        }

        if (array_key_exists("Namespace",$param) and $param["Namespace"] !== null) {
            $this->Namespace = $param["Namespace"];
        }

        if (array_key_exists("Role",$param) and $param["Role"] !== null) {
            $this->Role = $param["Role"];
        }

        if (array_key_exists("ClsLogsetId",$param) and $param["ClsLogsetId"] !== null) {
            $this->ClsLogsetId = $param["ClsLogsetId"];
        }

        if (array_key_exists("ClsTopicId",$param) and $param["ClsTopicId"] !== null) {
            $this->ClsTopicId = $param["ClsTopicId"];
        }

        if (array_key_exists("Type",$param) and $param["Type"] !== null) {
            $this->Type = $param["Type"];
        }

        if (array_key_exists("CodeSource",$param) and $param["CodeSource"] !== null) {
            $this->CodeSource = $param["CodeSource"];
        }

        if (array_key_exists("Layers",$param) and $param["Layers"] !== null) {
            $this->Layers = [];
            foreach ($param["Layers"] as $key => $value){
                $obj = new LayerVersionSimple();
                $obj->deserialize($value);
                array_push($this->Layers, $obj);
            }
        }

        if (array_key_exists("DeadLetterConfig",$param) and $param["DeadLetterConfig"] !== null) {
            $this->DeadLetterConfig = new DeadLetterConfig();
            $this->DeadLetterConfig->deserialize($param["DeadLetterConfig"]);
        }

        if (array_key_exists("PublicNetConfig",$param) and $param["PublicNetConfig"] !== null) {
            $this->PublicNetConfig = new PublicNetConfigIn();
            $this->PublicNetConfig->deserialize($param["PublicNetConfig"]);
        }
    }
}
