<?php

namespace DataLinx\SqualoMail\Requests;

use DataLinx\SqualoMail\API;
use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;

/**
 * Abstract request class that all requests should extend
 */
abstract class AbstractRequest implements RequestInterface {

    /**
     * API instance
     *
     * @var API
     */
    protected $api;

    /**
     * Create request object
     *
     * @param API $api
     */
    public function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * Send request via API
     *
	 * @param bool $post Make a POST request with a JSON body
     * @return array
     * @throws APIException
     * @throws ValidationException
     */
    protected function sendRequest(bool $post = false)
    {
        $this->api->validate();
        $this->validate();

        $data = [
            'apiKey' => $this->api->api_key,
        ] + $this->getData();

        return $this->api->sendRequest($this->getEndpoint(), $data, $post);
    }

    /**
     * Read object attributes with optional mapping
     *
     * @param array $attrs
     * @return array
     */
    protected function readAttributes($attrs)
    {
        $data = [];

        foreach ($attrs as $key => $value) {
            $attr_name = is_string($key) ? $key : $value;

            if (isset($this->$attr_name)) {
                $data[$value] = $this->$attr_name;
            }
        }

        return $data;
    }

    /**
     * Make sure all the provided attributes are set
     *
     * @param array $attrs
     * @return void
     * @throws ValidationException
     */
    protected function validateAttributes(array $attrs)
    {
        foreach ($attrs as $attr) {
            if (empty($this->$attr)) {
                throw new ValidationException($attr, ValidationException::CODE_ATTR_REQUIRED);
            }
        }
    }
}