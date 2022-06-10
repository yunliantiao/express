<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/6/10
 * Time : 12:01
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Package.php
 */


namespace Txtech\Express\Posts\DHL\Pickup;

/**
 * Class Package
 * @package Txtech\Express\Posts\DHL\Pickup
 */
class Package extends \Txtech\Express\Posts\DHL\Common\Package
{
    /**
     * @return array
     */
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