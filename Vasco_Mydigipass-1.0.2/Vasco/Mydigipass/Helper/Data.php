<?php

class Vasco_Mydigipass_Helper_Data extends Mage_Core_helper_Abstract
{

    public function getClientId() {
        return Mage::getStoreConfig('digipass/general/client_id');
    }

    public function getClientSecret() {
        return Mage::getStoreConfig('digipass/general/client_secret');
    }

    public function getBaseUri() {
        if (Mage::getStoreConfig('digipass/general/live')) {
            return 'https://www.mydigipass.com/oauth';
        }
        return 'https://sandbox.mydigipass.com/oauth';
    }

    public function getCallBackURL() {
        return Mage::getStoreConfig('digipass/general/callback_url');
    }

    public function getButtonHtml($location = null) {
        if (Mage::getStoreConfig('digipass/general/enabled')) {
            $url = Mage::helper('core/url')->getCurrentUrl();
            $storeCode = Mage::app()->getStore()->getCode();

            return '<a class="dpplus-connect" ' . $this->getButtonStyle($location) . ' data-client-id="' . $this->getClientId() . '" data-redirect-uri="' . $this->getCallBackURL() . '" data-state="' . $storeCode . ':' . $url . '" href="#">Connect with MYDIGIPASS.COM</a>';
            break;
        } else {
            return '';
        }
    }

    private function getButtonStyle($location) {
        if ($location != null) {
            $styleConfig = Mage::getStoreConfig("digipass/button_style/{$location}");
            $text = Mage::getStoreConfig("digipass/button_style/{$location}_text");
        } else {
            $styleConfig = 'default';
            $text = 'connect';
        }
        $style_help = explode('-', $styleConfig);
        $style = $style_help[0];
        $help = (isset($style_help[1])) ? 'true' : 'false';
        
        return 'data-style="' . $style . '" data-help="' . $help .'" data-text="' . $text . '"';
    }

    public function getButtonJs() {
        if (Mage::getStoreConfig('digipass/general/enabled')) {
            if (Mage::getStoreConfig('digipass/general/live')) {
                return '<script type="text/javascript" src="https://www.mydigipass.com/dp_connect.js"></script>';
            }
            return '<script type="text/javascript" src="https://sandbox.mydigipass.com/dp_connect.js"></script>';
        } else {
            return '';
        }
    }

    public function getLocalAuthAllowed() {
        return !Mage::getStoreConfig('digipass/general/local_auth');
    }

    public function getAutoAccount() {
        return Mage::getStoreConfig('digipass/general/auto_account');
    }

    public function getFieldConfig() {
        $config = array();
        $config['firstname'] = Mage::getStoreConfig('digipass/profile_fields/firstname');
        $config['lastname'] = Mage::getStoreConfig('digipass/profile_fields/lastname');
        $config['dob'] = Mage::getStoreConfig('digipass/profile_fields/dob');
        $config['email'] = (Mage::getStoreConfig('digipass/profile_fields/email')) ? Mage::getStoreConfig('digipass/profile_fields/email') : 1;
        $config['city'] = Mage::getStoreConfig('digipass/profile_fields/city');
        $config['postcode'] = Mage::getStoreConfig('digipass/profile_fields/zip');
        $config['country_id'] = Mage::getStoreConfig('digipass/profile_fields/country');
        $config['street'] = Mage::getStoreConfig('digipass/profile_fields/address');
        $config['telephone'] = Mage::getStoreConfig('digipass/profile_fields/telephone');
        $config['mydigipass'] = 1;

        return $config;
    }

}
