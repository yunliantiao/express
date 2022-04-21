<?php

namespace TxTech\Express\DHL\PickUp;

use TxTech\Express\DHL\Arrayable;

class ShipmentInfo implements Arrayable
{
    protected $serviceType = 'U';

    /**
     * @var string SI => KG/CM
     */
    protected $unitOfMeasurement = 'SI';

    protected $shipperAccountNumber;

    protected $shippingPaymentType = 'S';

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
     * @return mixed
     */
    public function getShipperAccountNumber()
    {
        return $this->shipperAccountNumber;
    }

    /**
     * @param mixed $shipperAccountNumber
     * @return ShipmentInfo
     */
    public function setShipperAccountNumber($shipperAccountNumber)
    {
        $this->shipperAccountNumber = $shipperAccountNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingPaymentType(): string
    {
        return $this->shippingPaymentType;
    }

    /**
     * @param string $shippingPaymentType
     * @return ShipmentInfo
     */
    public function setShippingPaymentType(string $shippingPaymentType): ShipmentInfo
    {
        $this->shippingPaymentType = $shippingPaymentType;

        return $this;
    }

    public function map()
    {
        return [
            'ServiceType' => $this->getServiceType(),
            'UnitOfMeasurement' => $this->getUnitOfMeasurement(),
            'Billing' => [
                'ShipperAccountNumber' => $this->getShipperAccountNumber(),
                'ShippingPaymentType' => $this->getShippingPaymentType(),
            ],
        ];
    }

    public function toArray()
    {
        return $this->map();
    }
}