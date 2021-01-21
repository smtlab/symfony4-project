# Load Balancer

Given N Servers, in 5 minutes interval, every minute, the load is checked and if the load is less than 50%, the servers are either reduced to N/2 else they are increased to 2N + 1. We want the application to be command line and designed in a way that in future if instead of 5 minutes, owner wants different no. of minutes it should be simply configurable.

Input : No. of Servers (N), server load every minute
Output : The number of servers running at the end of 5 minutes.

# Installation
```
git clone git@git.easternenterprise.com:php/symfony-symfony-test-ecommerce.git

cd symfony-servers-running

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
2. Tag your payment provider with name `app.payment_provider` and a unique key
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