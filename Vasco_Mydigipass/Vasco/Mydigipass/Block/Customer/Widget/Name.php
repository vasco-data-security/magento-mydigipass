<?php

class Vasco_Mydigipass_Block_Customer_Widget_Name extends Mage_Customer_Block_Widget_Name
{

    public function _construct() {
        parent::_construct();
        $this->setTemplate('mydigipass/customer/widget/name.phtml');
    }

}
?>
