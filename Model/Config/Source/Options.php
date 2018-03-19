<?php
/**
 * Created by PhpStorm.
 * User: timbarretto
 * Date: 12/03/2018
 * Time: 13:25
 */

namespace TimBarretto\ProductSizeGuide\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

use Magento\Framework\DB\Ddl\Table;

/**

 * Custom Attribute Renderer

 */

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource

{

    /**
     * @var OptionFactory
     */

    protected $optionFactory;

    /**
     * @param OptionFactory $optionFactory
     */

    /**
     * Get all options
     *
     * @return array
     */

    public function getAllOptions()

    {

        /* your Attribute options list*/

        $this->_options=[
            ['label'=>'Trousers Guide', 'value'=>'Trousers Guide'],
            ['label'=>'Shirt Guide', 'value'=>'Shirt Guide'],
        ];

        return $this->_options;

    }

}