<?php
class Vasco_Mydigipass_Model_Source_Profile_Requiredforced
{
    public function toOptionArray()
    {
        return array(
            array('value'=> 1, 'label'=>Mage::helper('mydigipass')->__('Required')),
            array('value'=> 2, 'label'=>Mage::helper('mydigipass')->__('Forced')),
        );
    }

}