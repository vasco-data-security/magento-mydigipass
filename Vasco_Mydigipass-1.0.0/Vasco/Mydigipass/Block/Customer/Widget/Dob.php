<?php

class Vasco_Mydigipass_Block_Customer_Widget_Dob extends Mage_Customer_Block_Widget_Dob
{

    public function _construct() {
        parent::_construct();
        $this->setTemplate('mydigipass/customer/widget/dob.phtml');
    }

}
?>
