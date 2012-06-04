<?php
class Vasco_Mydigipass_Model_Source_Button_Help
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'false', 'label'=>Mage::helper('mydigipass')->__('No')),
            array('value'=>'true', 'label'=>Mage::helper('mydigipass')->__('Yes')),
        );
    }

}