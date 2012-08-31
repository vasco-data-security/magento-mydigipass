#magento-mydigipass

This is the source code of the [Magento](http://www.magentocommerce.com) Plugin to add MYDIGIPASS.COM Secure two factor authentication towards your webshop.

All enhancement proposals can be submitted to our github and will be considered to become part of the official code base.

The MYDIGIPASS.COM Development team.


# Installing

The plugin can directly be installed from the Magento Connect Market place.

http://www.magentocommerce.com/magento-connect/mydigipass-com-secure-login-7270.html

If you are unsure how to install modules in Magento, this tutorial provides a detailed walk-through:

http://www.siteground.com/tutorials/magento/magento_connect.htm

To get started using the module you need to get a *client_id* and a *client_secret* from http://developer.mydigipass.com.

When you register an application, you will be asked for a callback URL. This will usually be:

https://yourdomain.com/mydigipass/digipass/callback

If you have set up multiple domains you should use the "Global website URL" configured in the Magento back-end. The module will ensure that the user is redirected back to the correct website/storeview after authentication.

Note that when you register the application on the developer site, you are using the sandbox environment.

When you are ready to go live  you can request your production environment details via the site.

# License

Copyright (c) 2012 VASCO Data Security
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
