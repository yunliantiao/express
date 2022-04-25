<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:48
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Ship.php
 */


namespace Txtech\Express\Posts\DHL\Common;


use Txtech\Express\Core\Arrayable;

/**
 * Class Ship
 * @package Txtech\Express\Posts\DHL\Common
 */
class Ship implements ArrayAble
{
    /**
     * @var Shipper
     */
    protected $shipper;

    /**
     * @var Recipient
     */
    protected $recipient;

    /**
     * @var PickUp
     */
    protected $pickUp;

    /**
     * @return mixed
     */
    public function getShipper()
    {
        return $this->shipper;
    }

    /**
     * @param mixed $shipper
     * @return Ship
     */
    public function setShipper($shipper)
    {
        $this->shipper = $shipper;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     * @return Ship
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPickUp()
    {
        return $this->pickUp;
    }

    /**
     * @param mixed $pickUp
     * @return Ship
     */
    public function setPickUp($pickUp)
    {
        $this->pickUp = $pickUp;

        return $this;
    }

    /**
     * @return array
     */
    public function map()
    {
        $data = [
            'Shipper' => $this->getShipper()->toArray(),
        ];

        if ($this->getRecipient()) {
            $data += ['Recipient' => $this->getRecipient()->toArray()];
        }

        if ($this->getPickUp()) {
            $data += ['Pickup' => $this->getPickUp()->toArray()];
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