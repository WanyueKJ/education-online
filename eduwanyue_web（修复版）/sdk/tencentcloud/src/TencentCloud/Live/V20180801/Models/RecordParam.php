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
namespace TencentCloud\Live\V20180801\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 录制模板参数。
 *
 * @method integer getRecordInterval() 获取录制间隔。
单位秒，默认：1800。
取值范围：300-7200。
此参数对 HLS 无效，当录制 HLS 时从推流到断流生成一个文件。
 * @method void setRecordInterval(integer $RecordInterval) 设置录制间隔。
单位秒，默认：1800。
取值范围：300-7200。
此参数对 HLS 无效，当录制 HLS 时从推流到断流生成一个文件。
 * @method integer getStorageTime() 获取录制存储时长。
单位秒，取值范围： 0 - 93312000。
0：表示永久存储。
 * @method void setStorageTime(integer $StorageTime) 设置录制存储时长。
单位秒，取值范围： 0 - 93312000。
0：表示永久存储。
 * @method integer getEnable() 获取是否开启当前格式录制，默认值为0，0：否， 1：是。
 * @method void setEnable(integer $Enable) 设置是否开启当前格式录制，默认值为0，0：否， 1：是。
 * @method integer getVodSubAppId() 获取点播子应用 ID。
 * @method void setVodSubAppId(integer $VodSubAppId) 设置点播子应用 ID。
 * @method string getVodFileName() 获取录制文件名。
 * @method void setVodFileName(string $VodFileName) 设置录制文件名。
 */
class RecordParam extends AbstractModel
{
    /**
     * @var integer 录制间隔。
单位秒，默认：1800。
取值范围：300-7200。
此参数对 HLS 无效，当录制 HLS 时从推流到断流生成一个文件。
     */
    public $RecordInterval;

    /**
     * @var integer 录制存储时长。
单位秒，取值范围： 0 - 93312000。
0：表示永久存储。
     */
    public $StorageTime;

    /**
     * @var integer 是否开启当前格式录制，默认值为0，0：否， 1：是。
     */
    public $Enable;

    /**
     * @var integer 点播子应用 ID。
     */
    public $VodSubAppId;

    /**
     * @var string 录制文件名。
     */
    public $VodFileName;

    /**
     * @param integer $RecordInterval 录制间隔。
单位秒，默认：1800。
取值范围：300-7200。
此参数对 HLS 无效，当录制 HLS 时从推流到断流生成一个文件。
     * @param integer $StorageTime 录制存储时长。
单位秒，取值范围： 0 - 93312000。
0：表示永久存储。
     * @param integer $Enable 是否开启当前格式录制，默认值为0，0：否， 1：是。
     * @param integer $VodSubAppId 点播子应用 ID。
     * @param string $VodFileName 录制文件名。
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
        if (array_key_exists("RecordInterval",$param) and $param["RecordInterval"] !== null) {
            $this->RecordInterval = $param["RecordInterval"];
        }

        if (array_key_exists("StorageTime",$param) and $param["StorageTime"] !== null) {
            $this->StorageTime = $param["StorageTime"];
        }

        if (array_key_exists("Enable",$param) and $param["Enable"] !== null) {
            $this->Enable = $param["Enable"];
        }

        if (array_key_exists("VodSubAppId",$param) and $param["VodSubAppId"] !== null) {
            $this->VodSubAppId = $param["VodSubAppId"];
        }

        if (array_key_exists("VodFileName",$param) and $param["VodFileName"] !== null) {
            $this->VodFileName = $param["VodFileName"];
        }
    }
}
