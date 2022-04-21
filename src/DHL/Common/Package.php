<?php

namespace TxTech\Express\DHL\Common;

use TxTech\Express\DHL\Arrayable;

class Package implements Arrayable
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var Dimensions
     */
    protected $dimensions;

    /**
     * @var string
     */
    protected $customerReferences;

    /**
     * @var string
     */
    protected $packageContentDescription = '';

    /**
     * @return string
     */
    public function getPackageContentDescription(): string
    {
        return $this->packageContentDescription;
    }

    /**
     * @param string $packageContentDescription
     * @return Package
     */
    public function setPackageContentDescription(string $packageContentDescription): Package
    {
        $this->packageContentDescription = $packageContentDescription;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Package
     */
    public function setNumber(int $number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return Package
     */
    public function setWeight(int $weight)
    {
        $this->weight = sprintf('%.3f', $weight / 1000);

        return $this;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * @param Dimensions $dimensions
     * @return Package
     */
    public function setDimensions(Dimensions $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerReferences()
    {
        return $this->customerReferences;
    }

    /**
     * @param string $customerReferences
     * @return Package
     */
    public function setCustomerReferences(string $customerReferences)
    {
        $this->customerReferences = $customerReferences;

        return $this;
    }

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
            'PackageContentDescription' => $this->getPackageContentDescription(),
            'CustomerReferences' => $this->getCustomerReferences(),
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map();
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}