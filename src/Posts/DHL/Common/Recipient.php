<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:31
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Recipient.php
 */


namespace Txtech\Express\Posts\DHL\Common;

/**
 * Class Recipient
 * @package Txtech\Express\Posts\DHL\Common
 */
class Recipient extends Shipper
{
    /**
     * @return array[]
     */
    public function toArray()
    {
        $data = [
            'Contact' => [
                'PersonName' => $this->getName(),
                'CompanyName' => $this->getCompanyName(),
                'PhoneNumber' => $this->getPhoneNumber(),
            ],
            'Address' => [
                'StreetLines' => $this->getStreet(),
                'City' => $this->getCity(),
                'StateOrProvinceCode' => $this->getStateOrProvinceCode(),
                'PostalCode' => $this->getPostCode(),
                'CountryCode' => $this->getCountryCode(),
                'StreetLines2' => $this->getStreet2(),
                'StreetLines3' => $this->getStreet3(),
            ]
        ];

        $this->unsetUnNeedStreet($data);

        return $data;
    }
}