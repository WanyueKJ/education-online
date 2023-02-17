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
namespace TencentCloud\Mps\V20190612\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 转码任务输入参数类型
 *
 * @method integer getDefinition() 获取视频转码模板 ID。
 * @method void setDefinition(integer $Definition) 设置视频转码模板 ID。
 * @method RawTranscodeParameter getRawParameter() 获取视频转码自定义参数，当 Definition 填 0 时有效。
该参数用于高度定制场景，建议您优先使用 Definition 指定转码参数。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setRawParameter(RawTranscodeParameter $RawParameter) 设置视频转码自定义参数，当 Definition 填 0 时有效。
该参数用于高度定制场景，建议您优先使用 Definition 指定转码参数。
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getWatermarkSet() 获取水印列表，支持多张图片或文字水印，最大可支持 10 张。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setWatermarkSet(array $WatermarkSet) 设置水印列表，支持多张图片或文字水印，最大可支持 10 张。
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getMosaicSet() 获取马赛克列表，最大可支持 10 张。
 * @method void setMosaicSet(array $MosaicSet) 设置马赛克列表，最大可支持 10 张。
 * @method TaskOutputStorage getOutputStorage() 获取转码后文件的目标存储，不填则继承上层的 OutputStorage 值。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setOutputStorage(TaskOutputStorage $OutputStorage) 设置转码后文件的目标存储，不填则继承上层的 OutputStorage 值。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getOutputObjectPath() 获取转码后主文件的输出路径，可以为相对路径或者绝对路径。如果不填，则默认为相对路径：`{inputName}_transcode_{definition}.{format}`。
 * @method void setOutputObjectPath(string $OutputObjectPath) 设置转码后主文件的输出路径，可以为相对路径或者绝对路径。如果不填，则默认为相对路径：`{inputName}_transcode_{definition}.{format}`。
 * @method string getSegmentObjectName() 获取转码后分片文件的输出路径（转码 HLS 时 ts 的路径），只能为相对路径。如果不填，则默认为：`{inputName}_transcode_{definition}_{number}.{format}`。
 * @method void setSegmentObjectName(string $SegmentObjectName) 设置转码后分片文件的输出路径（转码 HLS 时 ts 的路径），只能为相对路径。如果不填，则默认为：`{inputName}_transcode_{definition}_{number}.{format}`。
 * @method NumberFormat getObjectNumberFormat() 获取转码后输出路径中的`{number}`变量的规则。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setObjectNumberFormat(NumberFormat $ObjectNumberFormat) 设置转码后输出路径中的`{number}`变量的规则。
注意：此字段可能返回 null，表示取不到有效值。
 */
class TranscodeTaskInput extends AbstractModel
{
    /**
     * @var integer 视频转码模板 ID。
     */
    public $Definition;

    /**
     * @var RawTranscodeParameter 视频转码自定义参数，当 Definition 填 0 时有效。
该参数用于高度定制场景，建议您优先使用 Definition 指定转码参数。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $RawParameter;

    /**
     * @var array 水印列表，支持多张图片或文字水印，最大可支持 10 张。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $WatermarkSet;

    /**
     * @var array 马赛克列表，最大可支持 10 张。
     */
    public $MosaicSet;

    /**
     * @var TaskOutputStorage 转码后文件的目标存储，不填则继承上层的 OutputStorage 值。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $OutputStorage;

    /**
     * @var string 转码后主文件的输出路径，可以为相对路径或者绝对路径。如果不填，则默认为相对路径：`{inputName}_transcode_{definition}.{format}`。
     */
    public $OutputObjectPath;

    /**
     * @var string 转码后分片文件的输出路径（转码 HLS 时 ts 的路径），只能为相对路径。如果不填，则默认为：`{inputName}_transcode_{definition}_{number}.{format}`。
     */
    public $SegmentObjectName;

    /**
     * @var NumberFormat 转码后输出路径中的`{number}`变量的规则。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ObjectNumberFormat;

    /**
     * @param integer $Definition 视频转码模板 ID。
     * @param RawTranscodeParameter $RawParameter 视频转码自定义参数，当 Definition 填 0 时有效。
该参数用于高度定制场景，建议您优先使用 Definition 指定转码参数。
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $WatermarkSet 水印列表，支持多张图片或文字水印，最大可支持 10 张。
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $MosaicSet 马赛克列表，最大可支持 10 张。
     * @param TaskOutputStorage $OutputStorage 转码后文件的目标存储，不填则继承上层的 OutputStorage 值。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $OutputObjectPath 转码后主文件的输出路径，可以为相对路径或者绝对路径。如果不填，则默认为相对路径：`{inputName}_transcode_{definition}.{format}`。
     * @param string $SegmentObjectName 转码后分片文件的输出路径（转码 HLS 时 ts 的路径），只能为相对路径。如果不填，则默认为：`{inputName}_transcode_{definition}_{number}.{format}`。
     * @param NumberFormat $ObjectNumberFormat 转码后输出路径中的`{number}`变量的规则。
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
        if (array_key_exists("Definition",$param) and $param["Definition"] !== null) {
            $this->Definition = $param["Definition"];
        }

        if (array_key_exists("RawParameter",$param) and $param["RawParameter"] !== null) {
            $this->RawParameter = new RawTranscodeParameter();
            $this->RawParameter->deserialize($param["RawParameter"]);
        }

        if (array_key_exists("WatermarkSet",$param) and $param["WatermarkSet"] !== null) {
            $this->WatermarkSet = [];
            foreach ($param["WatermarkSet"] as $key => $value){
                $obj = new WatermarkInput();
                $obj->deserialize($value);
                array_push($this->WatermarkSet, $obj);
            }
        }

        if (array_key_exists("MosaicSet",$param) and $param["MosaicSet"] !== null) {
            $this->MosaicSet = [];
            foreach ($param["MosaicSet"] as $key => $value){
                $obj = new MosaicInput();
                $obj->deserialize($value);
                array_push($this->MosaicSet, $obj);
            }
        }

        if (array_key_exists("OutputStorage",$param) and $param["OutputStorage"] !== null) {
            $this->OutputStorage = new TaskOutputStorage();
            $this->OutputStorage->deserialize($param["OutputStorage"]);
        }

        if (array_key_exists("OutputObjectPath",$param) and $param["OutputObjectPath"] !== null) {
            $this->OutputObjectPath = $param["OutputObjectPath"];
        }

        if (array_key_exists("SegmentObjectName",$param) and $param["SegmentObjectName"] !== null) {
            $this->SegmentObjectName = $param["SegmentObjectName"];
        }

        if (array_key_exists("ObjectNumberFormat",$param) and $param["ObjectNumberFormat"] !== null) {
            $this->ObjectNumberFormat = new NumberFormat();
            $this->ObjectNumberFormat->deserialize($param["ObjectNumberFormat"]);
        }
    }
}
