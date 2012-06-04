<?php

class Vasco_Mydigipass_Model_Observer
{
    protected $_digipassHelper;

    public function digipassHelper() {
        if (is_null($this->_digipassHelper)) {
            $this->_digipassHelper = Mage::helper('mydigipass');
        }
        return $this->_digipassHelper;
    }

    /**
     * EVENT OBSERVER
     * Create post action predispatch
     * @param Mage_Customer_AccountController $observer
     */
    public function createPostActionPredispatch($observer) {
        $controllerAction = $observer['controller_action'];

        $post = Mage::app()->getRequest()->getPost();

        if (isset($post['digipassUuid'])) {
            $newPassword = Mage::getModel('customer/customer')->generatePassword();
            $post['password'] = $newPassword;
            $post['confirmation'] = $newPassword;
            Mage::app()->getRequest()->setPost($post);
        }
    }

    /**
     * EVENT OBSERVER
     * Create post action postdispatch
     * @param Mage_Customer_AccountController $observer
     */
    public function createPostActionPostdispatch($observer) {
        $controllerAction = $observer['controller_action'];

        $post = Mage::app()->getRequest()->getPost();

        if (isset($post['digipassUuid'])) {
            $userData = Mage::getSingleton('customer/session')->getUserdata();
            $customer = Mage::getModel('customer/customer');
            $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            $customer->loadByEmail($post['email']);
            $customer->setDigipassUuid($post['digipassUuid']);
            
            $keys = array_keys($userData);
            if (in_array('street', $keys) && in_array('city', $keys) && in_array('countryId', $keys)) { // more info than name?
                $address = Mage::getModel('customer/address');
                foreach ($userData as $key => $value) {
                    $methodName = 'set' . ucfirst($key);
                    $address->$methodName($value);
                }
                $address->setIsDefaultBilling('1')
                        ->setIsDefaultShipping('1');
                $customer->addAddress($address);
            }

            $customer->save();
        }
    }

    public function loginPostActionPredispatch($observer) {
        return false;
    }

    /**
     * EVENT OBSERVER
     * Login post action postdispatch
     * @param Mage_Customer_AccountController $observer
     */
    public function loginPostActionPostdispatch($observer) {
        // Link user after successful login
        $session = Mage::getSingleton('customer/session');
        $login = Mage::app()->getRequest()->getPost('login');
		$customer = $session->getCustomer();
        $digipassUuid = $customer->getDigipassUuid();
        if ($login['mydigipass'] != '') {    
            if ($digipassUuid != '') {
                if ($login['mydigipass'] != '') {
                    if ($login['mydigipass'] != $digipassUuid) { // user has revoked access via mydigipass and is trying to link
                        $session->setMydigipass(null);
                        if ($session->isLoggedIn()) {
                            Mage::getModel('mydigipass/digipass')->linkUser($login['mydigipass'], $customer);
                        }
                    } else { // Linked user trying Magento login. Deny.
                        $session->logout();
                        $session->addError($this->digipassHelper()->__('Invalid login or password. Please try again or use MyDIGIPASS to log in.'));
                    }
                } else { // Linked user trying Magento login. Deny.
                    $session->logout();
                    $session->addError($this->digipassHelper()->__('Invalid login or password. Please try again or use MyDIGIPASS to log in.'));
                }
            } else {
                // unlinked user trying to link
                $session->setMydigipass(null);
                if ($session->isLoggedIn()) {
                    Mage::getModel('mydigipass/digipass')->linkUser($login['mydigipass'], $customer);
                }
            }
        } else {
			if ($digipassUuid != '' && !$this->digipassHelper()->getLocalAuthAllowed()) {
				$session->logout();
				$session->getMessages(true); // clear other messages such as invalid login, etc...
				$session->addNotice($this->digipassHelper()->__('Local login is disabled, please use MyDIGIPASS to log in.'));
			}
        }
    }

}