<?php

namespace DataLinx\SqualoMail\Requests\Recipients;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\EntityResponse;

/**
 * Recipient data is returned as an object. Example:
 *
 * {
    "__type": "AcymailingSubscriber:#SqualoMail.Data.ApplicationDatabase.Models",
    "accept": 1,
    "confirmed": 1,
    "confirmed_date": 1651057685,
    "confirmed_ip": null,
    "created": 1651057685,
    "email": "test@datalinx.si",
    "email_imported_value": "test@datalinx.si",
    "enabled": 1,
    "gdpr_can_send": 0,
    "gdpr_can_track": 0,
    "gender": 3,
    "html": 1,
    "ip": "255.255.255.255",
    "is_inactive": 0,
    "key": "87453059",
    "lastclick_date": 0,
    "lastopen_date": 0,
    "lastopen_ip": null,
    "lastsent_date": 0,
    "name": "",
    "name_imported_value": "",
    "needs_gdpr_consent": 1,
    "phone": null,
    "quality_score": 0,
    "source": "SYSTEM_API",
    "subid": 65,
    "surname": "",
    "userid": 0
    }
 */
class GetRecipient extends AbstractRequest {

	public string $email;

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
		return [
			'entity' => 'recipient',
			'filter' => 'email=="'. $this->email .'"',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getEndpoint(): string
	{
		return 'get-data';
	}

	/**
	 * @return EntityResponse
	 * @throws ValidationException
	 * @throws \DataLinx\SqualoMail\Exceptions\APIException
	 */
	public function send(): EntityResponse
	{
		return new EntityResponse($this->sendRequest(), $this);
	}
}