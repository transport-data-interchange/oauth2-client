<?php
namespace TDi\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class TdiResourceOwner implements ResourceOwnerInterface
{
    private array $response;
    use ArrayAccessorTrait;
    
    /**
     * TdiResourceOwner constructor.
     *
     * @param array $response
     */
    public function __construct(array $response) {
        $this->response = $response;
    }
    
    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'access_token');
    }
    
    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}