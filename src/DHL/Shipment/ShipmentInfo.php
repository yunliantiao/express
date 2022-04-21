<?php

namespace TxTech\Express\DHL\Shipment;

use TxTech\Express\DHL\Arrayable;

class ShipmentInfo implements ArrayAble
{
    const SERVICE_TYPE_IT = 'N';

    const SERVICE_TYPE_OTHER = 'P';

    const SERVICE_TYPE_EU = 'U';

    const SERVICE_TYPE_OTHER_DOC = 'D';

    protected $DropOffType = 'REGULAR_PICKUP';

    protected $serviceType = 'P';

    protected $account;

    protected $currency = 'EUR';

    protected $unitOfMeasurement = 'SI';

    protected $labelType = 'PDF';

    protected $customsInvoiceTemplate = 'COMMERCIAL_INVOICE_03';

    protected $labelOptions = [];

    /**
     * @var float
     */
    protected $insuranceValue;

    /**
     * @var bool
     */
    protected $withCustomsInvoice = false;

    /**
     * @var bool
     */
    protected $withInsurance = false;

    /**
     * @var bool
     */
    protected $withDocumentInsurance = false;

    /**
     * @return float
     */
    public function getInsuranceValue(): float
    {
        return $this->insuranceValue;
    }

    /**
     * @param float $insuranceValue
     * @return ShipmentInfo
     */
    public function setInsuranceValue(float $insuranceValue): ShipmentInfo
    {
        $this->insuranceValue = $insuranceValue;

        return $this;
    }

    /**
     * @return $this
     */
    public function withInsurance()
    {
        $this->withInsurance = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withDocumentInsurance()
    {
        $this->withDocumentInsurance = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getDropOffType(): string
    {
        return $this->DropOffType;
    }

    /**
     * @param string $DropOffType
     * @return ShipmentInfo
     */
    public function setDropOffType(string $DropOffType): ShipmentInfo
    {
        $this->DropOffType = $DropOffType;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    /**
     * @param string $serviceType
     * @return ShipmentInfo
     */
    public function setServiceType(string $serviceType): ShipmentInfo
    {
        $this->serviceType = $serviceType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     * @return ShipmentInfo
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return ShipmentInfo
     */
    public function setCurrency(string $currency): ShipmentInfo
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnitOfMeasurement(): string
    {
        return $this->unitOfMeasurement;
    }

    /**
     * @param string $unitOfMeasurement
     * @return ShipmentInfo
     */
    public function setUnitOfMeasurement(string $unitOfMeasurement): ShipmentInfo
    {
        $this->unitOfMeasurement = $unitOfMeasurement;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelType(): string
    {
        return $this->labelType;
    }

    /**
     * @param string $labelType
     * @return ShipmentInfo
     */
    public function setLabelType(string $labelType): ShipmentInfo
    {
        $this->labelType = $labelType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomsInvoiceTemplate(): string
    {
        return $this->customsInvoiceTemplate;
    }

    /**
     * @param string $customsInvoiceTemplate
     * @return ShipmentInfo
     */
    public function setCustomsInvoiceTemplate(string $customsInvoiceTemplate): ShipmentInfo
    {
        $this->customsInvoiceTemplate = $customsInvoiceTemplate;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabelOptions(): array
    {
        return $this->labelOptions;
    }

    /**
     * @param array $labelOptions
     * @return ShipmentInfo
     */
    public function setLabelOptions(array $labelOptions): ShipmentInfo
    {
        $this->labelOptions = $labelOptions;

        return $this;
    }

    public function withCustomsInvoice()
    {
        $this->withCustomsInvoice = true;

        return $this;
    }

    /**
     * @return array
     */
    public function map()
    {
        return [
            'DropOffType' => $this->getDropOffType(),
            'ServiceType' => $this->getServiceType(),
            'Account' => $this->getAccount(),
            'Currency' => $this->getCurrency(),
            'UnitOfMeasurement' => $this->getUnitOfMeasurement(),
            'LabelType' => $this->getLabelType(),
            'LabelOptions' => [
                'RequestWaybillDocument' => 'Y',
                'DetachOptions' => [
                    'AllInOnePDF' => 'Y',
                ],
            ],
        ];
    }

    public function mapWithInvoice()
    {
        return [
            'DropOffType' => $this->getDropOffType(),
            'ServiceType' => $this->getServiceType(),
            'Account' => $this->getAccount(),
            'Currency' => $this->getCurrency(),
            'UnitOfMeasurement' => $this->getUnitOfMeasurement(),
            'LabelType' => $this->getLabelType(),
            'CustomsInvoiceTemplate' => 'COMMERCIAL_INVOICE_03',
            'LabelOptions' => [
                'RequestWaybillDocument' => 'Y',
                'RequestDHLCustomsInvoice' => 'Y',
                'DHLCustomsInvoiceType' => 'PROFORMA_INVOICE',
                'DetachOptions' => [
                    'AllInOnePDF' => 'Y',
                ],
            ],
        ];
    }

    public function toArray()
    {
        $data = $this->map();

        if ($this->withCustomsInvoice) {
            $data = $this->mapWithInvoice();
        }

        if ($this->withInsurance) {
            $data += $this->insuranceService();
        }

        if ($this->withDocumentInsurance) {
            $data += $this->documentInsuranceService();
        }

        return $data;
    }

    /**
     * @return \array[][][]
     */
    protected function insuranceService()
    {
        return [
            'SpecialServices' => [
                'Service' => [
                    [
                        'ServiceType' => 'II',
                        'ServiceValue' => $this->getInsuranceValue(),
                        'CurrencyCode' => $this->getCurrency(),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \array[][][]
     */
    protected function documentInsuranceService()
    {
        return [
            'SpecialServices' => [
                'Service' => [
                    [
                        'ServiceType' => 'IB',
                        'ServiceValue' => $this->getInsuranceValue(),
                        'CurrencyCode' => $this->getCurrency(),
                    ],
                ],
            ],
        ];
    }
}