<?php

namespace TxTech\Express\DHL\Common;

use TxTech\Express\DHL\Arrayable;

class Shipper implements ArrayAble
{
    protected $name;

    protected $companyName;

    protected $phoneNumber;

    protected $email = '';

    protected $street;

    protected $street2;

    protected $street3;

    protected $numberTypeCode = 'VAT';

    protected $RegistrationNumber;

    /**
     * @return mixed
     */
    public function getNumberTypeCode()
    {
        return $this->numberTypeCode;
    }

    /**
     * @param mixed $numberTypeCode
     * @return Shipper
     */
    public function setNumberTypeCode($numberTypeCode)
    {
        $this->numberTypeCode = $numberTypeCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrationNumber()
    {
        return $this->RegistrationNumber;
    }

    /**
     * @param mixed $RegistrationNumber
     * @return Shipper
     */
    public function setRegistrationNumber($RegistrationNumber)
    {
        $this->RegistrationNumber = $RegistrationNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param mixed $street2
     * @return Shipper
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet3()
    {
        return $this->street3;
    }

    /**
     * @param mixed $street3
     * @return Shipper
     */
    public function setStreet3($street3)
    {
        $this->street3 = $street3;

        return $this;
    }

    protected $city;

    protected $postCode;

    protected $countryCode;

    protected $StateOrProvinceCode = '';

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Shipper
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     * @return Shipper
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     * @return Shipper
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Shipper
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     * @return Shipper
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Shipper
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     * @return Shipper
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     * @return Shipper
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateOrProvinceCode()
    {
        return $this->StateOrProvinceCode;
    }

    /**
     * @param string $StateOrProvinceCode
     * @return Shipper
     */
    public function setStateOrProvinceCode(string $StateOrProvinceCode)
    {
        $this->StateOrProvinceCode = $StateOrProvinceCode;

        return $this;
    }

    public function toArray()
    {
        $data = [
            'Contact' => [
                'PersonName' => $this->getName(),
                'CompanyName' => $this->getCompanyName(),
                'PhoneNumber' => $this->getPhoneNumber(),
                'EmailAddress' => $this->getEmail(),
            ],
            'Address' => [
                'StreetLines' => $this->getStreet(),
                'City' => $this->getCity(),
                'StateOrProvinceCode' => $this->getStateOrProvinceCode(),
                'PostalCode' => $this->getPostCode(),
                'CountryCode' => $this->getCountryCode(),
                'StreetLines2' => $this->getStreet2(),
                'StreetLines3' => $this->getStreet3(),
            ],
            'RegistrationNumbers' => [
                'RegistrationNumber' => [
                    'Number' => $this->getRegistrationNumber(),
                    'NumberTypeCode' => 'VAT',
                    'NumberIssuerCountryCode' => $this->getCountryCode(),
                ],
            ],
        ];

        $this->unsetUnNeedStreet($data);

        $this->unsetRegistrationNumber($data);

        return $data;
    }

    /**
     * @param array $data
     */
    protected function unsetUnNeedStreet(array &$data)
    {
        if (!$data['Address']['StreetLines2']) {
            unset($data['Address']['StreetLines2']);
        }

        if (!$data['Address']['StreetLines3']) {
            unset($data['Address']['StreetLines3']);
        }
    }

    /**
     * @param array $data
     */
    protected function unsetRegistrationNumber(array &$data)
    {
        if (!$data['RegistrationNumbers']['RegistrationNumber']['Number']) {
            unset($data['RegistrationNumbers']);
        }
    }
}