<?php

namespace TxTech\Express\DHL\Common;

class Dimensions
{
    /**
     * @var float cm
     */
    protected $length;

    /**
     * @var
     */
    protected $width;

    /**
     * @var
     */
    protected $height;

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return Dimensions
     */
    public function setLength($length)
    {
        $this->length = sprintf('%.1f', $length / 10);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return Dimensions
     */
    public function setWidth($width)
    {
        $this->width = sprintf('%.1f', $width / 10);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return Dimensions
     */
    public function setHeight($height)
    {
        $this->height = sprintf('%.1f', $height / 10);

        return $this;
    }
}