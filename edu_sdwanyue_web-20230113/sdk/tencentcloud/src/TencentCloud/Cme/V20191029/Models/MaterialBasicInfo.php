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
namespace TencentCloud\Cme\V20191029\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 素材基本信息。
 *
 * @method string getMaterialId() 获取素材 Id。
 * @method void setMaterialId(string $MaterialId) 设置素材 Id。
 * @method string getMaterialType() 获取素材类型，取值为：音频（AUDIO）、视频（VIDEO）、图片（IMAGE）、链接（LINK）、字幕 （SUBTITLE）、转场（TRANSITION）、滤镜（FILTER）、文本文字（TEXT）、图文动效（TEXT_IMAGE）。
 * @method void setMaterialType(string $MaterialType) 设置素材类型，取值为：音频（AUDIO）、视频（VIDEO）、图片（IMAGE）、链接（LINK）、字幕 （SUBTITLE）、转场（TRANSITION）、滤镜（FILTER）、文本文字（TEXT）、图文动效（TEXT_IMAGE）。
 * @method Entity getOwner() 获取素材归属实体。
 * @method void setOwner(Entity $Owner) 设置素材归属实体。
 * @method string getName() 获取素材名称。
 * @method void setName(string $Name) 设置素材名称。
 * @method string getCreateTime() 获取素材文件的创建时间，使用 ISO 日期格式。
 * @method void setCreateTime(string $CreateTime) 设置素材文件的创建时间，使用 ISO 日期格式。
 * @method string getUpdateTime() 获取素材文件的最近更新时间（如修改视频属性、发起视频处理等会触发更新媒体文件信息的操作），使用 ISO 日期格式。
 * @method void setUpdateTime(string $UpdateTime) 设置素材文件的最近更新时间（如修改视频属性、发起视频处理等会触发更新媒体文件信息的操作），使用 ISO 日期格式。
 * @method string getClassPath() 获取素材的分类目录路径。
 * @method void setClassPath(string $ClassPath) 设置素材的分类目录路径。
 * @method array getTagSet() 获取素材标签信息。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setTagSet(array $TagSet) 设置素材标签信息。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getPreviewUrl() 获取素材媒体文件的预览图。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setPreviewUrl(string $PreviewUrl) 设置素材媒体文件的预览图。
注意：此字段可能返回 null，表示取不到有效值。
 */
class MaterialBasicInfo extends AbstractModel
{
    /**
     * @var string 素材 Id。
     */
    public $MaterialId;

    /**
     * @var string 素材类型，取值为：音频（AUDIO）、视频（VIDEO）、图片（IMAGE）、链接（LINK）、字幕 （SUBTITLE）、转场（TRANSITION）、滤镜（FILTER）、文本文字（TEXT）、图文动效（TEXT_IMAGE）。
     */
    public $MaterialType;

    /**
     * @var Entity 素材归属实体。
     */
    public $Owner;

    /**
     * @var string 素材名称。
     */
    public $Name;

    /**
     * @var string 素材文件的创建时间，使用 ISO 日期格式。
     */
    public $CreateTime;

    /**
     * @var string 素材文件的最近更新时间（如修改视频属性、发起视频处理等会触发更新媒体文件信息的操作），使用 ISO 日期格式。
     */
    public $UpdateTime;

    /**
     * @var string 素材的分类目录路径。
     */
    public $ClassPath;

    /**
     * @var array 素材标签信息。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $TagSet;

    /**
     * @var string 素材媒体文件的预览图。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $PreviewUrl;

    /**
     * @param string $MaterialId 素材 Id。
     * @param string $MaterialType 素材类型，取值为：音频（AUDIO）、视频（VIDEO）、图片（IMAGE）、链接（LINK）、字幕 （SUBTITLE）、转场（TRANSITION）、滤镜（FILTER）、文本文字（TEXT）、图文动效（TEXT_IMAGE）。
     * @param Entity $Owner 素材归属实体。
     * @param string $Name 素材名称。
     * @param string $CreateTime 素材文件的创建时间，使用 ISO 日期格式。
     * @param string $UpdateTime 素材文件的最近更新时间（如修改视频属性、发起视频处理等会触发更新媒体文件信息的操作），使用 ISO 日期格式。
     * @param string $ClassPath 素材的分类目录路径。
     * @param array $TagSet 素材标签信息。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $PreviewUrl 素材媒体文件的预览图。
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("MaterialId",$param) and $param["MaterialId"] !== null) {
            $this->MaterialId = $param["MaterialId"];
        }

        if (array_key_exists("MaterialType",$param) and $param["MaterialType"] !== null) {
            $this->MaterialType = $param["MaterialType"];
        }

        if (array_key_exists("Owner",$param) and $param["Owner"] !== null) {
            $this->Owner = new Entity();
            $this->Owner->deserialize($param["Owner"]);
        }

        if (array_key_exists("Name",$param) and $param["Name"] !== null) {
            $this->Name = $param["Name"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("UpdateTime",$param) and $param["UpdateTime"] !== null) {
            $this->UpdateTime = $param["UpdateTime"];
        }

        if (array_key_exists("ClassPath",$param) and $param["ClassPath"] !== null) {
            $this->ClassPath = $param["ClassPath"];
        }

        if (array_key_exists("TagSet",$param) and $param["TagSet"] !== null) {
            $this->TagSet = $param["TagSet"];
        }

        if (array_key_exists("PreviewUrl",$param) and $param["PreviewUrl"] !== null) {
            $this->PreviewUrl = $param["PreviewUrl"];
        }
    }
}
