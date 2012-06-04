<?php
class Vasco_Mydigipass_Model_Source_Button
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'large', 'label'=>Mage::helper('mydigipass')->__('Large')),
            array('value'=>'small', 'label'=>Mage::helper('mydigipass')->__('Small')),                     
        );
    }

}