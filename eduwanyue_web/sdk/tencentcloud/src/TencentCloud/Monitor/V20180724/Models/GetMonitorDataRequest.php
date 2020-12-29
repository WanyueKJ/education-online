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
namespace TencentCloud\Monitor\V20180724\Models;
use TencentCloud\Common\AbstractModel;

/**
 * GetMonitorData请求参数结构体
 *
 * @method string getNamespace() 获取命名空间，每个云产品会有一个命名空间
 * @method void setNamespace(string $Namespace) 设置命名空间，每个云产品会有一个命名空间
 * @method string getMetricName() 获取指标名称，各个云产品的详细指标说明请参阅各个产品[监控接口](https://cloud.tencent.com/document/product/248/30384)文档
 * @method void setMetricName(string $MetricName) 设置指标名称，各个云产品的详细指标说明请参阅各个产品[监控接口](https://cloud.tencent.com/document/product/248/30384)文档
 * @method array getInstances() 获取实例对象的维度组合
 * @method void setInstances(array $Instances) 设置实例对象的维度组合
 * @method integer getPeriod() 获取监控统计周期。默认为取值为300，单位为s
 * @method void setPeriod(integer $Period) 设置监控统计周期。默认为取值为300，单位为s
 * @method string getStartTime() 获取起始时间，如2018-09-22T19:51:23+08:00
 * @method void setStartTime(string $StartTime) 设置起始时间，如2018-09-22T19:51:23+08:00
 * @method string getEndTime() 获取结束时间，默认为当前时间。 EndTime不能小于StartTime
 * @method void setEndTime(string $EndTime) 设置结束时间，默认为当前时间。 EndTime不能小于StartTime
 */
class GetMonitorDataRequest extends AbstractModel
{
    /**
     * @var string 命名空间，每个云产品会有一个命名空间
     */
    public $Namespace;

    /**
     * @var string 指标名称，各个云产品的详细指标说明请参阅各个产品[监控接口](https://cloud.tencent.com/document/product/248/30384)文档
     */
    public $MetricName;

    /**
     * @var array 实例对象的维度组合
     */
    public $Instances;

    /**
     * @var integer 监控统计周期。默认为取值为300，单位为s
     */
    public $Period;

    /**
     * @var string 起始时间，如2018-09-22T19:51:23+08:00
     */
    public $StartTime;

    /**
     * @var string 结束时间，默认为当前时间。 EndTime不能小于StartTime
     */
    public $EndTime;

    /**
     * @param string $Namespace 命名空间，每个云产品会有一个命名空间
     * @param string $MetricName 指标名称，各个云产品的详细指标说明请参阅各个产品[监控接口](https://cloud.tencent.com/document/product/248/30384)文档
     * @param array $Instances 实例对象的维度组合
     * @param integer $Period 监控统计周期。默认为取值为300，单位为s
     * @param string $StartTime 起始时间，如2018-09-22T19:51:23+08:00
     * @param string $EndTime 结束时间，默认为当前时间。 EndTime不能小于StartTime
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
        if (array_key_exists("Namespace",$param) and $param["Namespace"] !== null) {
            $this->Namespace = $param["Namespace"];
        }

        if (array_key_exists("MetricName",$param) and $param["MetricName"] !== null) {
            $this->MetricName = $param["MetricName"];
        }

        if (array_key_exists("Instances",$param) and $param["Instances"] !== null) {
            $this->Instances = [];
            foreach ($param["Instances"] as $key => $value){
                $obj = new Instance();
                $obj->deserialize($value);
                array_push($this->Instances, $obj);
            }
        }

        if (array_key_exists("Period",$param) and $param["Period"] !== null) {
            $this->Period = $param["Period"];
        }

        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }
    }
}
