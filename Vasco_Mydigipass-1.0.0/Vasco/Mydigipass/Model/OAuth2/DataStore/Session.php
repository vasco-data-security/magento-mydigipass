<?php
/**
 * Copyright (c) 2010 VZnet Netzwerke Ltd.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Bastian Hofmann <bhfomann@vz.net>
 * @author    Vyacheslav Slinko <vyacheslav.slinko@gmail.com>
 * @copyright 2010 VZnet Netzwerke Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */
//use OAuth2_DataStore;
//use OAuth2_Token;

class Vasco_Mydigipass_Model_OAuth2_DataStore_Session implements Vasco_Mydigipass_Model_OAuth2_DataStore
{

    /**
     *
     * @return \OAuth2\Token
     */
    public function retrieveAccessToken() {
        $session = Mage::getSingleton('core/session');
        $oauth2Token = $session->getOauth2Token();
        return isset($oauth2Token) ? $oauth2Token : new Vasco_Mydigipass_Model_OAuth2_Token();
    }

    /**
     * @param \OAuth2\Token $token
     */
    public function storeAccessToken(Vasco_Mydigipass_Model_OAuth2_Token $token) {
        $session = Mage::getSingleton('core/session');
        $session->setOauth2Token($token);
    }

    public function  __destruct() {
        $session = Mage::getSingleton('core/session');
        $session->setOauth2Token('');
    }
}
