<?php

namespace TxTech\Express\DHL\Common;

class Recipient extends Shipper
{
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