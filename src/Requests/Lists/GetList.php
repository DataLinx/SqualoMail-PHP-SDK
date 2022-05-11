<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\EntityResponse;
use DataLinx\SqualoMail\Responses\ResponseInterface;

class GetList extends AbstractRequest
{
    /**
     * @var int|null List ID
     */
    public ?int $id;

    /**
     * @var string|null List name
     */
    public ?string $name;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (empty($this->id) and empty($this->name)) {
            throw new ValidationException(['id', 'name'], ValidationException::CODE_ATTR_EITHER_REQUIRED);
        }
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $data = [
            'entity' => 'list',
        ];

        if (isset($this->id)) {
            $data['filter'] = 'listId=='. $this->id;
        } elseif (isset($this->name)) {
            $data['filter'] = 'name=="'. $this->name .'"';
        }

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
     * Send request and return data as an associative array.
     *
     * Example:
     * {
        "__type": "AcymailingList:#SqualoMail.Data.ApplicationDatabase.Models",
        "AllSubscribersCount": null,
        "DisabledCount": null,
        "ListTags": null,
        "SubscribersCount": null,
        "UnsubscribersCount": null,
        "access_manage": "none",
        "access_sub": "all",
        "alias": "test-list",
        "color": "#ff6600",
        "created_on": 0,
        "description": "",
        "languages": "all",
        "listid": 1,
        "name": "Test list",
        "ordering": 2,
        "published": 1,
        "startrule": "0",
        "type": "list",
        "unsubmailid": 0,
        "userid": 254,
        "visible": 1,
        "welmailid": 0
        }
     *
     * @return EntityResponse
     */
    public function send(): EntityResponse
    {
        return new EntityResponse($this->sendRequest(), $this);
    }
}