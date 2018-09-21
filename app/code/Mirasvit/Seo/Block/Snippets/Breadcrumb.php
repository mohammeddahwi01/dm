<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.0.85
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Seo\Block\Snippets;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Seo\Traits\SnippetsTrait;

class Breadcrumb extends Template
{

    /**
     * @param Registry $registry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function getBreadcrumbSnippets()
    {
        $itemListElement = '';
        if ($breadcrumbs = $this->registry->registry('m__snipets_breadcrumbs')) {
            $i = 1;
            foreach ($breadcrumbs as $id => $name) {
                if ($i > 1) {
                    $itemListElement .= ',';
                }
                $itemListElement .=
                '{
                   "@type": "ListItem",
                   "position": ' . $i . ',
                   "item":
                   {
                       "@id": "' . $id . '",
                        "name": "' . SnippetsTrait::prepareBreadcrumbsSnippet($name) . '"
                    }
                }';
                $i++;
            }
        }

        return $itemListElement;
    }
}
