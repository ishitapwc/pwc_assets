<?php

declare(strict_types=1);

namespace Ecomm\Subscription\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as SetCollectionFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddAttributSets implements DataPatchInterface
{
    private const ATTRIBUTE_SETS = [
        'Subscription'
    ];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @var SetCollectionFactory
     */
    private $setCollectionFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param SetCollectionFactory $setCollectionFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory,
        AttributeSetFactory $attributeSetFactory,
        SetCollectionFactory $setCollectionFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->setCollectionFactory = $setCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $sortOrder = 200;
        foreach (self::ATTRIBUTE_SETS as $attributeset) {
            if (!$this->getAttributeSetId($attributeset)) {
                $attributeSet = $this->attributeSetFactory->create();
                $entityTypeId = $categorySetup->getEntityTypeId(Product::ENTITY);
                $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
                $data = [
                    'attribute_set_name' => $attributeset,
                    'entity_type_id' => $entityTypeId,
                    'sort_order' => $sortOrder,
                ];
                $attributeSet->setData($data);
                $attributeSet->validate();
                $attributeSet->save();
                $attributeSet->initFromSkeleton($attributeSetId);
                $attributeSet->save();
                $sortOrder += 100;
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Get Attributes set id
     *
     * @param string $attrSetName
     *
     * @return int
     */
    public function getAttributeSetId($attrSetName)
    {
        $attributeSet = $this->setCollectionFactory->create()->addFieldToSelect('*')
        ->addFieldToFilter('attribute_set_name', $attrSetName);
        $attributeSetId = 0;
        foreach ($attributeSet as $attr) {
            $attributeSetId = $attr->getAttributeSetId();
        }
        return $attributeSetId;
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
