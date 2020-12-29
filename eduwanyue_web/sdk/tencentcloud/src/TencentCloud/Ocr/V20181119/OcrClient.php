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

namespace TencentCloud\Ocr\V20181119;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Ocr\V20181119\Models as Models;

/**
 * @method Models\ArithmeticOCRResponse ArithmeticOCR(Models\ArithmeticOCRRequest $req) 本接口支持作业算式题目的自动识别，目前覆盖 K12 学力范围内的 14 种题型，包括加减乘除四则运算、分数四则运算、竖式四则运算、脱式计算等。
 * @method Models\BankCardOCRResponse BankCardOCR(Models\BankCardOCRRequest $req) 本接口支持对中国大陆主流银行卡的卡号、银行信息、有效期等关键字段的检测与识别。
 * @method Models\BizLicenseOCRResponse BizLicenseOCR(Models\BizLicenseOCRRequest $req) 本接口支持快速精准识别营业执照上的字段，包括统一社会信用代码、公司名称、经营场所、主体类型、法定代表人、注册资金、组成形式、成立日期、营业期限和经营范围等字段。
 * @method Models\BusInvoiceOCRResponse BusInvoiceOCR(Models\BusInvoiceOCRRequest $req) 本接口支持识别公路汽车客票的发票代码、发票号码、日期、姓名、票价等字段。
 * @method Models\BusinessCardOCRResponse BusinessCardOCR(Models\BusinessCardOCRRequest $req) 本接口支持名片各字段的自动定位与识别，包含姓名、电话、手机号、邮箱、公司、部门、职位、网址、地址、QQ、微信、MSN等。
 * @method Models\CarInvoiceOCRResponse CarInvoiceOCR(Models\CarInvoiceOCRRequest $req) 本接口支持机动车销售统一发票和二手车销售统一发票的识别，包括发票号码、发票代码、合计金额、合计税额等二十多个字段。
 * @method Models\DriverLicenseOCRResponse DriverLicenseOCR(Models\DriverLicenseOCRRequest $req) 本接口支持驾驶证主页和副页所有字段的自动定位与识别，重点字段的识别准确度达到99%以上。

驾驶证主页：包括证号、姓名、性别、国籍、住址、出生日期、初次领证日期、准驾车型、有效期限。

驾驶证副页：包括证号、姓名、档案编号、记录。

另外，本接口还支持复印件、翻拍和PS告警功能。
 * @method Models\DutyPaidProofOCRResponse DutyPaidProofOCR(Models\DutyPaidProofOCRRequest $req) 本接口支持对完税证明的税号、纳税人识别号、纳税人名称、金额合计大写、金额合计小写、填发日期、税务机关、填票人等关键字段的识别。
 * @method Models\EduPaperOCRResponse EduPaperOCR(Models\EduPaperOCRRequest $req) 本接口支持数学试题内容的识别和结构化输出，包括通用文本解析和小学/初中/高中数学公式解析能力（包括91种题型，180种符号）。
 * @method Models\EnglishOCRResponse EnglishOCR(Models\EnglishOCRRequest $req) 本接口支持图像英文文字的检测和识别，返回文字框位置与文字内容。支持多场景、任意版面下的英文、字母、数字和常见字符的识别，同时覆盖英文印刷体和英文手写体识别。
 * @method Models\EnterpriseLicenseOCRResponse EnterpriseLicenseOCR(Models\EnterpriseLicenseOCRRequest $req) 本接口支持智能化识别各类企业登记证书、许可证书、企业执照、三证合一类证书，结构化输出统一社会信用代码、公司名称、法定代表人、公司地址、注册资金、企业类型、经营范围等关键字段。
 * @method Models\EstateCertOCRResponse EstateCertOCR(Models\EstateCertOCRRequest $req) 本接口支持不动产权证关键字段的识别，包括使用期限、面积、用途、权利性质、权利类型、坐落、共有情况、权利人、权利其他状况等。



 * @method Models\FinanBillOCRResponse FinanBillOCR(Models\FinanBillOCRRequest $req) 本接口支持常见银行票据的自动分类和识别。整单识别包括支票（含现金支票、普通支票、转账支票），承兑汇票（含银行承兑汇票、商业承兑汇票）以及进账单等，适用于中国人民银行印发的 2010 版银行票据凭证版式（银发[2010]299 号）。
 * @method Models\FinanBillSliceOCRResponse FinanBillSliceOCR(Models\FinanBillSliceOCRRequest $req) 本接口支持常见银行票据的自动分类和识别。切片识别包括金融行业常见票据的重要切片字段识别，包括金额、账号、日期、凭证号码等。（金融票据切片：金融票据中待识别字段及其周围局部区域的裁剪图像。）
 * @method Models\FlightInvoiceOCRResponse FlightInvoiceOCR(Models\FlightInvoiceOCRRequest $req) 本接口支持机票行程单关键字段的识别，包括姓名、身份证件号码、航班号、票价 、合计、电子客票号码、填开日期等。
 * @method Models\FormulaOCRResponse FormulaOCR(Models\FormulaOCRRequest $req) 本接口支持识别主流初高中数学符号和公式，返回公式的 Latex 格式文本。
 * @method Models\GeneralAccurateOCRResponse GeneralAccurateOCR(Models\GeneralAccurateOCRRequest $req) 本接口支持图像整体文字的检测和识别，返回文字框位置与文字内容。相比通用印刷体识别接口，高精度版在英文、数字、小字、模糊字、倾斜文本行等困难场景下，准确率和召回率更高。
 * @method Models\GeneralBasicOCRResponse GeneralBasicOCR(Models\GeneralBasicOCRRequest $req) 本接口支持多场景、任意版面下整图文字的识别。支持自动识别语言类型，同时支持自选语言种类（推荐），除中英文外，支持日语、韩语、西班牙语、法语、德语、葡萄牙语、越南语、马来语、俄语、意大利语、荷兰语、瑞典语、芬兰语、丹麦语、挪威语、匈牙利语、泰语等多种语言。应用场景包括：印刷文档识别、网络图片识别、广告图文字识别、街景店招识别、菜单识别、视频标题识别、头像文字识别等。
 * @method Models\GeneralEfficientOCRResponse GeneralEfficientOCR(Models\GeneralEfficientOCRRequest $req) 本接口支持多场景、任意版面下整图文字的识别。相较于“通用印刷体识别”接口，精简版接口在准召率有一定损失的情况下，耗时更短。适用于对接口耗时较为敏感的客户。
 * @method Models\GeneralFastOCRResponse GeneralFastOCR(Models\GeneralFastOCRRequest $req) 本接口支持图片中整体文字的检测和识别，返回文字框位置与文字内容。相比通用印刷体识别接口，识别速度更快、支持的 QPS 更高。
 * @method Models\GeneralHandwritingOCRResponse GeneralHandwritingOCR(Models\GeneralHandwritingOCRRequest $req) 本接口支持图片内手写体文字的检测和识别，针对手写字体无规则、字迹潦草、模糊等特点进行了识别能力的增强。
 * @method Models\HmtResidentPermitOCRResponse HmtResidentPermitOCR(Models\HmtResidentPermitOCRRequest $req) 港澳台居住证OCR支持港澳台居住证正反面全字段内容检测识别功能，包括姓名、性别、出生日期、地址、身份证ID、签发机关、有效期限、签发次数、通行证号码关键字段识别。可以应用于港澳台居住证信息有效性校验场景，例如银行开户、用户注册等场景。
 * @method Models\IDCardOCRResponse IDCardOCR(Models\IDCardOCRRequest $req) 本接口支持中国大陆居民二代身份证正反面所有字段的识别，包括姓名、性别、民族、出生日期、住址、公民身份证号、签发机关、有效期限，识别准确度达到99%以上。

另外，本接口还支持多种增值能力，满足不同场景的需求。如身份证照片、人像照片的裁剪功能，同时具备9种告警功能，如下表所示。

<table style="width:650px">
      <thead>
        <tr>
       <th width="150">增值能力</th>
          <th width="500">能力项</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td rowspan="2">裁剪功能</td>
          <td>身份证照片裁剪（去掉证件外多余的边缘、自动矫正拍摄角度）</td>
        </tr>
        <tr>
          <td>人像照片裁剪（自动抠取身份证头像区域）</td>
        </tr>
        <tr>
          <td rowspan="9">告警功能</td>
          <td>身份证有效日期不合法告警</td>
        </tr>
        <tr>
          <td>身份证边框不完整告警</td>
        </tr>
        <tr>
          <td>身份证复印件告警</td>
        </tr>
        <tr>
          <td>身份证翻拍告警</td>
        </tr>
          <tr>
          <td>身份证框内遮挡告警</td>
        </tr>
         <tr>
          <td>临时身份证告警</td>
        </tr>
          <tr>
          <td>身份证 PS 告警</td>
        </tr>
          <tr>
          <td>图片模糊告警</td>
        </tr>
      </tbody>
    </table>
 * @method Models\InstitutionOCRResponse InstitutionOCR(Models\InstitutionOCRRequest $req) 本接口支持事业单位法人证书关键字段识别，包括注册号、有效期、住所、名称、法定代表人等。
 * @method Models\InsuranceBillOCRResponse InsuranceBillOCR(Models\InsuranceBillOCRRequest $req) 本接口支持病案首页、费用清单、结算单、医疗发票四种保险理赔单据的文本识别和结构化输出。
 * @method Models\InvoiceGeneralOCRResponse InvoiceGeneralOCR(Models\InvoiceGeneralOCRRequest $req) 本接口支持对通用机打发票的发票代码、发票号码、日期、购买方识别号、销售方识别号、校验码、小写金额等关键字段的识别。
 * @method Models\LicensePlateOCRResponse LicensePlateOCR(Models\LicensePlateOCRRequest $req) 本接口支持对中国大陆机动车车牌的自动定位和识别，返回地域编号和车牌号信息。
 * @method Models\MLIDCardOCRResponse MLIDCardOCR(Models\MLIDCardOCRRequest $req) 本接口支持马来西亚身份证识别，识别字段包括身份证号、姓名、性别、地址；具备身份证人像照片的裁剪功能和翻拍、复印件告警功能。
本接口暂未完全对外开放，如需咨询，请[联系商务](https://cloud.tencent.com/about/connect)

 * @method Models\MLIDPassportOCRResponse MLIDPassportOCR(Models\MLIDPassportOCRRequest $req) 本接口支持中国港澳台地区以及其他国家、地区的护照。识别字段包括护照ID、姓名、出生日期、性别、有效期、发行国、国籍；具备护照人像照片的裁剪功能和翻拍、复印件告警功能。
本接口暂未完全对外开放，如需咨询，请[联系商务](https://cloud.tencent.com/about/connect)
 * @method Models\MainlandPermitOCRResponse MainlandPermitOCR(Models\MainlandPermitOCRRequest $req) 智能识别并结构化港澳台居民来往内地通行证正面全部字段，包含中文姓名、英文姓名、性别、出生日期、签发机关、有效期限、证件号、签发地点、签发次数、证件类别。
 * @method Models\MixedInvoiceDetectResponse MixedInvoiceDetect(Models\MixedInvoiceDetectRequest $req) 本接口支持多张、多类型票据的混合检测和自动分类，返回对应票据类型。目前已支持增值税发票、增值税发票（卷票）、定额发票、通用机打发票、购车发票、火车票、出租车发票、机票行程单、汽车票、轮船票、过路过桥费发票、酒店账单、客运限额发票、购物小票、完税证明共15种票据。
 * @method Models\MixedInvoiceOCRResponse MixedInvoiceOCR(Models\MixedInvoiceOCRRequest $req) 本接口支持多张、多类型票据的混合识别，系统自动实现分割、分类和识别，同时支持自选需要识别的票据类型。目前已支持增值税发票、增值税发票（卷票）、定额发票、通用机打发票、购车发票、火车票、出租车发票、机票行程单、汽车票、轮船票、过路过桥费发票共11种票据。
 * @method Models\OrgCodeCertOCRResponse OrgCodeCertOCR(Models\OrgCodeCertOCRRequest $req) 本接口支持组织机构代码证关键字段的识别，包括代码、有效期、地址、机构名称等。
 * @method Models\PassportOCRResponse PassportOCR(Models\PassportOCRRequest $req) 本接口支持中国大陆地区护照个人资料页多个字段的检测与识别。已支持字段包括英文姓名、中文姓名、国家码、护照号、出生地、出生日期、国籍英文、性别英文、有效期、签发地点英文、签发日期、持证人签名、护照机读码（MRZ码）等。
 * @method Models\PermitOCRResponse PermitOCR(Models\PermitOCRRequest $req) 本接口支持对卡式港澳台通行证的识别，包括签发地点、签发机关、有效期限、性别、出生日期、英文姓名、姓名、证件号等字段。
 * @method Models\PropOwnerCertOCRResponse PropOwnerCertOCR(Models\PropOwnerCertOCRRequest $req) 本接口支持房产证关键字段的识别，包括房地产权利人、共有情况、登记时间、规划用途、房屋性质、房屋坐落等。
 * @method Models\QrcodeOCRResponse QrcodeOCR(Models\QrcodeOCRRequest $req) 本接口支持条形码和二维码的识别（包括 DataMatrix 和 PDF417）。
本接口暂未完全对外开放，如需咨询，请[联系商务](https://cloud.tencent.com/about/connect) 
 * @method Models\QuotaInvoiceOCRResponse QuotaInvoiceOCR(Models\QuotaInvoiceOCRRequest $req) 本接口支持定额发票的发票号码、发票代码、金额(大小写)、发票消费类型、地区及是否有公司印章等关键字段的识别。
 * @method Models\ResidenceBookletOCRResponse ResidenceBookletOCR(Models\ResidenceBookletOCRRequest $req) 本接口支持居民户口簿户主页及成员页关键字段的识别，包括姓名、户别、地址、籍贯、身份证号码等。
 * @method Models\ShipInvoiceOCRResponse ShipInvoiceOCR(Models\ShipInvoiceOCRRequest $req) 本接口支持识别轮船票的发票代码、发票号码、日期、姓名、票价等字段。
 * @method Models\TableOCRResponse TableOCR(Models\TableOCRRequest $req) 本接口支持图片内表格文档的检测和识别，返回每个单元格的文字内容，支持将识别结果保存为 Excel 格式。
 * @method Models\TaxiInvoiceOCRResponse TaxiInvoiceOCR(Models\TaxiInvoiceOCRRequest $req) 本接口支持出租车发票关键字段的识别，包括发票号码、发票代码、金额、日期、上下车时间、里程、车牌号、发票类型及所属地区等字段。
 * @method Models\TextDetectResponse TextDetect(Models\TextDetectRequest $req) 本接口通过检测图片中的文字信息特征，快速判断图片中有无文字并返回判断结果，帮助用户过滤无文字的图片。
 * @method Models\TollInvoiceOCRResponse TollInvoiceOCR(Models\TollInvoiceOCRRequest $req) 本接口支持对过路过桥费发票的发票代码、发票号码、日期、小写金额等关键字段的识别。
 * @method Models\TrainTicketOCRResponse TrainTicketOCR(Models\TrainTicketOCRRequest $req) 本接口支持火车票全字段的识别，包括编号、票价、姓名、座位号、出发时间、出发站、到达站、车次、席别、发票类型及序列号等。

 * @method Models\VatInvoiceOCRResponse VatInvoiceOCR(Models\VatInvoiceOCRRequest $req) 本接口支持增值税专用发票、增值税普通发票、增值税电子发票全字段的内容检测和识别，包括发票代码、发票号码、开票日期、合计金额、校验码、税率、合计税额、价税合计、购买方识别号、复核、销售方识别号、开票人、密码区1、密码区2、密码区3、密码区4、发票名称、购买方名称、销售方名称、服务名称、备注、规格型号、数量、单价、金额、税额、收款人等字段。
 * @method Models\VatRollInvoiceOCRResponse VatRollInvoiceOCR(Models\VatRollInvoiceOCRRequest $req) 本接口支持对增值税发票（卷票）的发票代码、发票号码、日期、校验码、合计金额（小写）等关键字段的识别。
 * @method Models\VehicleLicenseOCRResponse VehicleLicenseOCR(Models\VehicleLicenseOCRRequest $req) 本接口支持行驶证主页和副页所有字段的自动定位与识别。

行驶证主页：车牌号码、车辆类型、所有人、住址、使用性质、品牌型号、识别代码、发动机号、注册日期、发证日期、发证单位。

行驶证副页：号牌号码、档案编号、核定载人数、总质量、整备质量、核定载质量、外廓尺寸、准牵引总质量、备注、检验记录。

另外，本接口还支持复印件、翻拍和PS告警功能。
 * @method Models\VehicleRegCertOCRResponse VehicleRegCertOCR(Models\VehicleRegCertOCRRequest $req) 本接口支持国内机动车登记证书主要字段的结构化识别，包括机动车所有人、身份证明名称、号码、车辆型号、车辆识别代号、发动机号、制造厂名称等。
 * @method Models\VinOCRResponse VinOCR(Models\VinOCRRequest $req) 本接口支持图片内车辆识别代号（VIN）的检测和识别。
 * @method Models\WaybillOCRResponse WaybillOCR(Models\WaybillOCRRequest $req) 本接口支持市面上主流版式电子运单的识别，包括收件人和寄件人的姓名、电话、地址以及运单号等字段。
 */

class OcrClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "ocr.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $version = "2018-11-19";

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
        $respClass = "TencentCloud"."\\".ucfirst("ocr")."\\"."V20181119\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
