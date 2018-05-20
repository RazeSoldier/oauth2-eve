<?php

namespace Killmails\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class EveOnline extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string Key used in a token response to identify the resource owner.
     */
    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'CharacterID';

    /**
     * @var string URL path for autorization.
     */
    const PATH_AUTHORIZE = '/oauth/authorize';


    /**
     * @var string URL path for token.
     */
    const PATH_TOKEN = '/oauth/token';


    /**
     * @var string URL path for user details.
     */
    const PATH_USER = '/oauth/verify';


    /**
     * @var string Scope separator
     */
    const SCOPE_SEPARATOR = ' ';

    /**
     * Domain
     *
     * @var string
     */
    protected $domain = 'https://login.eveonline.com';

    /**
     * Get authorization url to begin OAuth flow.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . self::PATH_AUTHORIZE;
    }

    /**
     * Get access token url to retrieve token.
     *
     * @param  array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . self::PATH_TOKEN;
    }

    /**
     * Get provider url to fetch user details.
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain . self::PATH_USER;
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator
     */
    protected function getScopeSeparator()
    {
        return self::SCOPE_SEPARATOR;
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     *
     * @param  ResponseInterface $response
     * @param  array|string      $data Parsed response data
     *
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            throw new IdentityProviderException($data['error_description'], $response->getStatusCode(), $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param  array       $response
     * @param  AccessToken $token
     *
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new EveOnlineResourceOwner($response);
    }
}
