<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 14:38
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: DHLExpress.php
 */


namespace Txtech\Express;

use Psr\Log\LogLevel;
use Txtech\Express\Core\Arrayable;
use Txtech\Express\Core\Log\Logger;
use Txtech\Express\Posts\DHL\Common\Dimensions;
use Txtech\Express\Posts\DHL\Common\Package;
use Txtech\Express\Posts\DHL\Common\Packages;
use Txtech\Express\Posts\DHL\Common\Recipient;
use Txtech\Express\Posts\DHL\Common\Ship;
use Txtech\Express\Posts\DHL\Common\Shipper;
use Txtech\Express\Posts\DHL\DHLRequest;
use Txtech\Express\Posts\DHL\Shipment;
use Txtech\Express\Posts\DHL\Shipment\ExportLineItem;
use Txtech\Express\Posts\DHL\Shipment\InternationalDetail;
use Txtech\Express\Posts\DHL\Shipment\ShipmentInfo;
use Txtech\Express\Posts\InvalidPostInfoException;
use Txtech\Express\Posts\Post;
use Txtech\Express\Posts\PostApiException;

/**
 * Class DHLExpress
 * @package Txtech\Express
 */
class DHLExpress extends Post
{
    /** @var string */
    protected string $accountCode;

    /** @var array */
    protected array $apiInfo;

    /** @var string */
    protected string $pdfContent;

    /** @var string 运单号 */
    protected string $shipmentIdentificationNumber;

    /** @var string 取件号 */
    protected string $dispatchConfirmationNumber;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->accountCode = $options['accountCode'];

        $this->apiInfo = [
            'url' => $options['url'],
            'username' => $options['username'],
            'password' => $options['password']
        ];

        parent::__constrcut();
    }

    /**
     * @param array $data
     * @return array|void
     * @throws PostApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateLabel(array $data)
    {
        Logger::printScreen(LogLevel::INFO, 'DHL面单对接原始数据', $data);

        $data['package_info'] = json_decode($data['package_info'], true);

        $this->setPackageId($data['parcel_id']);
        $this->setCreated(date('Y-m-d', strtotime($data['created_at'])));

        $shipmentInfo = (new ShipmentInfo())->setAccount($this->accountCode);
        $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_OTHER);

        $arr = [1 => 'Documents', 2 => 'Goods', 3 => 'Gift', 4 => 'Clothes', 5 => 'Cosmeceuticals', 6 => 'Red wine', 7 => 'Food', 100 => 'Goods'];
        $documentContent = $arr[$data['package_content']];

        $details = (new InternationalDetail())
            ->setDescription($documentContent)
            ->setCustomsValue($data['total_value']);

        //如果设置了保险，加上保险服务
        if ($data['is_enabled_insurance']) {
            //文件类型保险
            if ($documentContent == 'Documents') {
                $shipmentInfo->withDocumentInsurance()->setInsuranceValue($data['insurance_cost']);
            } else { //非文件类型
                $shipmentInfo->withInsurance()->setInsuranceValue($data['insurance_cost']);
            }
        }

        if ($documentContent == 'Documents') {
            $details->setContentDocuments();
            //如果寄往欧盟国家，更改服务类型为欧盟
            // if ($this->isEUCountry($data['country_code'])) {
            //     $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_EU);
            //     //如果是国际件
            // } elseif ($this->isInternationalRecipientCountry($data['country_code'])) {
            //     $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_OTHER_DOC);
            // }
            //文件 世界的都有 D
            $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_OTHER_DOC);
        }

        //如果欧洲境内 U
        if ($this->isEuropeanCountry($data['receiver_country_code'])) {
            $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_EU);
        }

        // 奥地利 法国 英国 荷兰 希腊 比利时 德国 西班牙
        // 改成U
        // CONTENTS 用DOCUMENTS
        // 挪威  瑞士 用 P
        //英国  比利时 卢森堡，奥地利，葡萄牙，希腊，爱尔兰，克罗地亚，匈牙利，瑞典，波兰，芬兰，捷克，罗马尼亚，斯洛伐克，丹麦
        //，斯罗维尼亚，都是欧洲国家 CONTENT 用DOCUMENTS
        // 只是服务用  U ，之前用的是 W

        //如果是欧盟国家
        if ($this->isEUCountry($data['receiver_country_code'])) {
            $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_EU);
            //如果是奥地利 法国 英国 荷兰 希腊 比利时 德国 西班牙 W
            //Update: 2020/12/07 奥地利 法国 英国 荷兰 希腊 比利时 德国 西班牙改成U CONTENTS 用DOCUMENTS
            //if ($this->isServerTypeW($data['country_code'])) {
            //    $shipmentInfo->setServiceType('W');
            //}
            //英国  比利时 卢森堡，奥地利，葡萄牙，希腊，爱尔兰，克罗地亚，匈牙利，瑞典，波兰，芬兰，捷克，罗马尼亚，斯洛伐克，丹麦
            //，斯罗维尼亚，都是欧洲国家 CONTENT 用DOCUMENTS
            $details->setContentDocuments();
        }

        //如果是挪威 瑞士 P
        if ($this->isServerTypeP($data['receiver_country_code'])) {
            $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_OTHER);
        }

        //如果寄往地区是意大利，更改服务类型为 N
        if ($data['receiver_country_code'] == 'IT') {
            $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_IT);
        }

        $recipient = $this->setRecipient($data);
        $shipper = $this->setShipper($data);

        $packages = new Packages();

        if ($data['package_info']) {
            foreach ($data['package_info'] as $key => $value) {

                $dimension = new Dimensions();

                $dimension->setHeight($value['height_size']) //use mm
                ->setLength($value['length_size'])
                    ->setWidth($value['width_size']);

                $package = $this->setPackage($dimension, $value, $data["vip_id"], $data['file_declaration'] == "" ? $documentContent : $data['file_declaration']);

                $packages->add($package);
            }
        }

        $ship = new Ship();

        $customs_value = empty($data['total_value']) ? 0 : $data['total_value'];
        //如果是发国际件,非欧洲外
        if (!$this->isEUCountry($recipient->getCountryCode())) {
            $exportLineItems = [];

            //如果发的是文件
            if ($data['package_content'] == 1) {

                $exportItem = new ExportLineItem();

                $exportItem->setItemNumber(1)
                    ->setUnitPrice($customs_value)
                    ->setItemDescription($data['file_declaration'])
                    ->setQuantityUnitOfMeasurement("KG") //$item['unit']
                    ->setGrossWeight(number_format(ceil($data['total_weight_with_box'] / 1000), 2))
                    ->setNetWeight(number_format(ceil($data['total_weight_with_box'] / 1000), 2))
                    ->setQuantity(1);

                $exportLineItems[] = $exportItem->toArray();

            } else {
                foreach ($data['items'] as $item) {
                    $exportItem = new ExportLineItem();

                    $exportItem->setItemNumber($item['order_item_id'])
                        ->setUnitPrice($item['unit_price'])
                        ->setItemDescription($item['item_en_name'] ?? $item['description'])
                        ->setQuantityUnitOfMeasurement("KG") //$item['unit']
                        ->setGrossWeight(number_format(ceil($item['total_weight'] / 1000), 2))
                        ->setNetWeight(number_format(ceil($item['net_content'] / 1000), 2))
                        ->setQuantity($item['quantity']);

                    $exportLineItems[] = $exportItem->toArray();
                }
            }

            //发票类型 == 2 就是系统生成
            $invoice_type = empty($data['invoice_type']) ? 0 : $data['invoice_type'];

            if ($invoice_type == 2) {
                $shipmentInfo = $shipmentInfo->withCustomsInvoice();
                $details = $details->withInvoice($exportLineItems)
                    ->setInvoiceDate($this->getYMDCreated())
                    ->setInvoiceNumber($data['package_number'] ?? '');
            }
        }

        $shipment = (new Shipment())
            ->setShipmentInfo($shipmentInfo)
            ->setInternationalDetail($details)
            ->setPackages($packages)
            ->setShipTimestamp(gmdate('Y-m-d\TH:i:s \G\M\TP', strtotime("+1 day"))) //这个地方一定要是未来的时间
            ->setShip($ship->setShipper($shipper)->setRecipient($recipient))
            ->toArray();

        $this->toArray($shipment);

        try {
            $data = (new DHLRequest($this->apiInfo))->shipmentRequest($shipment);

            $response = $data['ShipmentResponse'];

            if ($response['Notification'][0]['@code'] != 0) {
                throw new InvalidPostInfoException((json_encode($response)));
            }

            $pdfContent = $response['LabelImage'][0]['GraphicImage'];

            //获取国际单号
            $this->setGlobalBarcode($response['PackagesResult']['PackageResult'][0]['TrackingNumber']);
            //DHL运单身份证号码，用于后续修改运单
            $this->setShipmentIdentificationNumber($response['ShipmentIdentificationNumber']);

            $this->setPartnerBarcode($this->getGlobalBarcode());
            $this->setPartnerCompany("DHL");
            //返回面单PDF
            if (isset($response['LabelImage'])) {
                $this->setPdfContent($pdfContent);
            }

            $partnerBarcodes = $globalBarcodes = $globalPdfs = [];

            if (is_array($response['PackagesResult']['PackageResult'])) {
                foreach ($response['PackagesResult']['PackageResult'] as $key => $value) {
                    //获取国际单号
                    $this->setGlobalBarcode($value["TrackingNumber"]);
                    $this->setPartnerBarcode($this->getGlobalBarcode());

                    $partnerBarcodes[] = $this->getGlobalBarcode();
                    $globalBarcodes[] = $this->getGlobalBarcode();
                    $globalPdfs[] = $this->getPdfContent();

                    $this->setPartnerCompany("DHL");
                }

                return $this->updateManyNumbers(
                    implode(",", $globalBarcodes),
                    implode(",", $partnerBarcodes),
                    implode(",", $globalPdfs));
            }
        } catch (PostApiException|InvalidPostInfoException $ex) {
            Logger::printScreen(LogLevel::ERROR, 'DHL面单对接失败', $ex->getMessage());
            throw new PostApiException($ex->getMessage());
        }
    }

    /**
     * 更新多个单号，用于一票多单
     * DHL就是这样
     * @param $globalNumber
     * @param $partnerNumber
     * @param $expressPdf
     * @return array
     */
    public function updateManyNumbers($globalNumber, $partnerNumber, $expressPdf)
    {
        return [
            'express_global_number' => $globalNumber,
            'express_china_number' => $partnerNumber,
            'express_pdf' => $expressPdf,
            'package_id' => $this->getPackageId(),
            'express_code' => $this->getTypeCode(),
        ];
    }

    /**
     * @return string
     */
    public function getTypeCode()
    {
        return 'DHL';
    }

    /**
     * @return string
     */
    public function getPdfContent(): string
    {
        return $this->pdfContent;
    }

    /**
     * @param string $pdfContent
     * @return DHLExpress
     */
    public function setPdfContent(string $pdfContent): DHLExpress
    {
        $this->pdfContent = $pdfContent;

        return $this;
    }

    /**
     * @param string $shipmentIdentificationNumber
     * @return DHLExpress
     */
    public function setShipmentIdentificationNumber(string $shipmentIdentificationNumber): DHLExpress
    {
        $this->shipmentIdentificationNumber = $shipmentIdentificationNumber;

        return $this;
    }


    /**
     * 设置包裹参数
     *
     * @param Dimensions $dimension
     * @param $data
     * @param $reference
     * @param string $description
     * @return Package
     */
    protected function setPackage(Dimensions $dimension, $data, $reference, $description = ''): Package
    {
        $package = new Package();

        $package->setNumber($data["qty"])
            ->setDimensions($dimension)
            ->setWeight((int)$data["weight"])  //use g
            ->setPackageContentDescription($description == "" ? 'Goods' : $description)
            ->setCustomerReferences($reference);

        return $package;
    }


    /**
     * 设置发件人信息
     *
     * @param $data
     * @param bool $withRegistrationNumber
     * @return Shipper
     */
    protected function setShipper($data, bool $withRegistrationNumber = true): Shipper
    {
        $shipper = new Shipper();

        $shipper->setName($data['sender_fullname'])
            ->setCompanyName(strlen($data['sender_company_name']) == 0 ? $data['sender_fullname'] : $data['sender_company_name'])
            ->setCountryCode($data['sender_country_code'])
            ->setCity($data['sender_city'])
            ->setEmail($data['sender_email'])
            ->setStreet($data['sender_address1'])
            ->setPostCode($data['sender_postcode'])
            ->setPhoneNumber($data['sender_tel_number']);

        if ($withRegistrationNumber) {
            $shipper->setRegistrationNumber($data['package_number'] ?? 'NUM');
        }

        return $shipper;
    }

    /**
     * 设置收件人信息
     *
     * @param $data
     * @return Recipient
     */
    protected function setRecipient($data): Recipient
    {
        $recipient = new Recipient();

        $addresseeAddress = $this->pinyin($data['receiver_address1']);

        $recipient->setName($this->pinyin($data['receiver_fullname']))
            ->setCompanyName($this->pinyin($data['receiver_company_name']))
            ->setPhoneNumber($data['receiver_tel_number'])
            ->setCity($this->pinyin($data['receiver_city']))
            ->setPostCode($data['receiver_postcode'])
            ->setCountryCode($data['receiver_country_code']);

        //因为这个地址长度的原因,最多只能45个长度
        if (strlen($addresseeAddress) <= 45) {
            $recipient->setStreet($addresseeAddress);
        } else {

            $addressee_address_1 = character_limiter($addresseeAddress, 45, '');

            $recipient->setStreet($addressee_address_1);

            $addressee_address_2 = substr($addresseeAddress, strlen($addressee_address_1), strlen($addresseeAddress));

            if (strlen($addressee_address_2) <= 45) {

                $recipient->setStreet2($addressee_address_2);

            } else {

                $orgin_addressee_address_2 = trim(substr($addresseeAddress, strlen($addressee_address_1), 45));
                //43是为了防止拼音截断
                $addressee_address_2 = character_limiter($addressee_address_2, 43, '');
                $recipient->setStreet2($addressee_address_2);

                $addressee_address_3 = substr($addresseeAddress, strlen($addressee_address_1) + strlen($orgin_addressee_address_2), strlen($addresseeAddress));
                $recipient->setStreet3($addressee_address_3);

            }
        }

        return $recipient;
    }

    /**
     * @param string $code
     * @return bool
     */
    protected function isServerTypeP(string $code)
    {
        $code = $this->convertCountryCode($code);
        //挪威  P
        //瑞士  P
        return in_array($code, [
            'NO', 'CH',
        ]);
    }

    /**
     * @param string $code
     * @return bool
     */
    protected function isEUCountry(string $code)
    {
        $code = $this->convertCountryCode($code);

        return in_array($code, [
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU',
            'MT', 'NL', 'PO', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE',
        ]);
    }

    /**
     * @param string $code
     * @return bool
     */
    protected function isEuropeanCountry(string $code)
    {
        $code = $this->convertCountryCode($code);

        return in_array($code, [
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU',
            'MT', 'NL', 'PO', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'AL', 'AD', 'AM', 'BY', 'BA', 'FO', 'GE', 'GI', 'IS',
            'IM', 'XK', 'LI', 'MK', 'MD', 'MC', 'MN', 'NO', 'RU', 'SM', 'RS', 'CH', 'TR', 'UA', 'GB', 'VA'
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function toArray(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->toArray($value);
            } elseif ($value instanceof Arrayable) {
                $value = $value->toArray();
            }
        }
    }

}