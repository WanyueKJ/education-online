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
namespace TencentCloud\Taf\V20200210\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 业务出参
 *
 * @method string getUid() 获取用户 ID accountType 不同对应不同的用户 ID。如是 QQ 或微信用户则填入对应的 openId
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setUid(string $Uid) 设置用户 ID accountType 不同对应不同的用户 ID。如是 QQ 或微信用户则填入对应的 openId
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getPostTime() 获取操作时间戳，单位：秒
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setPostTime(integer $PostTime) 设置操作时间戳，单位：秒
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getUserIp() 获取用户操作的真实外网 IP
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setUserIp(string $UserIp) 设置用户操作的真实外网 IP
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getLevel() 获取0：表示不活跃
1 - 4：活跃等级由低到高
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setLevel(integer $Level) 设置0：表示不活跃
1 - 4：活跃等级由低到高
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getType() 获取账号标签：
3，无效账号，送检账号参数无法成功解析，请检查微信openid是否有误 ，QQopenid是否与appidU对应，手机号是否有误。
4，黑名单，该账号在业务侧有过拉黑记录
5，白名单，该账号在业务侧有过加白名单记录

环境标签：
205，非公网有效ip，传进来的IP地址为内网ip地址或者ip保留地址；
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setType(array $Type) 设置账号标签：
3，无效账号，送检账号参数无法成功解析，请检查微信openid是否有误 ，QQopenid是否与appidU对应，手机号是否有误。
4，黑名单，该账号在业务侧有过拉黑记录
5，白名单，该账号在业务侧有过加白名单记录

环境标签：
205，非公网有效ip，传进来的IP地址为内网ip地址或者ip保留地址；
注意：此字段可能返回 null，表示取不到有效值。
 */
class OutputDetectAccountActivityValue extends AbstractModel
{
    /**
     * @var string 用户 ID accountType 不同对应不同的用户 ID。如是 QQ 或微信用户则填入对应的 openId
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Uid;

    /**
     * @var integer 操作时间戳，单位：秒
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $PostTime;

    /**
     * @var string 用户操作的真实外网 IP
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $UserIp;

    /**
     * @var integer 0：表示不活跃
1 - 4：活跃等级由低到高
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Level;

    /**
     * @var array 账号标签：
3，无效账号，送检账号参数无法成功解析，请检查微信openid是否有误 ，QQopenid是否与appidU对应，手机号是否有误。
4，黑名单，该账号在业务侧有过拉黑记录
5，白名单，该账号在业务侧有过加白名单记录

环境标签：
205，非公网有效ip，传进来的IP地址为内网ip地址或者ip保留地址；
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Type;

    /**
     * @param string $Uid 用户 ID accountType 不同对应不同的用户 ID。如是 QQ 或微信用户则填入对应的 openId
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $PostTime 操作时间戳，单位：秒
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $UserIp 用户操作的真实外网 IP
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $Level 0：表示不活跃
1 - 4：活跃等级由低到高
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $Type 账号标签：
3，无效账号，送检账号参数无法成功解析，请检查微信openid是否有误 ，QQopenid是否与appidU对应，手机号是否有误。
4，黑名单，该账号在业务侧有过拉黑记录
5，白名单，该账号在业务侧有过加白名单记录

环境标签：
205，非公网有效ip，传进来的IP地址为内网ip地址或者ip保留地址；
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
        if (array_key_exists("Uid",$param) and $param["Uid"] !== null) {
            $this->Uid = $param["Uid"];
        }

        if (array_key_exists("PostTime",$param) and $param["PostTime"] !== null) {
            $this->PostTime = $param["PostTime"];
        }

        if (array_key_exists("UserIp",$param) and $param["UserIp"] !== null) {
            $this->UserIp = $param["UserIp"];
        }

        if (array_key_exists("Level",$param) and $param["Level"] !== null) {
            $this->Level = $param["Level"];
        }

        if (array_key_exists("Type",$param) and $param["Type"] !== null) {
            $this->Type = $param["Type"];
        }
    }
}
