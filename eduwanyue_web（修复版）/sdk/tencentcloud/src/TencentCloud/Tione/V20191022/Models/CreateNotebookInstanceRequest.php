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
namespace TencentCloud\Tione\V20191022\Models;
use TencentCloud\Common\AbstractModel;

/**
 * CreateNotebookInstance请求参数结构体
 *
 * @method string getNotebookInstanceName() 获取Notebook实例名称
 * @method void setNotebookInstanceName(string $NotebookInstanceName) 设置Notebook实例名称
 * @method string getInstanceType() 获取Notebook算力类型
 * @method void setInstanceType(string $InstanceType) 设置Notebook算力类型
 * @method integer getVolumeSizeInGB() 获取数据卷大小(GB)
 * @method void setVolumeSizeInGB(integer $VolumeSizeInGB) 设置数据卷大小(GB)
 * @method string getDirectInternetAccess() 获取外网访问权限，可取值Enabled/Disabled
 * @method void setDirectInternetAccess(string $DirectInternetAccess) 设置外网访问权限，可取值Enabled/Disabled
 * @method string getRootAccess() 获取Root用户权限，可取值Enabled/Disabled
 * @method void setRootAccess(string $RootAccess) 设置Root用户权限，可取值Enabled/Disabled
 * @method string getSubnetId() 获取子网ID
 * @method void setSubnetId(string $SubnetId) 设置子网ID
 * @method string getLifecycleScriptsName() 获取生命周期脚本名称
 * @method void setLifecycleScriptsName(string $LifecycleScriptsName) 设置生命周期脚本名称
 * @method string getDefaultCodeRepository() 获取默认存储库名称
可以是已创建的存储库名称或者已https://开头的公共git库
 * @method void setDefaultCodeRepository(string $DefaultCodeRepository) 设置默认存储库名称
可以是已创建的存储库名称或者已https://开头的公共git库
 * @method array getAdditionalCodeRepositories() 获取其他存储库列表
每个元素可以是已创建的存储库名称或者已https://开头的公共git库
 * @method void setAdditionalCodeRepositories(array $AdditionalCodeRepositories) 设置其他存储库列表
每个元素可以是已创建的存储库名称或者已https://开头的公共git库
 */
class CreateNotebookInstanceRequest extends AbstractModel
{
    /**
     * @var string Notebook实例名称
     */
    public $NotebookInstanceName;

    /**
     * @var string Notebook算力类型
     */
    public $InstanceType;

    /**
     * @var integer 数据卷大小(GB)
     */
    public $VolumeSizeInGB;

    /**
     * @var string 外网访问权限，可取值Enabled/Disabled
     */
    public $DirectInternetAccess;

    /**
     * @var string Root用户权限，可取值Enabled/Disabled
     */
    public $RootAccess;

    /**
     * @var string 子网ID
     */
    public $SubnetId;

    /**
     * @var string 生命周期脚本名称
     */
    public $LifecycleScriptsName;

    /**
     * @var string 默认存储库名称
可以是已创建的存储库名称或者已https://开头的公共git库
     */
    public $DefaultCodeRepository;

    /**
     * @var array 其他存储库列表
每个元素可以是已创建的存储库名称或者已https://开头的公共git库
     */
    public $AdditionalCodeRepositories;

    /**
     * @param string $NotebookInstanceName Notebook实例名称
     * @param string $InstanceType Notebook算力类型
     * @param integer $VolumeSizeInGB 数据卷大小(GB)
     * @param string $DirectInternetAccess 外网访问权限，可取值Enabled/Disabled
     * @param string $RootAccess Root用户权限，可取值Enabled/Disabled
     * @param string $SubnetId 子网ID
     * @param string $LifecycleScriptsName 生命周期脚本名称
     * @param string $DefaultCodeRepository 默认存储库名称
可以是已创建的存储库名称或者已https://开头的公共git库
     * @param array $AdditionalCodeRepositories 其他存储库列表
每个元素可以是已创建的存储库名称或者已https://开头的公共git库
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
        if (array_key_exists("NotebookInstanceName",$param) and $param["NotebookInstanceName"] !== null) {
            $this->NotebookInstanceName = $param["NotebookInstanceName"];
        }

        if (array_key_exists("InstanceType",$param) and $param["InstanceType"] !== null) {
            $this->InstanceType = $param["InstanceType"];
        }

        if (array_key_exists("VolumeSizeInGB",$param) and $param["VolumeSizeInGB"] !== null) {
            $this->VolumeSizeInGB = $param["VolumeSizeInGB"];
        }

        if (array_key_exists("DirectInternetAccess",$param) and $param["DirectInternetAccess"] !== null) {
            $this->DirectInternetAccess = $param["DirectInternetAccess"];
        }

        if (array_key_exists("RootAccess",$param) and $param["RootAccess"] !== null) {
            $this->RootAccess = $param["RootAccess"];
        }

        if (array_key_exists("SubnetId",$param) and $param["SubnetId"] !== null) {
            $this->SubnetId = $param["SubnetId"];
        }

        if (array_key_exists("LifecycleScriptsName",$param) and $param["LifecycleScriptsName"] !== null) {
            $this->LifecycleScriptsName = $param["LifecycleScriptsName"];
        }

        if (array_key_exists("DefaultCodeRepository",$param) and $param["DefaultCodeRepository"] !== null) {
            $this->DefaultCodeRepository = $param["DefaultCodeRepository"];
        }

        if (array_key_exists("AdditionalCodeRepositories",$param) and $param["AdditionalCodeRepositories"] !== null) {
            $this->AdditionalCodeRepositories = $param["AdditionalCodeRepositories"];
        }
    }
}
