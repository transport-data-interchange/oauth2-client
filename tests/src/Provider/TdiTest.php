<?php


namespace TDi\OAuth2\Client\Test\Provider;


use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use TDi\OAuth2\Client\Provider\Tdi;
use Mockery as m;


class TdiTest extends TestCase
{
    protected Tdi $provider;
    
    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('response_type', $query);
    }
    
    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        
        $this->assertEquals('/token', $uri['path']);
    }
    
    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getHeader')
                 ->times(1)
                 ->andReturn('application/json');
        $response->shouldReceive('getBody')
                 ->times(1)
                 ->andReturn('{"access_token":"mock_access_token", "token_type":"bearer", "expires_in":3600, "refresh_token":"mock_refresh_token"}');
        
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);
        
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
    }
    
    public function testClientCredentials()
    {
        $token = $this->provider->getAccessToken('client_credentials', []);
        
        $this->assertIsObject($token, AccessToken::class);
        $this->assertEquals(null, $token->getResourceOwnerId());
    }
    
    protected function setUp(): void
    {
        $this->provider = new Tdi(
            [
                'clientId'     => 'vc-internal',
                'clientSecret' => '12345',
                'redirectUri'  => 'mock_redirect_uri',
            ]
        );
    }
}