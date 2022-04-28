<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;

class DeleteList extends AbstractRequest
{
    /**
     * @var int List ID
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
            'listId' => $this->id,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'delete-list';
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