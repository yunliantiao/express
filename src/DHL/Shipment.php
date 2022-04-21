<?php

namespace TxTech\Express\DHL;

use TxTech\Express\DHL\Shipment\InternationalDetail;

class Shipment
{
    protected $shipmentInfo;

    /**
     * @var string
     */
    protected $shipTimestamp;

    /**
     * @var string
     */
    protected $paymentInfo = 'DAP';

    protected $internationalDetail;

    protected $ship;

    protected $packages;

    /**
     * @return mixed
     */
    public function getShipmentInfo()
    {
        return $this->shipmentInfo;
    }

    /**
     * @param mixed $shipmentInfo
     * @return Shipment
     */
    public function setShipmentInfo($shipmentInfo)
    {
        $this->shipmentInfo = $shipmentInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getShipTimestamp()
    {
        if (!$this->shipmentInfo) {
            return gmdate('Y-m-d\TH:i:s \G\M\TP', $time ?? time() + 60);
        }

        return $this->shipTimestamp;
    }

    /**
     * @param string $shipTimestamp
     * @return Shipment
     */
    public function setShipTimestamp(string $shipTimestamp): Shipment
    {
        $this->shipTimestamp = $shipTimestamp;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentInfo(): string
    {
        return $this->paymentInfo;
    }

    /**
     * @param string $paymentInfo
     * @return Shipment
     */
    public function setPaymentInfo(string $paymentInfo): Shipment
    {
        $this->paymentInfo = $paymentInfo;

        return $this;
    }

    protected function getInternationalDetail()
    {
        return $this->internationalDetail;
    }

    /**
     * @param InternationalDetail $internationalDetail
     * @return Shipment
     */
    public function setInternationalDetail(InternationalDetail $internationalDetail)
    {
        $this->internationalDetail = $internationalDetail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * @param mixed $ship
     * @return Shipment
     */
    public function setShip($ship)
    {
        $this->ship = $ship;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param mixed $packages
     * @return Shipment
     */
    public function setPackages($packages)
    {
        $this->packages = $packages;

        return $this;
    }

    /**
     * @return array
     */
    protected function map()
    {
        return [
            'ShipmentRequest' => [
                'RequestedShipment' => [
                    'ShipmentInfo' => $this->getShipmentInfo(),
                    'ShipTimestamp' => $this->getShipTimestamp(),
                    'PaymentInfo' => $this->getPaymentInfo(),
                    'InternationalDetail' => $this->getInternationalDetail(),
                    'Ship' => $this->getShip(),
                    'Packages' => $this->getPackages(),
                ]
            ]
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