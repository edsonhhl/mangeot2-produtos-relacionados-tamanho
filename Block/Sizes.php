<?php
namespace Dot\ProductSizes\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Dot\ProductSizes\Helper\Data as Helper;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;

class Sizes extends Template
{
    protected $registry;
    protected $helper;
    protected $imageHelper;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Helper $helper,
        ImageHelper $imageHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->helper = $helper;
        $this->imageHelper = $imageHelper;
    }

    public function getProduct(): ?Product
    {
        return $this->registry->registry('current_product');
    }

    public function getRelated(): array
    {
        $product = $this->getProduct();
        if (!$product) return [];
        $raw = (string)($product->getData('dot_related_sizes') ?? '');
        $rows = $this->helper->parseLines($raw);
        $out = [];
        foreach ($rows as $r) {
            $p = $this->helper->getProductBySku($r['sku']);
            if (!$p) continue;
            if (!$p->isSalable()) continue;
            $out[] = [
                'sku' => $r['sku'],
                'label' => $r['label'],
                'product' => $p,
                'url' => $p->getProductUrl(),
                'image' => $this->imageHelper->init($p, 'product_small_image')->getUrl()
            ];
        }
        return $out;
    }

    public function renderPrice(Product $product): string
    {
        $priceRender = $this->getLayout()->createBlock(
            '\\Magento\\Framework\\Pricing\\Render',
            'dot.product.price.render.' . $product->getId(),
            ['data' => ['price_render_handle' => 'catalog_product_prices', 'use_link_for_as_low_as' => true]]
        );
        if ($priceRender) {
            return $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                ['render_zone' => 'item_list', 'price_id' => 'dot-price-'.$product->getId(), 'include_container' => true]
            );
        }
        return '';
    }
}
