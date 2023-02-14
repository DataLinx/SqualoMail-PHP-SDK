<?php

namespace DataLinx\SqualoMail\Responses\Recipients;

use DataLinx\SqualoMail\Responses\CommonResponse;

class CreateRecipientResponse extends CommonResponse
{
    /**
     * Get created recipient - a generic object cast from JSON.
     * Example:
     *
     * <code>
     * {
            "accept": true,
            "confirmed": true,
            "confirmedDate": 1666339398,
            "confirmedIp": null,
            "created": 1666339398,
            "customAttributes": [
                {
                    "name": "custom_attribute",
                    "value": "Test value"
                }
            ],
            "email": "test3@datalinx.si",
            "enabled": true,
            "html": true,
            "id": 259,
            "ip": "",
            "lastClickDate": 0,
            "lastOpenDate": 0,
            "lastOpenIp": null,
            "lastSentDate": 0,
            "name": "",
            "phone": null,
            "source": "SYSTEM_API",
            "surname": "",
            "tags": [
                "test tag 2"
            ],
            "userId": 0
        }
     * </code>
     *
     * @return object
     */
    public function getRecipient(): object
    {
        return (object)$this->getParameter('recipient');
    }
}
