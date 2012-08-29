<?php

class Vasco_Mydigipass_Model_DigipassConnector extends Mage_Core_Model_Abstract
{
    protected $_digipassHelper;
    protected $_digipassAuth;

    public function _construct() {
        parent::_construct();
        $this->_init('mydigipass/connector');
    }

    public function digipassHelper() {
        if (is_null($this->_digipassHelper)) {
            $this->_digipassHelper = Mage::helper('mydigipass');
        }
        return $this->_digipassHelper;
    }

    public function digipassAuth($code) {
        if (is_null($this->_digipassAuth)) {
            $client = new Vasco_Mydigipass_Model_OAuth2_Client(
                            $this->digipassHelper()->getClientId(),
                            $this->digipassHelper()->getClientSecret(),
                            $this->digipassHelper()->getCallbackURL()
            );
            $config = new Vasco_Mydigipass_Model_OAuth2_Service_Configuration(
                            $this->digipassHelper()->getBaseUri() . '/authenticate',
                            $this->digipassHelper()->getBaseUri() . '/token');
            $dataStore = new Vasco_Mydigipass_Model_OAuth2_DataStore_Session();
            $scope = null;
            $this->_digipassAuth = new Vasco_Mydigipass_Model_OAuth2_Service($client, $config, $dataStore, $scope);
            $this->_digipassAuth->getAccessToken($code);
        }
        return $this->_digipassAuth;
    }

    public function retrieveUserData($code) {
        if (!isset($code)) {
            $this->digipassAuth()->authorize();
        }
        $response = $this->digipassAuth($code)->callApiEndpoint($this->digipassHelper()->getBaseUri() . '/user_data');
        $userData = json_decode($response, true);
        
        if ($userData) {
            return $userData;
        } else {
            throw new Exception('MyDIGIPASS Service did not return data');
        }
    }

}