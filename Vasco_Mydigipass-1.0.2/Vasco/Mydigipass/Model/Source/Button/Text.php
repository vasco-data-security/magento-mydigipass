<?php
class Vasco_Mydigipass_Model_Source_Button_Text
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'connect', 'label'=>Mage::helper('mydigipass')->__('Connect')),
            array('value'=>'sign-up', 'label'=>Mage::helper('mydigipass')->__('Sign Up')),
            array('value'=>'secure-login', 'label'=>Mage::helper('mydigipass')->__('Secure Login')),
        );
    }

}