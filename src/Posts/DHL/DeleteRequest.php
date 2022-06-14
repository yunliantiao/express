<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/6/14
 * Time : 14:13
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: DeleteRequest.php
 */


namespace Txtech\Express\Posts\DHL;

/**
 * Class DeleteRequest
 * @package Txtech\Express\Posts\DHL
 */
class DeleteRequest
{
    /** @var string  */
    public string $PickupDate;

    /** @var string  */
    public string $PickupCountry;

    /** @var string  */
    public string $DispatchConfirmationNumber;

    /** @var string  */
    public string $RequestorName;

    /** @var string  */
    public string $Reason;

    /**
     * @return string
     */
    public function getPickupDate(): string
    {
        return $this->PickupDate;
    }

    /**
     * @param string $PickupDate
     * @return DeleteRequest
     */
    public function setPickupDate(string $PickupDate)
    {
        $this->PickupDate = $PickupDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPickupCountry(): string
    {
        return $this->PickupCountry;
    }

    /**
     * @param string $PickupCountry
     * @return DeleteRequest
     */
    public function setPickupCountry(string $PickupCountry): static
    {
        $this->PickupCountry = $PickupCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getDispatchConfirmationNumber(): string
    {
        return $this->DispatchConfirmationNumber;
    }

    /**
     * @param string $DispatchConfirmationNumber
     * @return DeleteRequest
     */
    public function setDispatchConfirmationNumber(string $DispatchConfirmationNumber): static
    {
        $this->DispatchConfirmationNumber = $DispatchConfirmationNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestorName(): string
    {
        return $this->RequestorName;
    }

    /**
     * @param string $RequestorName
     * @return DeleteRequest
     */
    public function setRequestorName(string $RequestorName): static
    {
        $this->RequestorName = $RequestorName;

        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->Reason;
    }

    /**
     * @param string $Reason
     * @return DeleteRequest
     */
    public function setReason(string $Reason): static
    {
        $this->Reason = $Reason;

        return $this;
    }

    /**
     * @return array|array[]
     */
    protected function map()
    {
        $data = [
            'DeleteRequest' => [
                'PickupDate' => $this->PickupDate,
                'PickupCountry' => $this->PickupCountry,
                'DispatchConfirmationNumber' => $this->DispatchConfirmationNumber,
                'RequestorName' => $this->RequestorName,
            ]
        ];

        if ($this->Reason) {
            $data['DeleteRequest']['Reason'] = $this->Reason;
        }

        return $data;
    }

    /**
     * @return array|array[]
     */
    public function toArray()
    {
        return $this->map();
    }
}