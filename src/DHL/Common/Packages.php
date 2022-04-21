<?php

namespace TxTech\Express\DHL\Common;

use TxTech\Express\DHL\Arrayable;

class Packages implements ArrayAble
{
    /**
     * @var Package[]
     */
    protected $packages = [];

    public function add(Package $package)
    {
        $this->packages[] = $package;
    }

    public function map()
    {
        return [
            'RequestedPackages' => array_map(function ($package) {
                return $package->toArray();
            }, $this->packages),
        ];
    }

    public function toArray()
    {
        return $this->map();
    }
}