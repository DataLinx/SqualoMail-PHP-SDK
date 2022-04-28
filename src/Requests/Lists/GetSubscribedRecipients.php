<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\Lists\GetSubscribedRecipientsResponse;

class GetSubscribedRecipients extends AbstractRequest
{
    public int $list_id;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validateAttributes([
            'list_id',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return [
            'listId' => $this->list_id,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'get-subscribed-recipients';
    }

    /**
     * @return GetSubscribedRecipientsResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): GetSubscribedRecipientsResponse
    {
        return new GetSubscribedRecipientsResponse($this->sendRequest(), $this);
    }
}