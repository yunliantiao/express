<?php

namespace TxTech\Express\DHL\PickUp;

class Package extends \TxTech\Express\DHL\Common\Package
{
    protected function map()
    {
        return [
            '@number' => $this->getNumber(),
            'Weight' => $this->getWeight(),
            'Dimensions' => [
                'Height' => $this->getDimensions()->getHeight(),
                'Width' => $this->getDimensions()->getWidth(),
                'Length' => $this->getDimensions()->getLength(),
            ],
            'CustomerReferences' => $this->getCustomerReferences(),
        ];
    }
}