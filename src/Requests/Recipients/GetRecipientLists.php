<?php

namespace DataLinx\SqualoMail\Requests\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;
use Exception;

/**
 * Get recipient lists.
 *
 * The response data array will be an 2-d associative array with the following format:
 *  [
        {
        "__type": "AcymailingListsub:#SqualoMail.Data.ApplicationDatabase.Models",
        "listid": 1,
        "status": 1,
        "subdate": 1651057685,
        "subid": 65,
        "unsubdate": null
        }
    ]
 *
 * The recipient is actively subscribed to lists with status=1 only.
 */
class GetRecipientLists extends AbstractRequest
{
    /**
     * @var int|null Recipient ID
     */
    public ?int $recipient_id;

    /**
     * @var string|null Recipient email
     */
    public ?string $recipient_email;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (empty($this->recipient_id) and empty($this->recipient_email)) {
            throw new ValidationException(['recipient_id', 'recipient_email'], ValidationException::CODE_ATTR_EITHER_REQUIRED);
        }
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $data = [
            'entity' => 'ListSub',
        ];

        if (isset($this->recipient_id)) {
            $recipient_id = $this->recipient_id;
        } else {
            $gr = new GetRecipient($this->api);
            $gr->email = $this->recipient_email;

            try {
                $recipient = $gr->send()->getData();

                if (empty($recipient['subid'])) {
                    throw new Exception('subId empty');
                }

                $recipient_id = $recipient['subid'];
            } catch (Exception $exception) {
                throw new Exception('Could not fetch recipient ID for provided email: '. $exception->getMessage());
            }
        }

        $data['filter'] = 'subId='. $recipient_id;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'get-data';
    }

    /**
     * @return CommonResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): CommonResponse
    {
        return new CommonResponse($this->sendRequest(), $this);
    }
}