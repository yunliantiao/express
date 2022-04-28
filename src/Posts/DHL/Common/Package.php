<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:43
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Package.php
 */


namespace Txtech\Express\Posts\DHL\Common;


use Txtech\Express\Core\Arrayable;

/**
 * Class Package
 * @package Txtech\Express\Posts\DHL\Common
 */
class Package implements Arrayable
{
    /**
     * @var int
     */
    protected int $number;

    /**
     * @var float
     */
    protected mixed $weight;

    /**
     * @var Dimensions
     */
    protected Dimensions $dimensions;

    /**
     * @var string
     */
    protected string $customerReferences;

    /**
     * @var string
     */
    protected string $packageContentDescription = '';

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
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Package
     */
    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight(): mixed
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return Package
     */
    public function setWeight(mixed $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @param Dimensions $dimensions
     * @return Package
     */
    public function setDimensions(Dimensions $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerReferences(): string
    {
        return $this->customerReferences;
    }

    /**
     * @param string $customerReferences
     * @return Package
     */
    public function setCustomerReferences(string $customerReferences): static
    {
        $this->customerReferences = $customerReferences;

        return $this;
    }

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
            'PackageContentDescription' => $this->getPackageContentDescription(),
            'CustomerReferences' => $this->getCustomerReferences(),
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
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
