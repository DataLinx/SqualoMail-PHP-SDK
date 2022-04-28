<?php

namespace DataLinx\SqualoMail\Responses\Recipients;

use DataLinx\SqualoMail\Responses\CommonResponse;

class CreateRecipientResponse extends CommonResponse {

	/**
	 * Get created recipient - a generic object cast from JSON.
	 * Example:
	 *
	 * {
		"accept": true,
		"confirmed": true,
		"confirmedDate": 1650966173,
		"confirmedIp": null,
		"created": 1650966173,
		"customAttributes": null,
		"email": "test@example.com",
		"enabled": true,
		"html": true,
		"id": 3,
		"ip": "255.255.255.255",
		"lastClickDate": 0,
		"lastOpenDate": 0,
		"lastOpenIp": null,
		"lastSentDate": 0,
		"name": "",
		"source": "SYSTEM_API",
		"surname": "",
		"userId": 0
		}
	 *
	 * @return object
	 */
	public function getRecipient(): object
	{
		return (object)$this->getParameter('recipient');
	}
}