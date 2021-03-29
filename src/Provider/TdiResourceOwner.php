<?php


class TdiResourceOwner implements \League\OAuth2\Client\Provider\ResourceOwnerInterface
{
    
    /**
     * TdiResourceOwner constructor.
     *
     * @param array $response
     */
    public function __construct(array $response) {}
    
    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return mixed
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }
    
    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}