<?php
/**
 * Created by PhpStorm.
 * User: timbarretto
 * Date: 06/03/2018
 * Time: 12:30
 */

namespace TimBarretto\ProductSizeGuide\Block;

use Magento\Framework\View\Element\Template;


class SizeGuide extends Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);

        $this->setTabTitle();
    }


    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle()
    {
        $title =  __('Size Guide');
        $this->setTitle($title);
    }

    /**
     * Retrieve current product object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }


    /**
     * is enabled
     *
     * @return boo
     */
    public function isEnabled()
    {

        return $this->_scopeConfig->getValue('productsizeguide/productSize/productSizeGuideEnabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
