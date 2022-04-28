<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\Lists\CreateListResponse;

class CreateList extends AbstractRequest
{
    /**
     * @var string List name
     */
    public string $name;

    /**
     * @var string|null Description
     */
    public ?string $description;

    /**
     * @var string|null List tag/category
     */
    public ?string $list_tag;

    /**
     * @var string|null Color in HEX code
     */
    public ?string $color;

    /**
     * @var int|null Order number for list sorting
     */
    public ?int $ordering;

    /**
     * @var bool Is the list published
     */
    public bool $published;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validateAttributes([
            'name',
            'published',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->readAttributes([
            'name',
            'description',
            'list_tag' => 'listTag',
            'color',
            'ordering',
            'published',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'create-list';
    }

    /**
     * @return CreateListResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): CreateListResponse
    {
        return new CreateListResponse($this->sendRequest(true), $this);
    }
}