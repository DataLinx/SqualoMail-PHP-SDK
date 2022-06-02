<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\SubscribeByEmail;
use DataLinx\SqualoMail\Requests\Lists\UnsubscribeByEmail;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\GetRecipient;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class SubscribeByEmailTest extends AbstractTest
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
        $test_recipient = $cr->send()->getRecipient();

        // Test request
        $request = new SubscribeByEmail($this->api);
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

    public function testResubWithAccept()
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
        $unsub = new UnsubscribeByEmail($this->api);
        $unsub->list_ids[] = $test_list->id;
        $unsub->email = $cr->email;
        $unsub->accept = false;
        $this->assertTrue($unsub->send()->isSuccessful());

        // Test request
        $request = new SubscribeByEmail($this->api);
        $request->email = $cr->email;
        $request->list_ids[] = $test_list->id;
        $request->accept = true;

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

    public function testResubWithoutAccept()
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
        $unsub = new UnsubscribeByEmail($this->api);
        $unsub->list_ids[] = $test_list->id;
        $unsub->email = $cr->email;
        $unsub->accept = true;
        $this->assertTrue($unsub->send()->isSuccessful());

        // Test request
        $request = new SubscribeByEmail($this->api);
        $request->email = $cr->email;
        $request->list_ids[] = $test_list->id;
        $request->accept = false;

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