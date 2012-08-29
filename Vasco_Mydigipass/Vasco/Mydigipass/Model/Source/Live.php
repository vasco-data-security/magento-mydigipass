<?php
class Vasco_Mydigipass_Model_Source_Live
{
    public function toOptionArray()
    {
        return array(
            array('value'=>0, 'label'=>Mage::helper('mydigipass')->__('Sandbox')),
            array('value'=>1, 'label'=>Mage::helper('mydigipass')->__('Live')),                     
        );
    }

}