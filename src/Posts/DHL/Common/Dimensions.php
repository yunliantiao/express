<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 13:52
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Dimensions.php
 */


namespace Txtech\Express\Posts\DHL\Common;

/**
 * Class Dimensions
 * @package Txtech\Express\Posts\DHL\Common
 */
class Dimensions
{
    /** @var float */
    protected float $length;

    /** @var float */
    protected float $width;

    /** @var float */
    protected float $height;

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return $this
     */
    public function setLength(mixed $length): static
    {
        $this->length = sprintf('%.1f', $length / 10);

        return $this;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param $width
     * @return $this
     */
    public function setWidth($width): static
    {
        $this->width = sprintf('%.1f', $width / 10);

        return $this;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param $height
     * @return $this
     */
    public function setHeight($height): static
    {
        $this->height = sprintf('%.1f', $height / 10);

        return $this;
    }
}