<?php
class Vasco_Mydigipass_Model_Source_Profile
{
    public function toOptionArray()
    {
        return array(
            array('value'=> 0, 'label'=>Mage::helper('mydigipass')->__('No Synchronisation')),
            array('value'=> 1, 'label'=>Mage::helper('mydigipass')->__('Required')),
            array('value'=> 2, 'label'=>Mage::helper('mydigipass')->__('Forced')),
        );
    }

}