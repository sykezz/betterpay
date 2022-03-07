# Betterpay

[![Latest Stable Version](http://poser.pugx.org/sykez/betterpay/v)](https://packagist.org/packages/sykez/betterpay)
[![License](http://poser.pugx.org/sykez/betterpay/license)](https://packagist.org/packages/sykez/betterpay)
[![PHP Version Require](http://poser.pugx.org/sykez/betterpay/require/php)](https://packagist.org/packages/sykez/betterpay)
[![Total Downloads](http://poser.pugx.org/sykez/betterpay/downloads)](https://packagist.org/packages/sykez/betterpay)

**PHP package for [Betterpay](https://www.betterpay.me/) (previously known as QlicknPay) payment gateway solution API.**

[Register for sandbox account](https://www.betterpay.me/v2/sandbox) to start testing.


## Contents
- [Betterpay](#betterpay)
  - [Contents](#contents)
  - [Supported Features](#supported-features)
  - [To-do](#to-do)
  - [Installation](#installation)
    - [Laravel Setup](#laravel-setup)
  - [Usage](#usage)
  - [Related Resources](#related-resources)
  - [Contributing](#contributing)
  - [License](#license)


## Supported Features

- [x] Tokenization - MasterCard Payment Gateway Service (MPGS)

## To-do
- [ ] Standard Payment Gateway
- [ ] Direct Payment
- [ ] Recurring Payment - Direct Debit
- [ ] Refund

## Installation

Install package via Composer:

```
composer require sykez/betterpay
```

### Laravel Setup

Add the environment variables to your `.env`:
```
BETTERPAY_API_KEY=
BETTERPAY_MERCHANT_ID=
BETTERPAY_CALLBACK_URL=https://example.com/callback
BETTERPAY_SUCCESS_URL=https://example.com/success
BETTERPAY_FAIL_URL=https://example.com/fail
```

## Usage

**Create verification link for Tokenization:**

```
use Sykez\Betterpay\Betterpay;

$bp = new Betterpay($api_key, $merchant_id, $api_url, $callback_url, $success_url, $fail_url);
$response = $bp->createTokenizationUrl($reference_id); // unique $reference_id
header('Location: '.$response['credit_card_verification_url']); // redirect to payment gateway
```

Token is returned to your callback/success URL upon success.

**Charge card:**

```
$token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // token returned from verification
$bp->charge($token, $invoice, $amount);
```

**Laravel: Charge card:**

This package includes Laravel's Service Provider, which injects the dependencies.
```
use Sykez\Betterpay\Betterpay;

public function charge(Betterpay $bp, Request $request)
{
    $invoice = bin2hex(random_bytes(5));
    $token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $amount = 10.5;
    return $bp->charge($token, $invoice, $amount);
}
```

## Related Resources

- [Betterpay API Documentation](https://www.betterpay.me/docs/)
- [QlicknPay Plugins](https://github.com/shahrul95-dev/QlicknPay-Plugins)


## Contributing

Contributions are welcome 😄

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.