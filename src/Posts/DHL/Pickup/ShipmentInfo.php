<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/6/10
 * Time : 12:02
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: ShipmentInfo.php
 */


namespace Txtech\Express\Posts\DHL\Pickup;


use Txtech\Express\Core\Arrayable;

/**
 * Class ShipmentInfo
 * @package Txtech\Express\Posts\DHL\Pickup
 */
class ShipmentInfo implements Arrayable
{
    /** @var string  */
    protected $serviceType = 'U';

    /**
     * @var string SI => KG/CM
     */
    protected $unitOfMeasurement = 'SI';

    /** @var  */
    protected $shipperAccountNumber;

    /** @var string  */
    protected $shippingPaymentType = 'S';

    /**
     * @return string
     */
    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    /**
     * @param  string  $serviceType
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
     * @param  string  $unitOfMeasurement
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
     * @param  mixed  $shipperAccountNumber
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
     * @param  string  $shippingPaymentType
     * @return ShipmentInfo
     */
    public function setShippingPaymentType(string $shippingPaymentType): ShipmentInfo
    {
        $this->shippingPaymentType = $shippingPaymentType;

        return $this;
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map();
    }
}