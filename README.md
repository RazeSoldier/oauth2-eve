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
$provider = new Killmails\OAuth2\Client\Provider\EveOnline([
    'clientId'          => '{eve-client-id}',
    'clientSecret'      => '{eve-client-key}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
```
### Refreshing a Token

Once your application is authorized, you can refresh an expired token using a refresh token rather than going through the entire process of obtaining a brand new token. To do so, simply reuse this refresh token from your data store to request a refresh.

```php
$provider = new Killmails\OAuth2\Client\Provider\EveOnline([
    'clientId'          => '{eve-client-id}',
    'clientSecret'      => '{eve-client-key}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

$existingAccessToken = getAccessTokenFromYourDataStore();

if ($existingAccessToken->hasExpired()) {
    $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
    ]);

    // Purge old access token and store new access token to your data store.
}
```

### Managing Scopes

When creating your EVE Online authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['esi-killmails.read_killmails.v1', 'esi-killmails.read_corporation_killmails.v1']
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```
If neither are defined, the provider will utilize internal defaults.

Use the [ESI documentation](https://esi.evetech.net/ui/) to find the full list of scopes available.

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
