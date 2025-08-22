<?php
namespace Dot\ProductSizes\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    protected $productRepository;
    protected $storeManager;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    public function getProductBySku(string $sku)
    {
        try {
            return $this->productRepository->get($sku, false, $this->storeManager->getStore()->getId());
        } catch (\Exception $e) {
            return null;
        }
    }

    public function parseLines(?string $raw): array
    {
        if (!$raw) return [];
        $lines = preg_split('/\r?\n/', trim((string)$raw)) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if (strpos($line, '|') !== false) {
                [$sku, $label] = array_map('trim', explode('|', $line, 2));
            } elseif (strpos($line, '/') !== false) {
                [$sku, $label] = array_map('trim', explode('/', $line, 2));
            } else {
                $sku = $line; $label = $line;
            }
            if ($sku !== '' && $label !== '') {
                $out[] = ['sku' => $sku, 'label' => $label];
            }
        }
        return $out;
    }
}
