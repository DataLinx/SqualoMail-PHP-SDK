<?php

namespace DataLinx\SqualoMail\Responses;

/**
 * This generic response is used for responses where a single object is expected as a result.
 */
class EntityResponse extends CommonResponse
{
    /**
     * @inheritDoc
     */
    public function getData(): ?array
    {
        if (is_array(parent::getData()) and count(parent::getData()) === 1) {
            return parent::getData()[0];
        }

        return null;
    }
}
