<?php

class Vasco_Mydigipass_Block_Customer_Address_Edit extends Mage_Customer_Block_Address_Edit {
    
    public function getCountryHtmlSelect($defValue=null, $name='country_id', $id='country', $title='Country')
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (is_null($defValue)) {
            $defValue = $this->getCountryId();
        }
        $cacheKey = 'DIRECTORY_COUNTRY_SELECT_STORE_'.Mage::app()->getStore()->getCode();
        if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey)) {
            $options = unserialize($cache);
        } else {
            $options = $this->getCountryCollection()->toOptionArray();
            if (Mage::app()->useCache('config')) {
                Mage::app()->saveCache(serialize($options), $cacheKey, array('config'));
            }
        }
        
        
        $fieldConfig = Mage::helper('mydigipass')->getFieldConfig();
        $disabled = ($fieldConfig['countryId'] == 2 && $this->isDefaultBilling()) ? 'disabled="disabled"' : '';
        
        $html = $this->getLayout()->createBlock('core/html_select')
            ->setName($name)
            ->setId($id)
            ->setTitle(Mage::helper('directory')->__($title))
            ->setClass('validate-select')
            ->setValue($defValue)
            ->setOptions($options)
            ->setExtraParams($disabled)
            ->getHtml();

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $html;
    }
    
}
?>
