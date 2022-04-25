<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:43
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Packages.php
 */


namespace Txtech\Express\Posts\DHL\Common;


use Txtech\Express\Core\Arrayable;

/**
 * Class Packages
 * @package Txtech\Express\Posts\DHL\Common
 */
class Packages implements ArrayAble
{
    /**
     * @var Package[]
     */
    protected array $packages = [];

    /**
     * @param Package $package
     * @return void
     */
    public function add(Package $package)
    {
        $this->packages[] = $package;
    }

    /**
     * @return array
     */
    public function map()
    {
        return [
            'RequestedPackages' => array_map(function ($package) {
                return $package->toArray();
            }, $this->packages),
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map();
    }
}