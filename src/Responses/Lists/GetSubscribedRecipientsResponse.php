<?php

namespace DataLinx\SqualoMail\Responses\Lists;

use DataLinx\SqualoMail\Responses\CommonResponse;

class GetSubscribedRecipientsResponse extends CommonResponse
{
    /**
     * Get a 2-d array for recipient records. Example record:
     *
     * <code>
     * [
     *      "accept": true,
            "confirmed": false,
            "confirmedDate": 0,
            "confirmedIp": null,
            "created": 1650959846,
            "customAttributes": [
                {
                    "name": "custom_attribute",
                    "value": "Test value"
                }
            ],
            "email": "test@example.com",
            "enabled": true,
            "html": true,
            "id": 1,
            "ip": null,
            "lastClickDate": 0,
            "lastOpenDate": 0,
            "lastOpenIp": null,
            "lastSentDate": 0,
            "name": "",
            "source": "url",
            "surname": "",
            "userId": 0
     * ]
     * </code>
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->getParameter('recipientList');
    }
}
