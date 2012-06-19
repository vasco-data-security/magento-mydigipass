<?php

class Vasco_Mydigipass_Digipasscontroller extends Mage_Core_Controller_Front_Action
{
    protected $_digipassHelper;

    public function digipassHelper() {
        if (is_null($this->_digipassHelper)) {
            $this->_digipassHelper = Mage::helper('mydigipass');
        }
        return $this->_digipassHelper;
    }

    public function callbackAction() {
        $digipass = Mage::getModel('mydigipass/digipass');
        $state = $this->getRequest()->getParam('state');
        $storeCode = '';

        if (isset($state)) {
            $storeCode = substr($state, 0, strpos($state, ':'));
            $stateUrl = substr($state, strpos($state, ':') + 1);
        }

        if (Mage::app()->getStore($storeCode)->getCode() == Mage::app()->getStore()->getCode()) {
            $result = $digipass->loginUser($this->getRequest()->getParam('code'));
            $session = Mage::getSingleton('customer/session');

            if (!isset($state)) {
                $directLoginRedirect = $digipass->getDirectLoginRedirect($session->getCustomer());
                $storeCode = $directLoginRedirect['store'];
                $stateUrl = $directLoginRedirect['url'];
            }

            if (is_array($result)) {
                if ($result['type'] == 'new') {
                    unset($result['type']);
                    $session->setCustomerFormData($result);
                    $this->getResponse()->setRedirect(Mage::getUrl('customer/account/create'));
                } else if (!Mage::helper('customer')->isLoggedIn()) { //unlinked, logged out user
                    $session->addNotice($this->digipassHelper()->__('You already have an account on this website linked to the e-mail address configured in your MyDIGIPASS account. Please log in below so your account can be linked to your MyDIGIPASS account.'));
                    $session->setUsername($result['email']);
                    $session->setMydigipass($result['mydigipass']);
                    $this->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
                } else { // logged in user trying to link
                    $digipass->linkUser($result['mydigipass'], $session->getCustomer());
                    $this->getResponse()->setRedirect($stateUrl);
                }
            } else {
                echo '<html><head><script>function redirect(){window.location = "' . $stateUrl . '";}</script></head><body onload="redirect()"><p style="text-align:center;margin:50px;font-family:Verdanan,Arial;"><img src="' . str_replace("index.php/", "", Mage::getUrl()) . 'skin/frontend/default/default/images/opc-ajax-loader.gif" alt="' . Mage::helper('mydigipass')->__('Redirecting. One moment please ...') . '"/> ' . Mage::helper('mydigipass')->__('Redirecting. One moment please ...') . '</p></body></html>';
                $this->getResponse()->setRedirect($stateUrl);
            }
        } else {
            $parsedUrl = parse_url($stateUrl);
            $parsedStoreUrl = parse_url(Mage::app()->getStore()->getUrl());
            $parsedUrl['host'] = $parsedStoreUrl;

            $params = $this->getRequest()->getParams();
            foreach ($params as $key => $value) {
                $params[$key] = htmlentities($value);
            }

            $storeUrl = Mage::app()->getStore($storeCode)->getUrl();
            $storeUrl = substr($storeUrl, 0, strpos($storeUrl, '?'));
            $url = $storeUrl . 'mydigipass/digipass/callback?' . http_build_query($params);
            echo '<html><head><script>function redirect(){window.location = "' . $stateUrl . '";}</script></head><body onload="redirect()"><p style="text-align:center;margin:50px;font-family:Verdanan,Arial;"><img src="' . str_replace("index.php/", "", Mage::getUrl()) . 'skin/frontend/default/default/images/opc-ajax-loader.gif" alt="' . Mage::helper('mydigipass')->__('Redirecting. One moment please ...') . '"/> ' . Mage::helper('mydigipass')->__('Redirecting. One moment please ...') . '</p></body></html>';
            $this->getResponse()->setRedirect($url);
        }
    }

    public function unlinkAction() {
        $session = Mage::getSingleton('customer/session');
        $digipass = Mage::getModel('mydigipass/digipass');
        $digipass->unlinkUser($session->getCustomer());

        $session->addNotice($this->digipassHelper()->__('Your account has been unlinked. An email was sent to you for your password.'));
        $this->getResponse()->setRedirect(Mage::app()->getStore()->getUrl('customer/account'));
    }

}