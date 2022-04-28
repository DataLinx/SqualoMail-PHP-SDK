<?php

namespace DataLinx\SqualoMail\Requests\Recipients;

use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;

class DeleteRecipient extends AbstractRequest
{
    /**
     * Recipient ID
     *
     * @var int
     */
    public int $id;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validateAttributes([
            'id',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return [
            'recipientId' => $this->id,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'delete-recipient';
    }

    /**
     * @inheritDoc
     */
    public function send(): CommonResponse
    {
        return new CommonResponse($this->sendRequest(), $this);
    }
}