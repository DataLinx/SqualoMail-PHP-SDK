<?php

namespace DataLinx\SqualoMail\Responses\Recipients;

use DataLinx\SqualoMail\Responses\CommonResponse;

class UpdateRecipientResponse extends CommonResponse
{
    /**
     * Get updated recipient - a generic object cast from JSON.
     * Example:
     *
     * <code>
     * {
        "accept": true,
        "confirmed": true,
        "confirmedDate": 1657522018,
        "confirmedIp": null,
        "created": 1657522018,
        "customAttributes": [
            {
                "name": "custom_attribute",
                "value": "Test value 123"
            }
        ],
        "email": "test2@datalinx.si",
        "enabled": true,
        "html": true,
        "id": 258,
        "ip": "",
        "lastClickDate": 0,
        "lastOpenDate": 0,
        "lastOpenIp": null,
        "lastSentDate": 0,
        "name": "Rikardo",
        "phone": null,
        "source": "SYSTEM_API",
        "surname": "",
        "tags": [
            "Test tag 1"
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
