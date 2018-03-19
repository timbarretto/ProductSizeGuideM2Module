<?php

namespace TimBarretto\ProductSizeGuide\Test\Unit\Block;

class ProductSizeGuideTabsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlBuilder;

    /**
     * @var ProductSizeGuide
     */
    protected $block;

    protected function setUp()
    {
        $this->urlBuilder = $this->createMock(\Magento\Framework\UrlInterface::class);
        $helper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $helper->getObject(
            \TimBarretto\ProductSizeGuide\Block\ProductSizeGuide::class,
            ['urlBuilder' => $this->urlBuilder]
        );
    }

    public function testGetAction()
    {
        $this->urlBuilder->expects($this->once())
            ->method('getUrl')
            ->with('section/productsizeguide/save', [])
            ->willReturn('section/productsizeguide/save');

        $this->assertEquals('section/productsizeguide/save', $this->block->getAction());
    }


    /**
     * @param bool $isVisible Determines whether the 'productSizeGuide' attribute is visible or enabled
     * @param bool $expectedValue The value we expect from ProductSizeGuide::isEnabled()
     * @return void
     *
     * @dataProvider isEnabledDataProvider
     */
    public function testIsEnabled($isVisible, $expectedValue)
    {
        $this->attribute->expects($this->once())->method('isVisible')->will($this->returnValue($isVisible));
        $this->assertSame($expectedValue, $this->_block->isEnabled());
    }

    /**
     * @return array
     */
    public function isEnabledDataProvider()
    {
        return [[true, true], [false, false]];
    }

}
