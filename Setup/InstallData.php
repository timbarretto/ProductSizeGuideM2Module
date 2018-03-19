<?php
/**
 * Created by PhpStorm.
 * User: timbarretto
 * Date: 31/01/2018
 * Time: 09:41
 */

namespace TimBarretto\ProductSizeGuide\Setup;

use Magento\Catalog\Helper\DefaultCategory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;


class InstallData implements InstallDataInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;


    /**
     * @var \Magento\Cms\Model\BlockRepository
     */
    protected $blockRepository;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var DefaultCategory
     */
    private $defaultCategory;

    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Product setup factory
     *
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * Product setup factory
     *
     * @var Product
     */
    private $productModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var string
     */
    protected $productType = \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;


    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;


    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @var \Magento\Eav\Api\AttributeSetRepositoryInterface
     */
    protected $attributeSet;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
     */
    protected $_attributeSetCollectionFactory;


    /**
     * @deprecated 101.0.0
     * @return DefaultCategory
     */
    private function getDefaultCategory()
    {
        if ($this->defaultCategory === null) {
            $this->defaultCategory = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(DefaultCategory::class);
        }
        return $this->defaultCategory;
    }

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\Product $productModel
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Cms\Model\BlockRepository $blockRepository
     * @param AttributeSetFactory $attributeSetFactory
     * @param \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollectionFactory
     * @throws \Magento\Framework\Exception\LocalizedException
     * @internal param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection
     */
    public function __construct(EavSetupFactory $eavSetupFactory,
                                CategorySetupFactory $categorySetupFactory,
                                \Magento\Catalog\Model\ProductFactory $productFactory,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
                                \Magento\Catalog\Model\Product $productModel,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
                                $productCollectionFactory,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
                                \Magento\Framework\App\State $state,
                                \Magento\Cms\Model\BlockFactory $blockFactory,
                                \Magento\Cms\Model\BlockRepository $blockRepository,
                                AttributeSetFactory $attributeSetFactory,
                                \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
                                \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
                                $attributeSetCollectionFactory
    )
    {

        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->productFactory = $productFactory;
        $this->productModel = $productModel;
        $this->storeManager = $storeManager;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->productRepository = $productRepository;
        $this->_productCollectionFactory = $productCollectionFactory;

        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSet = $attributeSet;
        $this->_attributeSetCollectionFactory = $attributeSetCollectionFactory;
        if(!$state->getAreaCode()) {
            $state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        }

    }


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->create($setup, $context);

    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {


//        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.1') < 0) {
//
        // PLACEHOLDER: add attribute code goes here
//        }

    }


    public function create(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createProductAttributes($setup);
        $this->createCategories($setup, $context);
        $this->createBlocks();
        $setup->endSetup();

    }


    public function createAttribute(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'custom_attribute',
            [
                'group' => 'General Information',
                'type' => 'varchar',
                'label' => 'Custom Attribute',
                'input' => 'text',
                'required' => false,
                'sort_order' => 100,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function createCategories(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $categoryName = array(
            "category_name" => 'Default Category',
            "categories" => array(
                array(
                    "category_name" => 'Trousers',
                    "products" => array(
                        array(
                            "product_name" => "Smart Trousers",
                            "size_guide" => "Trousers Guide"
                        ),
                        array(
                            "product_name" => "Casual Trousers",
                            "size_guide" => "Trousers Guide"
                        )
                    )
                ),
                array(
                    "category_name" => 'Shirts',
                    "products" => array(
                        array(
                            "product_name" => "Smart Shirt",
                            "size_guide" => "Shirt Guide"
                        ),
                        array(
                            "product_name" => "Casual Shirt",
                            "size_guide" => "Shirt Guide"
                        )
                    )
                )
            )
        );


        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $rootCategoryId = \Magento\Catalog\Model\Category::TREE_ROOT_ID;
        $defaultCategoryId = $this->getDefaultCategory()->getId();

        $attributeSet = $this->createAttributeSet($categorySetup);


        $collection = $this->_categoryCollectionFactory
            ->create()
            ->addAttributeToFilter('name', $categoryName["category_name"])
            ->setPageSize(1);


        if ($collection->getSize()) {
            $categoryRoot = $collection->getFirstItem();

        } else {
            // Create Default Catalog Node
            $categoryRoot = $categorySetup->createCategory();
            $categoryRoot->load($defaultCategoryId)
                ->setId(0)
                ->setStoreId(0)
                ->setPath($rootCategoryId)
                ->setName($categoryName["category_name"])
                ->setDisplayMode('PRODUCTS')
                ->setIsActive(1)
                ->setLevel(1)
                ->setParentId(1)
                ->setInitialSetupFlag(true)
                ->setAttributeSetId($categoryRoot->getDefaultAttributeSetId())
                ->save();

        }


        foreach ($categoryName["categories"] as $categoryAry) {

            $collection = $this->_categoryCollectionFactory
                ->create()
                ->addAttributeToFilter('name', $categoryAry['category_name'])
                ->setPageSize(1);


            if ($collection->getSize()) {
                $category = $collection->getFirstItem();

            } else {
                $category = $categorySetup->createCategory();
                $category->load($defaultCategoryId)
                    ->setId(0)
                    ->setStoreId(0)
                    ->setPath($rootCategoryId . '/' . $categoryRoot->getId())
                    ->setName($categoryAry['category_name'])
                    ->setDisplayMode('PRODUCTS')
                    ->setIsActive(1)
                    ->setLevel(1)
                    ->setParentId($categoryRoot->getId())
                    ->setInitialSetupFlag(true)
                    ->setAttributeSetId($attributeSet->getId())
                    ->save();

            }

            $this->createProducts($categoryAry, $category->getId());

        }

    }


    public function createProducts($productsData, $categoryId)
    {


        foreach ($productsData['products'] as $productData) {
            $collection = $this->_productCollectionFactory
                ->create()
                ->addAttributeToFilter('sku', $productData['product_name'])
                ->setPageSize(1);
            if (!$collection->getSize()) {

                $product = $this->productFactory->create();
                $product->setWebsiteIds(array(1));
                $product->setAttributeSetId(4);
                $product->setTypeId('simple');
                $product->setCreatedAt(strtotime('now'));
                $product->setName($productData['product_name']);
                $product->setSku($productData['product_name']);
                $product->setCategoryIds(array($categoryId));
                $product->setTaxClassId(0); // (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                $product->setVisibility(4); // catalog and search visibility
                $product->setColor(24);
                $product->setPrice(1);
                $product->setCost(1);
                $product->setDescription($productData['product_name']);
                $product->setShortDescription($productData['product_name']);
                $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                $product->setCustomAttribute('sizeguide',$productData['size_guide']);
                $product->setStockData(
                    array(
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 1, // manage stock
                        'min_sale_qty' => 1, // Shopping Cart Minimum Qty Allowed
                        'max_sale_qty' => 2, // Shopping Cart Maximum Qty Allowed
                        'is_in_stock' => 1, // Stock Availability of product
                        'qty' => 1
                    )
                );
                $product->save();
            }

        }

    }


    public function createProductAttributes(ModuleDataSetupInterface $setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'sizeguide');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'sizeguide',/* Custom Attribute Code */
            [
                'group' => 'Product Details',/*Product Group name in which you want
                                              to display your custom attribute */
                'type' => 'int',/* Data type in which formate your value save in database*/
                'backend' => '',
                'frontend' => '',
                'label' => 'SizeGuide', /* lablel of your attribute*/
                'input' => 'select',
                'class' => '',
                'source' => 'TimBarretto\ProductSizeGuide\Model\Config\Source\Options',
                /* Source of your select type custom attribute options*/
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                /*Scope of your attribute */
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }

    public function createBlocks()
    {
        $blocksArray = [
            [
            'title' => 'Shirt Size Guide',
            'identifier' => 'Shirt Guide',
            'stores' => [0],
            'is_active' => 1,
            'content' => 'This is the shirt size guide'
            ],
            [
            'title' => 'Trousers Size Guide',
            'identifier' => 'Trousers Guide',
            'stores' => [0],
            'is_active' => 1,
            'content' => 'This is the trousers size guide'
            ]
        ];

        foreach ($blocksArray as $block) {
            $newBlock = $this->blockFactory->create(['data' => $block]);
            $this->blockRepository->save($newBlock);
        }
    }

    /**
     * @param $categorySetup
     * @return mixed
     */
    private function createAttributeSet($categorySetup)
    {

        $collection = $this->_attributeSetCollectionFactory
            ->create()
            ->addFieldToFilter('attribute_set_name', 'ProductSizeGuide')
            ->setPageSize(1);
        if ($collection->getSize()) {
            $attributeSet = $collection->getFirstItem();

        }else
        {
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data = [
                'attribute_set_name' => 'ProductSizeGuide', // define custom attribute set name here
                'entity_type_id' => $entityTypeId,
                'sort_order' => 200,
            ];
            $attributeSet->setData($data);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();
        }
        return $attributeSet;
    }
}