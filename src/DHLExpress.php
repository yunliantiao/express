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

        $this->setPackageId($data['parcel_id']);
        $this->setCreated(date('Y-m-d', strtotime($data['created_at'])));

        $shipmentInfo = (new ShipmentInfo())->setAccount($this->accountCode);
        $shipmentInfo->setServiceType(ShipmentInfo::SERVICE_TYPE_OTHER);

        $cargoType = $data['cargo_type'];
        $serverType = $data['server_type'];

        $details = (new InternationalDetail())
            ->setDescription($cargoType)
            ->setCustomsValue($data['total_value']);

        //如果设置了保险，加上保险服务
        if ($data['is_enabled_insurance']) {
            //文件类型保险
            if ($cargoType == 'Documents') {
                $shipmentInfo->withDocumentInsurance()->setInsuranceValue($data['insurance_cost']);
            } else { //非文件类型
                $shipmentInfo->withInsurance()->setInsuranceValue($data['insurance_cost']);
            }
        }

        $shipmentInfo->setServiceType($serverType);

        $shipper = $this->setShipper($data);
        $recipient = $this->setRecipient($data);

        $packages = new Packages();

        if ($data['boxes']) {
            foreach ($data['boxes'] as $box) {
                $dimension = new Dimensions();
                $dimension->setHeight($box['height'])->setLength($box['length'])->setWidth($box['width']);

                $package = $this->setPackage($dimension, $box, $data['vip_number'], $box['description'] == '' ? $cargoType : $box['description']);
                $packages->add($package);
            }
        }

        $ship = new Ship();

        $exportLineItems = [];

        if ($cargoType == 'Documents') {
            $exportItem = new Shipment\ExportLineItem();
            $exportItem->setItemNumber(1)
                ->setUnitPrice($data['total_price'])
                ->setItemDescription($cargoType)
                ->setQuantityUnitOfMeasurement('weight_unit')
                ->setGrossWeight(number_format(ceil($data['total_value']), 2))
                ->setNetWeight(number_format(ceil($data['total_value']), 2))
                ->setQuantity(1);

            $exportLineItems[] = $exportItem->toArray();
            $details->setContentDocuments();
        } else {
            foreach ($data['declares'] as $declare) {
                $exportItem = new ExportLineItem();
                $exportItem->setItemNumber($declare['number'] ?? $declare['id'])
                    ->setUnitPrice($declare['price'])
                    ->setItemDescription($declare['description'])
                    ->setQuantityUnitOfMeasurement('KG')
                    ->setGrossWeight(number_format(ceil($declare['total_weight']), 2))
                    ->setNetWeight(number_format(ceil($declare['total_weight']), 2))
                    ->setQuantity($declare['quantity']);

                $exportLineItems[] = $exportItem->toArray();
            }
            $details->setContentNonDocuments();
        }

        //发票类型 == 2 就是系统生成
        $invoiceType = empty($data['invoice_type']) ? 0 : $data['invoice_type'];
        if ($invoiceType == 2) {
            $shipmentInfo = $shipmentInfo->withCustomsInvoice();
            $details = $details->withInvoice($exportLineItems)
                ->setInvoiceDate($this->getYMDCreated())
                ->setInvoiceNumber($data['parcel_number'])
                ->setExportReason($data['cargo_use']);
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

            Logger::printScreen(LogLevel::INFO, 'DHL面单返回数据', $data);

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

            $globalBarcodes = [];

            if (is_array($response['PackagesResult']['PackageResult'])) {
                foreach ($response['PackagesResult']['PackageResult'] as $value) {
                    //获取国际单号
                    $this->setGlobalBarcode($value["TrackingNumber"]);
                    $this->setPartnerBarcode($this->getGlobalBarcode());

                    $globalBarcodes[] = [
                        'box_number' => $value['@number'],
                        'tracking_number' => $this->getGlobalBarcode()
                    ];

                    $this->setPartnerCompany("DHL");
                }
            }

            return $this->updateManyNumbers($globalBarcodes, $pdfContent);
        } catch (PostApiException|InvalidPostInfoException $ex) {
            Logger::printScreen(LogLevel::ERROR, 'DHL面单对接失败', $ex->getMessage());
            throw new PostApiException($ex->getMessage());
        }
    }

    /**
     * 更新多个单号，用于一票多单
     * DHL就是这样
     * @param $globalNumber
     * @param $expressPdf
     * @return array
     */
    public function updateManyNumbers($globalNumber, $expressPdf)
    {
        return [
            'express_number' => $globalNumber,
            'express_pdf' => $expressPdf,
            'express_code' => $this->getTypeCode(),
            'parcel_id' => $this->getPackageId(),
            'identification_number' => $this->getShipmentIdentificationNumber()
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
     * @return string
     */
    public function getShipmentIdentificationNumber(): string
    {
        return $this->shipmentIdentificationNumber;
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
    protected function setPackage(Dimensions $dimension, $data, $reference, string $description = ''): Package
    {
        $package = new Package();

        $package->setNumber($data['number'])
            ->setDimensions($dimension)
            ->setWeight($data['weight'])  //use g
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
            ->setCompanyName(empty($data['sender_company_name']) ? $data['sender_fullname'] : $data['sender_company_name'])
            ->setCountryCode($data['sender_country_code'])
            ->setCity($data['sender_city'])
            ->setEmail($data['sender_email'])
            ->setStreet($data['sender_address1'])
            ->setPostCode($data['sender_postcode'])
            ->setPhoneNumber($data['sender_tel_number']);

        if ($withRegistrationNumber) {
            $shipper->setRegistrationNumber($data['sender_tax_number'] ?? 'NUM');
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
            ->setCompanyName($this->pinyin(empty($data['receiver_company_name']) ? $data['receiver_fullname'] : $data['receiver_company_name']))
            ->setPhoneNumber($data['receiver_tel_number'])
            ->setCity($this->pinyin($data['receiver_city']))
            ->setPostCode($data['receiver_postcode'])
            ->setCountryCode($data['receiver_country_code']);

        if (!empty($data['receiver_province'])) {
            $recipient->setStateOrProvinceCode($data['receiver_province']);
        }

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
