<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:19
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: InternationalDetail.php
 */


namespace Txtech\Express\Posts\DHL\Shipment;


use Txtech\Express\Core\Arrayable;

/**
 * Class InternationalDetail
 * @package Txtech\Express\Posts\DHL\Shipment
 */
class InternationalDetail implements ArrayAble
{
    /** @var string */
    public const DOCUMENTS = 'DOCUMENTS';

    /** @var string */
    public const NON_DOCUMENTS = 'NON_DOCUMENTS';

    /** @var */
    protected mixed $description;

    /** @var */
    protected mixed $customsValue;

    /** @var string  */
    protected string $content = 'NON_DOCUMENTS';

    /** @var string */
    protected string $invoiceDate = '';

    /** @var string */
    protected string $invoiceNumber = '';

    /** @var array */
    protected array $exportLineItems = [];

    /** @var bool */
    protected bool $requestInvoice = false;

    /** @var string  */
    protected string $exportReason = '';

    /**
     * @param array $items
     * @return InternationalDetail
     */
    public function withInvoice(array $items)
    {
        $this->requestInvoice = true;

        $this->exportLineItems = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function getExportReason(): string
    {
        return $this->exportReason;
    }

    /**
     * @param string $exportReason
     * @return InternationalDetail
     */
    public function setExportReason(string $exportReason)
    {
        $this->exportReason = $exportReason;

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
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return InternationalDetail
     */
    public function setDescription($description)
    {
        $this->description = $description;

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

    /**
     * @return array
     */
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
                'ExportReason' => $this->getExportReason(),
                'ExportLineItems' => [
                    'ExportLineItem' => $this->getExportLineItems(),
                ],
            ]
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->requestInvoice) {
            return $this->map();
        }

        return $this->mapWithoutInvoice();
    }
}