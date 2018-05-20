# EVE Online Provider for OAuth 2.0 Client
[![Source Code](http://img.shields.io/badge/source-killmails/oauth2--eve-blue.svg?style=flat-square)](https://github.com/killmails/oauth2-eve)
[![Latest Version](https://img.shields.io/github/release/killmails/oauth2-eve.svg?style=flat-square)](https://github.com/killmails/oauth2-eve/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/killmails/oauth2-eve/master.svg?style=flat-square)](https://travis-ci.org/killmails/oauth2-eve)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/killmails/oauth2-eve.svg?style=flat-square)](https://scrutinizer-ci.com/g/killmails/oauth2-eve/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/killmails/oauth2-eve.svg?style=flat-square)](https://scrutinizer-ci.com/g/killmails/oauth2-eve)
[![Total Downloads](https://img.shields.io/packagist/dt/killmails/oauth2-eve.svg?style=flat-square)](https://packagist.org/packages/killmails/oauth2-eve)

This package provides [EVE Online](https://developers.eveonline.com) OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require killmails/oauth2-eve
```

## Usage

Usage is the same as The League's OAuth client, using `\Killmails\OAuth2\Client\Provider\EveOnline` as the provider.

### Authorization Code Flow

```php

  // TODO

```

### Managing Scopes

When creating your EVE Online authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => // TODO
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```
If neither are defined, the provider will utilize internal defaults.

At the time of authoring this documentation, the [following scopes are available](https://esi.evetech.net/ui/).

- `// TODO`

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/killmails/oauth2-eve/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Oizys](https://github.com/syzio)
- [All Contributors](https://github.com/killmails/oauth2-eve/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/killmails/oauth2-eve/blob/master/LICENSE) for more information.
