<?php

namespace TxTech\Express\DHL\Shipment;

use TxTech\Express\DHL\Arrayable;

class InternationalDetail implements ArrayAble
{
    const DOCUMENTS = 'DOCUMENTS';

    const NON_DOCUMENTS = 'NON_DOCUMENTS';

    protected $Description;

    protected $customsValue;

    protected $content = 'NON_DOCUMENTS';

    /**
     * @var string
     */
    protected $invoiceDate = '';

    protected $invoiceNumber = '';

    protected $exportLineItems = [];

    protected $requestInvoice = false;

    /**
     * @param array $items
     * @return $this
     */
    public function withInvoice(array $items)
    {
        $this->requestInvoice = true;

        $this->exportLineItems = $items;

        return $this;
    }

    /**
     * @return $this
     */
    public function setContentDocuments()
    {
        $this->content = self::DOCUMENTS;

        return $this;
    }

    /**
     * @return $this
     */
    public function setContentNonDocuments()
    {
        $this->content = self::NON_DOCUMENTS;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     * @return InternationalDetail
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomsValue()
    {
        return $this->customsValue;
    }

    /**
     * @param mixed $customsValue
     * @return InternationalDetail
     */
    public function setCustomsValue($customsValue)
    {
        $this->customsValue = $customsValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return InternationalDetail
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * @param string $invoiceDate
     * @return InternationalDetail
     */
    public function setInvoiceDate(string $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     * @return InternationalDetail
     */
    public function setInvoiceNumber(string $invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return array
     */
    public function getExportLineItems()
    {
        return $this->exportLineItems;
    }

    /**
     * @param array $exportLineItems
     * @return InternationalDetail
     */
    public function setExportLineItems(array $exportLineItems)
    {
        $this->exportLineItems = $exportLineItems;

        return $this;
    }

    protected function map()
    {
        return [
            'Commodities' => [
                'Description' => $this->getDescription(),
                'CustomsValue' => $this->getCustomsValue(),
            ],
            'Content' => $this->getContent(),
            'ExportDeclaration' => [
                'InvoiceDate' => $this->getInvoiceDate(),
                'InvoiceNumber' => $this->getInvoiceNumber(),
                'ExportLineItems' => [
                    'ExportLineItem' => $this->getExportLineItems(),
                ],
            ]
        ];
    }

    protected function mapWithoutInvoice()
    {
        return [
            'Commodities' => [
                'Description' => $this->getDescription(),
                'CustomsValue' => $this->getCustomsValue(),
            ],
            'Content' => $this->getContent(),
        ];
    }

    public function toArray()
    {
        if ($this->requestInvoice) {
            return $this->map();
        }

        return $this->mapWithoutInvoice();
    }
}