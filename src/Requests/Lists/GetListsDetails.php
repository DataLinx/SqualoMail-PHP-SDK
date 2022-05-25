<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;

class GetListsDetails extends AbstractRequest
{
    /**
     * @var array|null Optionally, return lists with the specified IDs only
     */
    public ?array $list_ids;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        // Nothing to do, since there are no require parameters
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $data = [];

        if (isset($this->list_ids)) {
            $data['listIds'] = array_map('intval', $this->list_ids);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'get-lists-details';
    }

    /**
     * Send request.
     * The response will contain a "lists" parameter, which is an array of lists with the following format:
     * <code>
     * {
            "alias": "test-list",
            "color": "#ff6600",
            "description": "",
            "id": 1,
            "name": "Test list",
            "ordering": 2,
            "published": true,
            "tags": null,
            "type": "list",
            "unsubscribeMailId": 0,
            "visible": true,
            "welcomeMailId": 0,
            "disabledCount": 0,
            "subscribersCount": 5,
            "totalCount": 6,
            "unsubscribersCount": 1
        }
     * </code>
     *
     * @return CommonResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): CommonResponse
    {
        return new CommonResponse($this->sendRequest(true), $this);
    }
}