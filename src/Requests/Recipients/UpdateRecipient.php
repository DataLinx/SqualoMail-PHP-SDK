<?php

namespace DataLinx\SqualoMail\Requests\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\Recipients\UpdateRecipientResponse;
use DataLinx\SqualoMail\Responses\ResponseInterface;

/**
 * Update recipient - this is done by recipient's ID or email. You must provide one of those.
 * If you provide both, the recipient's email with the matching ID will be updated with the provided email.
 */
class UpdateRecipient extends AbstractRequest
{
    /**
     * @var int|null Recipient ID
     */
    public ?int $id;

    /**
     * @var string|null Recipient email address
     */
    public ?string $email;

    /**
     * @var string|null Recipient name
     */
    public ?string $name;

    /**
     * @var string|null Recipient surname
     */
    public ?string $surname;

    /**
     * @var array|null List IDs to immediately subscribe the recipient to
     */
    public ?array $list_ids;

    /**
     * @var bool|null Does the recipient receive HTML emails?
     */
    public ?bool $html;

    /**
     * @var bool|null Does the recipient agree to receive messages?
     */
    public ?bool $confirmed;

    /**
     * @var bool|null Is the recipient enabled?
     */
    public ?bool $enabled;

    /**
     * @var bool|null Does the recipient receive messages? (Ambiguous - not sure what SqualoMail means here)
     */
    public ?bool $accept;

    /**
     * A 2-d array of custom attributes in the format:
     *
     * <code>
     *      [[
     *          'name' => 'attr_name',
     *          'value' => 'Attribute value',
     *      ], [
     *          'name' => 'other_attr_name',
     *          'value' => 'Some other value',
     *      ]]
     * </code>
     *
     * @var array|null
     */
    public ?array $custom_attributes;

    /**
     * An array of tags (plain strings)
     *
     * @var string[]|null
     */
    public ?array $tags;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (! isset($this->id) and ! isset($this->email)) {
            throw new ValidationException(['id', 'email'], ValidationException::CODE_ATTR_EITHER_REQUIRED);
        }
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->readAttributes([
            'id',
            'email',
            'name',
            'surname',
            'list_ids' => 'listIds',
            'html',
            'confirmed',
            'enabled',
            'accept',
            'custom_attributes' => 'customAttributes',
            'tags',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'update-recipient';
    }

    /**
     * @return ResponseInterface
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): ResponseInterface
    {
        return new UpdateRecipientResponse($this->sendRequest(true), $this);
    }
}
