<?php

class Vasco_Mydigipass_Model_Source_Button_Style
{

    public function toOptionArray() {
        return array(
            array('value' => 'default', 'label' => Mage::helper('mydigipass')->__('Default')),
            array('value' => 'default-help', 'label' => Mage::helper('mydigipass')->__('Default with help')),
            array('value' => 'large', 'label' => Mage::helper('mydigipass')->__('Large')),
            array('value' => 'large-help', 'label' => Mage::helper('mydigipass')->__('Large with help')),
            array('value' => 'medium', 'label' => Mage::helper('mydigipass')->__('Medium')),
            array('value' => 'medium-help', 'label' => Mage::helper('mydigipass')->__('Medium with help')),
            array('value' => 'small', 'label' => Mage::helper('mydigipass')->__('Small')),
            array('value' => 'small-help', 'label' => Mage::helper('mydigipass')->__('Small with help')),
			array('value' => 'false', 'label' => Mage::helper('mydigipass')->__('Custom Styling')),
        );
    }

}