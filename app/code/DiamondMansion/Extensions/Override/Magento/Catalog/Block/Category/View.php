<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Block\Category;

class View extends \Magento\Catalog\Block\Category\View
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
/*
        $this->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);

        $category = $this->getCurrentCategory();
        if ($category) {
            $title = $category->getMetaTitle();
            if ($title) {
                $this->pageConfig->getTitle()->set($title);
            }
            $description = $category->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $category->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }
            if ($this->_categoryHelper->canUseCanonicalTag()) {
                $this->pageConfig->addRemotePageAsset(
                    $category->getUrl(),
                    'canonical',
                    ['attributes' => ['rel' => 'canonical']]
                );
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($this->getCurrentCategory()->getName());
            }
        }
*/
        $category = $this->getCurrentCategory();
        if ($category && $category->getDisplayMode() == \DiamondMansion\Extensions\Override\Magento\Catalog\Model\Category\Attribute\Source\Mode::DM_RING_DESIGN) {
            $title = $this->_getDmRingDesignPageTitle();
            if (!empty($title)) {
                $this->pageConfig->getTitle()->set($title);

                $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
                if ($pageMainTitle) {
                    $pageMainTitle->setPageTitle($title);
                }
            }
        }

        return $this;
    }

    protected function _getDmRingDesignPageTitle() {
        $params = array();
        foreach ($this->getRequest()->getParams() as $key=>$value) {
            if (in_array($key, ["dm_stone_type", "dm_stone_shape", "dm_band", "dm_metal", "dm_setting_style", "dm_design_collection", "dm_designer"])) {
                $params[$key] = $value;
            }
        }

        $pageTitle = "";
        if (count($params) < 4) {
            $pageTitle = "Engagement Rings";

            $eavConfig = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Eav\Model\Config');
            
            if (count($params) == 1) {
                foreach ($params as $attrKey => $attrValue) {
                    $attr = $eavConfig->getAttribute('catalog_product', $attrKey);
                    $attrLabel = $attr->getSource()->getOptionText($attrValue);

                    switch ($attrKey) {
                        case "dm_stone_shape":
                            if ($attrLabel == "Heart") {
                                $pageTitle = "Heart Shape " . $pageTitle;
                            } else {
                                $pageTitle = $attrLabel . " cut " . $pageTitle;
                            }
                            break;
                        case "dm_stone_type":
                            if ($attrLabel == "Natural") {
                                $pageTitle = "Diamond " . $pageTitle;
                            } else if ($attrLabel == "Setting") {
                                $pageTitle = "Engagement Ring Settings";
                            } else {
                                $pageTitle = $attrLabel . " Diamond " . $pageTitle;
                            }
                            break;
                        case "dm_setting_style":
                        case "dm_design_collection":
                        case "dm_metal":
                            $pageTitle = $attrLabel . " " . $pageTitle;
                            break;
                        case "dm_band":
                            if ($attrLabel == "Bridal Set") {
                                $pageTitle = "Bridal Wedding Ring Sets";
                            }
                            break;
                        case "dm_designer":
                            if (strpos(strtolower($attrLabel), 'designer') === FALSE) {
                                $pageTitle = ucfirst(str_replace(' rings', '', strtolower($attrLabel))) . ' ' . $pageTitle;
                            } else if (strtolower($attrLabel) == 'all designer') {
                                $pageTitle = 'Designer' . ' ' . $pageTitle;
                            }
                            break;
                    }
                }
            } 
            else if (count($params) == 2) {
                $pageTitle = "Engagement Rings";

                if (isset($params["dm_stone_shape"]) && isset($params["dm_stone_type"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    if ($attrLabel1 == "Heart") {
                        $pageTitle = $attrLabel1 . " Shape " . $attrLabel2 . " Diamond " . $pageTitle;
                    } else {
                        $pageTitle = $attrLabel1 . " cut " . $attrLabel2 . " Diamond " . $pageTitle;
                    }
                } else if (isset($params["dm_stone_shape"]) && isset($params["dm_setting_style"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    if ($attrLabel1 == "Heart") {
                        $pageTitle = $attrLabel1 . " Shape " . $attrLabel2 . " " . $pageTitle;
                    } else {
                        $pageTitle = $attrLabel1 . " cut " . $attrLabel2 . " " . $pageTitle;
                    }
                } else if (isset($params["dm_stone_shape"]) && isset($params["dm_design_collection"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    if ($attrLabel1 == "Heart") {
                        $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Shape " . $pageTitle;
                    } else {
                        $pageTitle = $attrLabel2 . " " . $attrLabel1 . " cut " . $pageTitle;
                    }
                } else if (isset($params["dm_stone_shape"]) && isset($params["dm_metal"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    if ($attrLabel1 == "Heart") {
                        $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Shape " . $pageTitle;
                    } else {
                        $pageTitle = $attrLabel2 . " " . $attrLabel1 . " cut " . $pageTitle;
                    }
                } else if (isset($params["dm_stone_shape"]) && isset($params["dm_band"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    if ($attrLabel1 == "Heart") {
                        $pageTitle = $attrLabel1 . " Shape Bridal Wedduing Ring Sets";
                    } else {
                        $pageTitle = $attrLabel1 . " cut Bridal Wedduing Ring Sets";
                    }
                } else if (isset($params["dm_stone_type"]) && isset($params["dm_design_collection"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                } else if (isset($params["dm_stone_type"]) && isset($params["dm_setting_style"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                } else if (isset($params["dm_stone_type"]) && isset($params["dm_metal"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                } else if (isset($params["dm_stone_type"]) && isset($params["dm_band"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    $pageTitle = $attrLabel1 . " Diamond Bridal Wedding Ring Sets";
                } else if (isset($params["dm_setting_style"]) && isset($params["dm_design_collection"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                } else if (isset($params["dm_setting_style"]) && isset($params["dm_metal"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                } else if (isset($params["dm_setting_style"]) && isset($params["dm_band"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                } else if (isset($params["dm_metal"]) && isset($params["dm_band"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                } else if (isset($params["dm_metal"]) && isset($params["dm_design_collection"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    $attrLabel2 = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                } else if (isset($params["dm_band"]) && isset($params["dm_design_collection"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                } else if (isset($params["dm_designer"])) {
                    $attrLabel1 = $eavConfig->getAttribute('catalog_product', "dm_designer")->getSource()->getOptionText($params["dm_designer"]);
                    if (strpos(strtolower($attrLabel1), 'designer') === FALSE) {
                        $attrLabel1 = ucfirst(str_replace(' rings', '', strtolower($attrLabel1)));
                    } else if (strtolower($attrLabel1) == 'all designer') {
                        $attrLabel1 = 'Designer';
                    }

                    foreach ($params as $attrKey => $attrValue) {
                        if ($attrKey == 'dm_designer') {
                            continue;
                        }

                        $attr = $eavConfig->getAttribute('catalog_product', $attrKey);
                        $attrLabel = $attr->getSource()->getOptionText($attrValue);

                        switch ($attrKey) {
                            case "dm_stone_shape":
                                if ($attrLabel == "Heart") {
                                    $pageTitle = "Heart Shape " . $pageTitle;
                                } else {
                                    $pageTitle = $attrLabel . " cut " . $pageTitle;
                                }
                                break;
                            case "dm_stone_type":
                                if ($attrLabel == "Diamond") {
                                    $pageTitle = "Diamond " . $pageTitle;
                                } else if ($attrLabel == "Setting") {
                                    $pageTitle = "Engagement Ring Settings";
                                } else {
                                    $pageTitle = $attrLabel . " Diamond " . $pageTitle;
                                }
                                break;
                            case "dm_setting_style":
                            case "dm_design_collection":
                            case "dm_metal":
                                $pageTitle = $attrLabel . " " . $pageTitle;
                                break;
                            case "dm_band":
                                if ($attrLabel == "Bridal Set") {
                                    $pageTitle = "Bridal Wedding Ring Sets";
                                }
                                break;
                        }
                    }

                    $pageTitle = $attrLabel1 . " " . $pageTitle;
                }
            } 
            else {
                
                $pageTitle = "";
                
                if (isset($params["dm_designer"])) {
                    $attr = $eavConfig->getAttribute('catalog_product', "dm_designer");
                    $attrLabel = $attr->getSource()->getOptionText($params["dm_designer"]);
                    if (strpos(strtolower($attrLabel), 'designer') === FALSE) {
                        $pageTitle = ucfirst(str_replace(' rings', '', strtolower($attrLabel))) . ' ' . $pageTitle;
                    } else if (strtolower($attrLabel) == 'all designer') {
                        $pageTitle = 'Designer' . ' ' . $pageTitle;
                    }
                }
                
                if (isset($params["dm_design_collection"])) {
                    $attrLabel = $eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                    $pageTitle .= $attrLabel;
                }
                
                if (isset($params["dm_stone_shape"])) {
                    $attrLabel = $eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                    $pageTitle .= " " . $attrLabel;
                    if ($attrLabel == "Heart") {
                        $pageTitle .= " Shape";
                    } else {
                        $pageTitle .= " Cut";
                    }
                }
                
                if (isset($params["dm_setting_style"])) {
                    $attrLabel = $eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                    $pageTitle .= " " . $attrLabel;
                }
                
                if (isset($params["dm_stone_type"])) {
                    $attrLabel = $eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                    if ($attrLabel == "Setting") {
                        $pageTitle .= " Engagement Ring Settings";
                    } else {
                        if ($attrLabel != "Diamond") {
                            $pageTitle .= " " . $attrLabel;
                        }
                        
                        $pageTitle .= " Diamond";
                    }
                    
                    $cdtLabel = $attrLabel;
                }
                
                if (!isset($params["dm_stone_type"]) || $cdtLabel != "Setting") {
                    if (isset($params["dm_band"]) && $eavConfig->getAttribute('catalog_product', "dm_band")->getSource()->getOptionText($params["dm_band"]) == "Bridal Set") {
                        $pageTitle .= " Bridal Wedding Ring Set";
                    } else {
                        $pageTitle .= " Engagement Rings";
                    }
                }
                
                if (isset($params["dm_metal"])) {
                    $attrLabel = $eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                    $pageTitle .= " - " . $attrLabel;
                }
                
                $pageTitle = trim($pageTitle);
                
            }
            
            $pageTitle = str_replace("Diamond Diamond ", "Diamond ", $pageTitle);
            $pageTitle = str_replace("Setting Diamond Engagement Rings", "Engagement Ring Settings", $pageTitle);
            $pageTitle = str_replace("Setting Diamond ", "", $pageTitle);
        }

        return $pageTitle;
    }
}