<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/6/10
 * Time : 12:04
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: PickupRequest.php
 */


namespace Txtech\Express\Posts\DHL;

use Txtech\Express\Posts\DHL\Common\Shipper;

/**
 * Class PickupRequest
 * @package Txtech\Express\Posts\DHL
 */
class PickupRequest
{
    protected $shipmentInfo;

    /**
     * @var string
     */
    protected $pickupTimestamp;

    /**
     * @var Shipper
     */
    protected $ship;

    protected $packages;

    /**
     * @var string 取件截止时间
     */
    protected $pickupLocationCloseTime = '';

    /**
     * @return mixed
     */
    public function getShipmentInfo()
    {
        return $this->shipmentInfo;
    }

    /**
     * @param  mixed  $shipmentInfo
     * @return PickUpRequest
     */
    public function setShipmentInfo($shipmentInfo)
    {
        $this->shipmentInfo = $shipmentInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getPickupTimestamp(): string
    {
        return $this->pickupTimestamp;
    }

    /**
     * @param  string  $pickupTimestamp
     * @return PickupRequest
     */
    public function setPickupTimestamp(string $pickupTimestamp): PickupRequest
    {
        $this->pickupTimestamp = $pickupTimestamp;

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
     * @param  mixed  $ship
     * @return PickupRequest
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
     * @param  mixed  $packages
     * @return PickupRequest
     */
    public function setPackages($packages)
    {
        $this->packages = $packages;

        return $this;
    }

    /**
     * @return string
     */
    public function getPickupLocationCloseTime(): string
    {
        return $this->pickupLocationCloseTime;
    }

    /**
     * @param  string  $pickupLocationCloseTime
     * @return PickupRequest
     */
    public function setPickupLocationCloseTime(string $pickupLocationCloseTime): PickupRequest
    {
        $this->pickupLocationCloseTime = $pickupLocationCloseTime;

        return $this;
    }

    /**
     * @return array
     */
    protected function map()
    {
        $data = [
            'PickUpRequest' => [
                'PickUpShipment' => [
                    'ShipmentInfo' => $this->getShipmentInfo(),
                    'PickupTimestamp' => $this->getPickupTimestamp(),
                    'Ship' => $this->getShip(),
                    'Packages' => $this->getPackages(),
                ]
            ]
        ];

        //如果有截止日期
        if ($this->getPickupLocationCloseTime()) {
            $data['PickUpRequest']['PickUpShipment'] += ['PickupLocationCloseTime' => $this->getPickupLocationCloseTime()];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map();
    }
}