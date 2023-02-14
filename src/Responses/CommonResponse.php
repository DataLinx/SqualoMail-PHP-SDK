<?php

namespace DataLinx\SqualoMail\Responses;

use DataLinx\SqualoMail\Requests\RequestInterface;

/**
 * A common response, possibly providing some data and/or error details
 */
class CommonResponse implements ResponseInterface
{
    /**
     * @var array Data as received by the API
     */
    protected array $data;

    /**
     * @var RequestInterface Original request that was sent to the API
     */
    protected RequestInterface $request;

    /**
     * @param array $data Response data
     * @param RequestInterface $request Original request
     */
    public function __construct(array $data, RequestInterface $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    /**
     * Get internal data array
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Get associated request that was sent to the API
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Get response data parameter
     *
     * @param string $name Parameter name
     * @return mixed|null
     */
    public function getParameter(string $name)
    {
        return $this->getData()[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return empty($this->getParameter('errorCode'));
    }

    /**
     * @inheritDoc
     */
    public function getErrorCode(): ?int
    {
        return $this->getParameter('errorCode');
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): ?string
    {
        return $this->getParameter('errorMessage');
    }
}
