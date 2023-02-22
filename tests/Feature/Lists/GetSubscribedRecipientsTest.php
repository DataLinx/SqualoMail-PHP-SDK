<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\GetSubscribedRecipients;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetSubscribedRecipientsTest extends AbstractTest
{
    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testBasic(): void
    {
        // Prepare test list
        $cl = new CreateList($this->api);
        $cl->name = 'Test list';
        $cl->published = true;
        $test_list = $cl->send()->getList();

        // Prepare test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = mt_rand() .'@example.com';
        $cr->list_ids = [$test_list->id];
        $cr->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];
        $test_recipient = $cr->send()->getRecipient();

        // Test recipients
        $request = new GetSubscribedRecipients($this->api);
        $request->list_id = $test_list->id;

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $recipients = $response->getRecipients();

        $this->assertIsArray($recipients);
        $this->assertCount(1, $recipients);

        $data = $recipients[0];

        $this->assertIsArray($data);
        $this->assertArrayHasKey('email', $data);
        $this->assertEquals($cr->email, $data['email']);

        // Assert custom attributes
        $this->assertArrayHasKey('customAttributes', $data);
        $this->assertIsArray($data['customAttributes']);
        $this->assertGreaterThan(0, count($data['customAttributes']));

        $attr = end($data['customAttributes']);
        $this->assertIsArray($attr);
        $this->assertArrayHasKey('name', $attr);
        $this->assertEquals('custom_attribute', $attr['name']);
        $this->assertArrayHasKey('value', $attr);
        $this->assertEquals('Test value', $attr['value']);

        // Cleanup
        $this->deleteTestRecipient($test_recipient->id);
        $this->deleteTestList($test_list->id);
    }
}
