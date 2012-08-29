<?php
class Vasco_Mydigipass_Model_Source_Localauth
{
    public function toOptionArray()
    {
        return array(
            array('value'=>0, 'label'=>Mage::helper('mydigipass')->__('Enable')),
            array('value'=>1, 'label'=>Mage::helper('mydigipass')->__('Disable')),                     
        );
    }

}