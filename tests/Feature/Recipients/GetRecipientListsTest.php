<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\GetRecipientLists;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetRecipientListsTest extends AbstractTest
{
    public function testBasic()
    {
        // Prepare test list
        $cl = new CreateList($this->api);
        $cl->name = 'Test list';
        $cl->published = true;
        $test_list = $cl->send()->getList();

        // Prepare test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = rand() .'@example.com';
        $cr->list_ids = [$test_list->id];
        $test_recipient = $cr->send()->getRecipient();

        // Test request
        $request = new GetRecipientLists($this->api);
        $request->recipient_id = $test_recipient->id;

        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $lists = $response->getData();

        $this->assertIsArray($lists);
        $this->assertCount(1, $lists);
        $this->assertIsArray($lists[0]);
        $this->assertArrayHasKey('status', $lists[0]);
        $this->assertEquals(1, $lists[0]['status']);

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }

    public function testByEmail()
    {
        // Prepare test list
        $cl = new CreateList($this->api);
        $cl->name = 'Test list';
        $cl->published = true;
        $test_list = $cl->send()->getList();

        // Prepare test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = rand() .'@example.com';
        $cr->list_ids = [$test_list->id];
        $test_recipient = $cr->send()->getRecipient();

        // Test request
        $request = new GetRecipientLists($this->api);
        $request->recipient_email = $cr->email;

        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $lists = $response->getData();

        $this->assertIsArray($lists);
        $this->assertCount(1, $lists);
        $this->assertIsArray($lists[0]);
        $this->assertArrayHasKey('status', $lists[0]);
        $this->assertEquals(1, $lists[0]['status']);

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }

    public function testValidation()
    {
        $request = new GetRecipientLists($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_EITHER_REQUIRED);
        $this->expectExceptionMessage('Either the "recipient_id" or "recipient_email" attribute is required');

        $request->validate();
    }
}
