<?php


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Tdi extends AbstractProvider
{
    use BearerAuthorizationTrait;
    
    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://oauth.tdi.tc';
    }
    
    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseAuthorizationUrl().'/token';
    }
    
    /**
     * Returns the URL for requesting the resource owner's details.
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
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }
    
    /**
     * Checks a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array|string $data Parsed response data
     *
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if(isset($data['error'])) {
            $statusCode = $response->getStatusCode();
            [$error, 'error_description' => $errorDescription] = $data;
            
            throw new \League\OAuth2\Client\Provider\Exception\IdentityProviderException(
                sprintf("%s - %s : %s", $statusCode, $errorDescription, $error),
                $response->getStatusCode(),
                $response
            );
            
        }
    }
    
    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new TdiResourceOwner($response);
    }
}