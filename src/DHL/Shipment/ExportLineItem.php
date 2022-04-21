<?php

namespace TxTech\Express\DHL\Shipment;


class ExportLineItem
{
    protected $itemNumber;

    protected $quantity;

    protected $quantityUnitOfMeasurement;

    protected $itemDescription;

    protected $unitPrice;

    protected $netWeight;

    protected $grossWeight;

    /**
     * @return mixed
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * @param mixed $itemNumber
     * @return ExportLineItem
     */
    public function setItemNumber($itemNumber)
    {
        $this->itemNumber = $itemNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return ExportLineItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantityUnitOfMeasurement()
    {
        return $this->quantityUnitOfMeasurement;
    }

    /**
     * @param mixed $quantityUnitOfMeasurement
     * @return ExportLineItem
     */
    public function setQuantityUnitOfMeasurement($quantityUnitOfMeasurement)
    {
        $this->quantityUnitOfMeasurement = $quantityUnitOfMeasurement;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemDescription()
    {
        return $this->itemDescription;
    }

    /**
     * @param mixed $itemDescription
     * @return ExportLineItem
     */
    public function setItemDescription($itemDescription)
    {
        $this->itemDescription = $itemDescription;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     * @return ExportLineItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetWeight()
    {
        return $this->netWeight;
    }

    /**
     * @param mixed $netWeight
     * @return ExportLineItem
     */
    public function setNetWeight($netWeight)
    {
        $this->netWeight = $netWeight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrossWeight()
    {
        return $this->grossWeight;
    }

    /**
     * @param mixed $grossWeight
     * @return ExportLineItem
     */
    public function setGrossWeight($grossWeight)
    {
        $this->grossWeight = $grossWeight;

        return $this;
    }

    public function map()
    {
        return [
            'ItemNumber' => $this->getItemNumber(),
            'Quantity' => $this->getQuantity(),
            'QuantityUnitOfMeasurement' => $this->getQuantityUnitOfMeasurement(),
            'ItemDescription' => $this->getItemDescription(),
            'UnitPrice' => $this->getUnitPrice(),
            'NetWeight' => $this->getNetWeight(),
            'GrossWeight' => $this->getGrossWeight(),
        ];
    }

    public function toArray()
    {
        return $this->map();
    }
}