<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\UnsubscribeByEmail;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\GetRecipient;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class UnsubscribeByEmailTest extends AbstractTest
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
        $cr->list_ids[] = $test_list->id;
        $test_recipient = $cr->send()->getRecipient();

        // Test request
        $request = new UnsubscribeByEmail($this->api);
        $request->email = $cr->email;
        $request->list_ids[] = $test_list->id;

        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }

    public function testUnsubWithAccept()
    {
        // Prepare test list
        $cl = new CreateList($this->api);
        $cl->name = 'Test list';
        $cl->published = true;
        $test_list = $cl->send()->getList();

        // Prepare test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = rand() .'@example.com';
        $cr->list_ids[] = $test_list->id;
        $test_recipient = $cr->send()->getRecipient();

        // Unsubscribe the recipient with accept=true
        $request = new UnsubscribeByEmail($this->api);
        $request->list_ids[] = $test_list->id;
        $request->email = $cr->email;
        $request->accept = 1;
        $this->assertTrue($request->send()->isSuccessful());

        // Get recipient details
        $gr = new GetRecipient($this->api);
        $gr->email = $cr->email;

        $recipient_details = $gr->send();

        $this->assertTrue($recipient_details->isSuccessful());
        $this->assertIsArray($recipient_details->getData());
        $this->assertArrayHasKey('accept', $recipient_details->getData());
        $this->assertEquals(1, $recipient_details->getParameter('accept'));

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }

    public function testUnsubWithoutAccept()
    {
        // Prepare test list
        $cl = new CreateList($this->api);
        $cl->name = 'Test list';
        $cl->published = true;
        $test_list = $cl->send()->getList();

        // Prepare test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = rand() .'@example.com';
        $cr->list_ids[] = $test_list->id;
        $test_recipient = $cr->send()->getRecipient();

        // Unsubscribe the recipient with accept=false
        $request = new UnsubscribeByEmail($this->api);
        $request->list_ids[] = $test_list->id;
        $request->email = $cr->email;
        $request->accept = 0;
        $this->assertTrue($request->send()->isSuccessful());

        // Get recipient details
        $gr = new GetRecipient($this->api);
        $gr->email = $cr->email;

        $recipient_details = $gr->send();

        $this->assertTrue($recipient_details->isSuccessful());
        $this->assertIsArray($recipient_details->getData());
        $this->assertArrayHasKey('accept', $recipient_details->getData());
        $this->assertEquals(0, $recipient_details->getParameter('accept'));

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }
}
