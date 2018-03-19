<?php
namespace TimBarretto\ProductSizeGuide\Test\Unit\Block\Adminhtml\Tab;


use Magento\Backend\Model\Session;
use Magento\Customer\Controller\RegistryConstants;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductSizeGuideTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Config\Block\System\Config\Tabs
     */
    protected $_object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formFactoryMock;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlBuilderMock;


    public function setUp()
    {
        $this->_requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->_requestMock->expects(
            $this->any()
        )->method(
            'getParam'
        )->with(
            'section'
        )->will(
            $this->returnValue('currentSectionId')
        );
        $this->_structureMock = $this->createMock(\Magento\Config\Model\Config\Structure::class);
        $this->_structureMock->expects($this->once())->method('getTabs')->will($this->returnValue([]));
        $this->_urlBuilderMock = $this->createMock(\Magento\Backend\Model\Url::class);

        $data = [
            'configStructure' => $this->_structureMock,
            'request' => $this->_requestMock,
            'urlBuilder' => $this->_urlBuilderMock,
        ];

        $helper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_object = $helper->getObject(\Magento\Config\Block\System\Config\Tabs::class, $data);
    }

    protected function tearDown()
    {
        unset($this->_object);
        unset($this->_requestMock);
        unset($this->_structureMock);
        unset($this->_urlBuilderMock);
    }

    public function testGetSectionUrl()
    {
        $this->_urlBuilderMock->expects(
            $this->once()
        )->method(
            'getUrl'
        )->with(
            '*/*/*',
            ['_current' => true, 'section' => 'testSectionId']
        )->will(
            $this->returnValue('testSectionUrl')
        );

        $sectionMock = $this->createMock(\Magento\Config\Model\Config\Structure\Element\Section::class);
        $sectionMock->expects($this->once())->method('getId')->will($this->returnValue('testSectionId'));


        var_dump($sectionMock);
        die();
        $this->assertEquals('testSectionUrl', $this->_object->getSectionUrl($sectionMock));
    }

    public function testTabExists()
    {
        /** @var \Magento\Backend\Block\Widget\Tab $object */
        $object = $this->helper->getObject(
            '\Magento\Backend\Block\Widget\Tab',
            ['data' => [$field => $value]]
        );
    }
    
}
