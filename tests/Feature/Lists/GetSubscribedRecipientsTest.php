<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\GetSubscribedRecipients;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Responses\Lists\GetSubscribedRecipientsResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetSubscribedRecipientsTest extends AbstractTest
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

        // Test recipients
        $request = new GetSubscribedRecipients($this->api);
        $request->list_id = $test_list->id;

        $response = $request->send();

        $this->assertInstanceOf(GetSubscribedRecipientsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $recipients = $response->getRecipients();

        $this->assertIsArray($recipients);
        $this->assertCount(1, $recipients);
        $this->assertIsArray($recipients[0]);
        $this->assertArrayHasKey('email', $recipients[0]);
        $this->assertEquals($cr->email, $recipients[0]['email']);

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }
}