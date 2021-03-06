<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:14
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Post.php
 */


namespace Txtech\Express\Posts;

use Overtrue\Pinyin\Pinyin;

/**
 * Class Post
 * @package Txtech\Express\Posts
 */
abstract class Post
{
    /** @var int  */
    protected int $packageId;

    /** @var mixed  */
    protected mixed $created;

    /** @var Pinyin  */
    protected Pinyin $pinyin;

    /** @var string  */
    protected string $globalBarcode;

    /** @var string  */
    protected string $partnerBarcode;

    /** @var string  */
    protected string $partnerCompany = 'EMS';

    /**
     * @return void
     */
    public function __constrcut(): void
    {
        $this->pinyin = new Pinyin();
    }

    /**
     * 得到国内单号快递公司
     * @param $v
     * @return $this
     */
    public function setPartnerCompany($v)
    {
        $this->partnerCompany = $v;
        return $this;
    }

    /**
     * 得到国内单号
     * @param $v
     * @return $this
     */
    public function setPartnerBarcode($v)
    {
        $this->partnerBarcode = $v;
        return $this;
    }

    /**
     * 得到国际单号
     * @param $v
     * @return $this
     */
    public function setGlobalBarcode($v)
    {
        $this->globalBarcode = $v;
        return $this;
    }

    /**
     * 得到国际单号
     * @return string
     * @throws \Exception
     */
    public function getGlobalBarcode()
    {
        if (empty($this->globalBarcode)) {
            throw new \Exception("Unset GobalBarcode", 1);

        }
        return $this->globalBarcode;
    }

    /**
     * @return int
     */
    public function getPackageId(): int
    {
        return $this->packageId;
    }

    /**
     * @param $v
     * @return $this
     */
    public function setPackageId($v): static
    {
        $this->packageId = $v;
        return $this;
    }


    /**
     * @param $v
     * @return $this
     */
    public function setCreated($v): static
    {
        $this->created = $v;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCreated(): mixed
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getYMDCreated()
    {
        return date("Y-m-d", strtotime($this->created));
    }

    /**
     * @param $v
     * @return string
     */
    public function pinyin($v)
    {
        return $this->pinyin->sentence($v);
    }
}