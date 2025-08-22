<?php
namespace Dot\ProductSizes\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class AddProductSizesAttribute implements DataPatchInterface
{
    private $moduleDataSetup;
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $code = 'dot_related_sizes';
        $entityType = \Magento\Catalog\Model\Product::ENTITY;

        if (!$eavSetup->getAttributeId($entityType, $code)) {
            $eavSetup->addAttribute(
                $entityType,
                $code,
                [
                    'type' => 'text',
                    'label' => 'Produtos Relacionados por Tamanho (SKU|Label por linha)',
                    'input' => 'textarea',
                    'required' => false,
                    'sort_order' => 300,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'user_defined' => true,
                    'group' => 'General',
                    'note' => 'Um por linha no formato: SKU|Rótulo (ex: 1234|500ml). Front mostra apenas itens em estoque.'
                ]
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies() { return []; }
    public function getAliases() { return []; }
}
