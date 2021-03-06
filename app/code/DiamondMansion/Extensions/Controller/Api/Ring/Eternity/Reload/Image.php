<?php

namespace DiamondMansion\Extensions\Controller\Api\Ring\Eternity\Reload;

class Image extends \Magento\Framework\App\Action\Action
{
    protected $_productFactory;
    protected $_helper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \DiamondMansion\Extensions\Helper\Image $helper
    ) {
        $this->_productFactory = $productFactory;
        $this->_helper = $helper;

        return parent::__construct($context);
    }

    public function execute() {
        $params = $this->getRequest()->getParams();

        $product = $this->_productFactory->create()->load($params['product_id']);

        echo json_encode([
            $this->_helper->getProductImages([
                'sku' => $product->getSku(),
                'type' => $params['stone-type'],
                'shape' => $params['stone-shape'],
                'carat' => $params['stone-carat'],
                'metal' => $params['metal'],
            ])
        ]);
    }
}