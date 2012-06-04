<?php

include_once "Mage/Customer/controllers/AccountController.php";

class Vasco_Mydigipass_AccountController extends Mage_Customer_AccountController
{

//    public function loginPostAction()
//    {
//
//        $login = $this->getRequest()->getPost('login');
//        $customer = Mage::getModel('customer/customer');
//        $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
//        $customer->loadByEmail($login['username']);
//        $digipassUuid = $customer->getDigipassUuid();
//        if (isset($digipassUuid) && $customer->getDigipassUuid() == $login['mydigipass']) { // User is linked! No standard login!
//            $this->_getSession()->addError($this->__('Invalid login or password. Please try again or use MyDIGIPASS to log in.'));
//            $this->_loginPostRedirect();
//        } else {// unlinked user! linking after successful login
//            parent::loginPostAction();
//            $this->_getSession()->setMydigipass($login['mydigipass']);
//            if ($this->_getSession()->isLoggedIn()) {
//                Mage::getModel('mydigipass/digipass')->linkUser($login['mydigipass'], $customer);
//            }
//        }
//    }

    public function createAction()
    {
        parent::createAction();
    }

//    public function createPostAction()
//    {
//        $post = $this->getRequest()->getPost();
//
//        if (isset($post['digipassUuid'])) {
//            $newPassword = $this->createRandomPassword(15);
//            $post['password'] = $newPassword;
//            $post['confirmation'] = $newPassword;
//            $this->getRequest()->setPost($post);
//        }
//        parent::createPostAction();
//
//        if (isset($post['digipassUuid'])) {
//            $customer = Mage::getModel('customer/customer');
//            $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
//            $customer->loadByEmail($post['email']);
//            $customer->setDigipassUuid($post['digipassUuid']);
//            $customer->save();
//        }
//    }

    private function createRandomPassword($length)
    {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i < $length)
        {
            $num = rand() % strlen($chars) - 1;
            $tmp = substr($chars, $num, 1);
            $tmp = (!is_numeric($tmp) && $num == 0) ? strtoupper($tmp) : $tmp;
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }

}