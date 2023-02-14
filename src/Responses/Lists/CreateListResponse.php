<?php

namespace DataLinx\SqualoMail\Responses\Lists;

use DataLinx\SqualoMail\Responses\CommonResponse;

class CreateListResponse extends CommonResponse
{
    /**
     * Get created List.
     *
     * Example object:
     * {
        "alias": "test-list",
        "color": "#935B20",
        "description": null,
        "id": 6,
        "name": "Test list",
        "ordering": 6,
        "published": true,
        "tags": [],
        "type": "list",
        "unsubscribeMailId": null,
        "visible": false,
        "welcomeMailId": null
        }
     *
     * @return object
     */
    public function getList(): object
    {
        return (object)$this->getParameter('list');
    }
}
