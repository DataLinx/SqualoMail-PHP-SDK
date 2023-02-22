<?php

namespace DataLinx\SqualoMail\Tests;

use DataLinx\SqualoMail\API;
use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\DeleteList;
use DataLinx\SqualoMail\Requests\Recipients\DeleteRecipient;
use PHPUnit\Framework\TestCase;

class AbstractTest extends TestCase
{
    protected API $api;

    public function setUp(): void
    {
        parent::setUp();

        $this->api = new API(getenv('squalomail.api_key'));
    }

    /**
     * Delete the recipient that was created by the test
     *
     * @param int $id
     * @return void
     */
    protected function deleteTestRecipient(int $id): void
    {
        $request = new DeleteRecipient($this->api);
        $request->id = $id;
        $request->send();
    }

    /**
     * Delete the list that was created by the test
     *
     * @param int $id
     * @return void
     * @throws APIException
     * @throws ValidationException
     */
    protected function deleteTestList(int $id): void
    {
        $request = new DeleteList($this->api);
        $request->id = $id;
        $request->send();
    }
}
