<?php
/**
 * Created by PhpStorm.
 * User: timbarretto
 * Date: 23/02/2018
 * Time: 14:52
 */

namespace TimBarretto\ProductSizeGuide\Test\Integration;


use Magento\Framework\Component\ComponentRegistrar;
use PHPUnit\Framework\TestCase;

class ProductSizeGuideIntegrationTest extends TestCase
{
    private $moduleName = 'TimBarretto_ProductSizeGuide';

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $paths = $registrar->getPaths(ComponentRegistrar::MODULE);
        $this->assertArrayHasKey($this->moduleName, $paths);
    }
}