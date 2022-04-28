<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;

class SubscribeByEmail extends AbstractRequest
{
    /**
     * @var string Recipient's email
     */
    public string $email;

    /**
     * @var int[] List IDs to subscribe to
     */
    public array $list_ids;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validateAttributes([
            'email',
            'list_ids',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->readAttributes([
            'email',
            'list_ids' => 'listIds',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'subscribe-recipient-by-email';
    }

    /**
     * @return CommonResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): CommonResponse
    {
        return new CommonResponse($this->sendRequest(true), $this);
    }
}