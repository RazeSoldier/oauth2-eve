<?php

namespace DtsEve\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class EveOnline extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string key used in a token response to identify the resource owner
     */
    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'CharacterID';

    /**
     * @var string URL path for autorization
     */
    const PATH_AUTHORIZE = '/v2/oauth/authorize';

    /**
     * @var string URL path for token
     */
    const PATH_TOKEN = '/v2/oauth/token';

    /**
     * @var string Scope separator
     */
    const SCOPE_SEPARATOR = ' ';

    /**
     * Domain.
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
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . self::PATH_TOKEN;
    }

    /**
     * @param AccessToken $token
     * @return ResourceOwnerInterface|mixed
     */
    public function getResourceOwner(AccessToken $token)
    {
        $jwtexplode=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.',$token )[1]))));
        $charactername=$jwtexplode->name;
        $characterid=explode(":",$jwtexplode->sub)[2];

        $response['CharacterName']=$charactername;
        $response['CharacterID']=$characterid;
        $response['CharacterOwnerHash']=$jwtexplode->owner;
        $response['ExpiresOn']=date('Y-m-d\TH:i:s',$jwtexplode->exp);
        $response['Scopes']=implode(" ",$jwtexplode->scp);

        return $this->createResourceOwner($response, $token);
    }

    /**
     * Get provider url to fetch user details.
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return null;
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
     * @param ResponseInterface $response
     * @param array|string      $data     Parsed response data
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
     * @param array       $response
     * @param AccessToken $token
     *
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new EveOnlineResourceOwner($response);
    }
}
