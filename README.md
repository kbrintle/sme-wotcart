#  WOTCart


## Table of Contents
* [Getting Started](#getting-started)
* [Asset Bundler](#asset-bundler)
* [WOTCart](#wotcart)



### Getting Started
* Pull repository from Assembla and checkout `development` branch
* Create local vhost by editing `/etc/apache2/extra/httpd-vhosts.conf` (replace `/Users/patrickeason/Projects` with wherever your repo is located):
```
##
# WOT - Cart 2016
##
<VirtualHost *:80>
  ServerName am-cart.dev
  DocumentRoot /Users/patrickeason/Projects/wideopen^americas-mattress.2/frontend/web

  <Directory "/Users/patrickeason/Projects/wideopen^americas-mattress.2/frontend/web">
       RewriteEngine on
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d

       RewriteRule . index.php
       DirectoryIndex index.php
       
       Options Indexes FollowSymLinks MultiViews
       AllowOverride all
       Order allow,deny
       Allow from all
   </Directory>
</VirtualHost>
```
* Update your hosts file:
```
echo '' | sudo tee -a /etc/hosts; echo '127.0.0.1 am-cart.dev' | sudo tee -a /etc/hosts
```
* Initialize Yii by running Composer:
```
composer update
composer install
```
* Update config for connecting to remote database in `common/config/main-local.php`:
```php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=104.236.11.18;dbname=am_wotcart_dev',
            'username' => 'am_dev_usr',
            'password' => 'w1d30p3n!',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
```
* Create symlink for Admin Dashboard (replace `~/Projects` with wherever your repo is located): 
```
ln -s ~/Projects/wideopen^americas-mattress.2/backend/web ~/Projects/wideopen^americas-mattress.2/frontend/web/admin
```
* Restart Apache
```
sudo apachectl restart
```
* You can now access the local site using am-cart.dev



### Asset Bundler
* Install Webpack:
```
npm install webpack -g
```
* Install npm modules in both frontend and backend projects (this example will use `frontend` but both follow the same process):
```
cd ~/Projects/wideopen^americas-mattress.2/frontend/web/themes/default/_assets
npm install
```
* Start the SASS watcher:
```
cd ~/Projects/wideopen^americas-mattress.2/frontend/web/themes/default/_assets/src/scss
./watch.sh
```
* With the watcher running, run webpack:
```
cd ~/Projects/wideopen^americas-mattress.2/frontend/web/themes/default/_assets
webpack
```

_Webpack will need to be rerun whenever a change is made. The watcher will rerun Sass, but Webpack creates the dist css.



### WOTCart
The WOTCart component is a fork of HCStudio's (yii2-cart)[https://www.github.com/hscstudio/yii2-cart] but with more flexibility, and more functionality. To access the Cart object, do the same thing you would with HCS: `Yii::$app->cart`. The WOTCart component takes over and we're on our way.

In addition to the HCS Cart functions, there are some extra functions to make your job easier:

#### `Yii::$app->cart->getDisplayCost()`
Returns the current line item costs (subtotal) in English format (ie: 1,000.25)
This is also applicable for $cart_item objects from `Yii::$app->cart->getItems()`

#### `Yii::$app->cart->getSalesTax()`
Returns the sales tax for the current subtotal in float

#### `Yii::$app->cart->getDisplaySales Tax()`
Returns the sales tax for the current subtotal in English format (ie: 1,000.25)

#### `Yii::$app->cart->getShipping()`
Returns the shipping cost for the subtotal in float

#### `Yii::$app->cart->getDisplayShipping()`
Returns the shipping cost for the subtotal in English format (ie: 1,000.25)

#### `Yii::$app->cart->getTotal()`
Returns the cost + shipping + sales tax in float

#### `Yii::$app->cart->getDisplayTotal()`
Returns the cost + shipping + sales tax in English format (ie: 1,000.25)

_For integration sake, `->getCost()` is still called getCost despite returning the subtotal. I'd love to rename it getSubtotal but we need to look at ease of integration. Maybe some time in the future._



### SSO
#### `common/config/params-local.php`
```
<?php
return [
    'sso' => [
        'login'             => 'http://am-lms.dev/site/login',
        'login_redirect'    => 'http://am-lms.dev/admin',
        'logout'            => 'http://am-lms.dev/site/logout',
        'logout_redirect'   => 'http://am-lms.dev'
    ]
];```