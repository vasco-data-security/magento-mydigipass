<?php

class Vasco_Mydigipass_Model_Digipass extends Mage_Core_Model_Abstract
{
    protected $_digipassHelper;

    public function _construct() {
        parent::_construct();
        $this->_init('mydigipass/digipass');
    }

    public function digipassHelper() {
        if (is_null($this->_digipassHelper)) {
            $this->_digipassHelper = Mage::helper('mydigipass');
        }
        return $this->_digipassHelper;
    }

    public function loginUser($code) {
        $result = '';
        $connector = Mage::getModel('mydigipass/digipassConnector');
        $session = Mage::getSingleton('customer/session');

        try {
            $userData = $connector->retrieveUserData($code);
            if (isset($userData['email']) && isset($userData['uuid'])) {
                $collection = Mage::getModel('customer/customer')
                        ->getCollection()
                        ->addAttributeToFilter('digipass_uuid', $userData['uuid']);
                if (count($collection) == 1) { // linked user
                    foreach ($collection as $customer) {
                        if (!Mage::getConfig('customer/account_share/scope') || $customer->getWebsiteId() == Mage::app()->getStore()->getWebsiteId()) {
                            $session->setCustomerAsLoggedIn($customer);
                            $result = 'linked';
                        } else { // accounts not shared, so register on the current store
                            $result = $this->formatUserData('new', $userData);
                            $session->setUserdata($result);
                        }
                    }
                } else {
                    $customer = Mage::getModel('customer/customer');
                    $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
                    $customer->loadByEmail($userData['email']);
                    if ($customer->getEmail() == $userData['email']) { // unlinked user -> provide Magento password
                        $result = $this->formatUserData('unlinked', $userData);
                        $session->setUserdata($result);
                    } else { // new user -> register form
                        if ($this->digipassHelper()->getAutoAccount()) {
                            $userDataFormatted = $this->formatUserData('new', $userData);
                            unset($userDataFormatted['type']);
                            $customer = $this->createUser($userDataFormatted);
                            $session->setCustomerAsLoggedIn($customer);
                            $result = 'linked';
                        } else {
                            $result = $this->formatUserData('new', $userData);
                            Mage::log($result);
                            $session->setUserdata($result);
                        }
                    }
                }
            } else {
                throw new Exception('Data received from MyDIGIPASS service is invalid');
            }
        } catch (Exception $e) {
            Mage::log('MyDIGIPASS: ' . $e->getMessage(), Zend_Log::ERR);
            Mage::getSingleton('core/session')->addError(Mage::helper('mydigipass')->__('The MyDIGIPASS service is currently unavailable, we apologize for the inconvenience.'));
        }

        return $result;
    }

    public function linkUser($digipassUuid, $customer) {
        $customer->setDigipassUuid($digipassUuid);
        $customer->save();
    }

    public function createUser($userData) {
        $customer = Mage::getModel('customer/customer');
        $customerFields = array('firstname', 'lastname', 'email', 'dob');
        foreach ($customerFields as $field) {
            if (isset($userData[$field])) {
                $methodName = 'set' . ucfirst($field);
                $customer->$methodName($userData[$field]);
            }
        }
        Mage::log($userData);
        $customer->setDigipassUuid($userData['mydigipass']);
        unset($userData['email']);
        unset($userData['dob']);
        unset($userData['mydigipass']);

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
        return $customer;
    }

    public function unlinkUser($customer) {
        $storeId = Mage::app()->getStore()->getId();
        $customer->setDigipassUuid(null)->save();

        if ($customer->getId()) {
            try {
                $newPassword = $customer->generatePassword();
                $customer->changePassword($newPassword, false);
                $customer->sendPasswordReminderEmail();
                Mage::app()->setCurrentStore($storeId);
            } catch (Exception $exception) {
                $this->_getSession()->addError($exception->getMessage());
                $this->_redirect('*/*/forgotpassword');
            }
        }
    }

    public function updateProfile($customer) {
        
    }

    public function getDirectLoginRedirect($customer) {
        $redirect = array();
        $orders = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('customer_id', $customer->getId());
        // get storeid of last order, if no order, then store id of registration
        if ($orders != null) {
            $orderArray = array('date' => '');
            foreach ($orders as $order) {
                if ($orderArray['date'] < $order->getCreatedAt()) {
                    $orderArray['date'] = $order->getCreatedAt();
                    $orderArray['store'] = $order->getStoreId();
                }
            }
            $redirect['store'] = $orderArray['store'];
            $redirect['url'] = Mage::app()->getStore($orderArray['store'])->getUrl();
        } else {
            $redirect['store'] = $customer->getStoreId();
            $redirect['url'] = Mage::app()->getStore($redirect['store'])->getUrl();
        }

        return $redirect;
    }

    private function formatUserData($type, $userData) {
        $mapping = array(
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'born_on' => 'dob',
            'email' => 'email',
            'uuid' => 'mydigipass',
            'city' => 'city',
            'state' => 'state',
            'country' => 'country_id',
            'address_1' => 'street',
            'address_2' => 'street',
            'zip' => 'postcode',
            'phone_number' => 'telephone',
        );
        $fieldConfig = $this->digipassHelper()->getFieldConfig();

        $result = array('type' => $type);
        foreach ($userData as $key => $value) {
            Mage::log($key . ' => ' . $mapping[$key]);
            if (!empty($fieldConfig[$mapping[$key]])) {
                if ($mapping[$key] == 'street') {
                    $result[$mapping[$key]][] = $value;
                } else if ($mapping[$key] == 'country_id') {
                    $result[$mapping[$key]] = $this->getCountryCode($userData['country']);
                } else {
                    $result[$mapping[$key]] = $value;
                }
            }
        }

        Mage::log($result);
        return $result;
    }

    private function getCountryCode($country) {
        $countries = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);

        foreach ($countries as $key => $value) {
            if ($value['label'] == $country) {
                return $value['value'];
            }
        }
        return $country;
    }

}