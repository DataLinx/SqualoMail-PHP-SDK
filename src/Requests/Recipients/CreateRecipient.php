<?php

namespace DataLinx\SqualoMail\Requests\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\Recipients\CreateRecipientResponse;

class CreateRecipient extends AbstractRequest {

	const GENDER_MALE = 'male';
	const GENDER_FEMALE = 'female';
	const GENDER_OTHER = 'other';

	/**
	 * @var string Recipient email address
	 */
	public string $email;

	/**
	 * @var string|null Recipient name
	 */
	public ?string $name;

	/**
	 * @var string|null Recipient surname
	 */
	public ?string $surname;

	/**
	 * @var string|null Recipient gender - see class constants for options
	 */
	public ?string $gender;

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
	 * @var bool|null GDPR acceptance for sending
	 */
	public ?bool $gdpr_can_send;

	/**
	 * @var bool|null GDPR acceptance for tracking
	 */
	public ?bool $gdpr_can_track;

	/**
	 * @var array|null Additional key-value pairs for custom attributes
	 */
	public ?array $custom_attributes;

	/**
	 * @inheritDoc
	 */
	public function validate(): void
	{
		$this->validateAttributes([
            'email',
        ]);
	}

	/**
	 * @inheritDoc
	 */
	public function getData(): array
	{
		return $this->readAttributes([
            'email',
            'name',
            'surname',
            'gender',
            'list_ids' => 'listIds',
            'html',
            'confirmed',
            'enabled',
            'accept',
            'gdpr_can_send' => 'gdprCanSend',
            'gdpr_can_track' => 'gdprCanTrack',
            'custom_attributes' => 'customAttributes',
        ]);
	}

	/**
	 * @inheritDoc
	 */
	public function getEndpoint(): string
	{
		return 'create-recipient';
	}

	/**
	 * @return CreateRecipientResponse
	 * @throws ValidationException
	 * @throws APIException
	 */
	public function send(): CreateRecipientResponse
	{
		return new CreateRecipientResponse($this->sendRequest(true), $this);
	}
}