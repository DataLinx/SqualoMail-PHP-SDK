<?php

namespace DataLinx\SqualoMail\Responses\Lists;

use DataLinx\SqualoMail\Responses\CommonResponse;

class GetSubscribedRecipientsResponse extends CommonResponse
{
    /**
     * Get an 2-d array for recipient records. Example record:
     * [
     *      "accept": true,
            "confirmed": false,
            "confirmedDate": 0,
            "confirmedIp": null,
            "created": 1650959846,
            "customAttributes": null,
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
     * @return array
     */
    public function getRecipients()
    {
        return $this->getParameter('recipientList');
    }
}