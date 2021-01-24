# **Basic symfony 4 ecommerce**

# Installation
```
git clone git@smtlab/symfony4-project:php/symfony-test-ecommerce.git

cd symfony-test-ecommerce.git

composer install
```

# Usage
```
php bin/console create-product Phone

php bin/console create-payment paypal Phone
```

# testing

`./bin/phpunit`

# Add your own payment provider

1. Create App\PaymentProvider\Custom.php
```
<?php
// src/PaymentProvider/Custom.php
declare(strict_types=1);
namespace App\PaymentProvider;

class Custom implements PaymentProviderInterface
{
    public function pay(): void
    {
        // @TODO call your payment gateway
    }
}

```
2. Tag your payment provider service and set an unique key
```
# config/services.yaml

services:
    App\PaymentProvider\Custom:
        tags:
            - { name: 'app.payment_provider', key: 'custom' }
```
3. Pay using custom payment privider

```
php bin/console custom Phone
```
