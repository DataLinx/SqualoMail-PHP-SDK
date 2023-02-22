<?php

declare(strict_types=1);

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\API;
use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Responses\Recipients\CreateRecipientResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class CreateRecipientTest extends AbstractTest
{
    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testBasic(): void
    {
        $this->assertIsObject($this->api);
        $this->assertInstanceOf(API::class, $this->api);
        $this->assertEquals(getenv('squalomail.api_key'), $this->api->api_key);

        $test_email = rand() .'@example.com';

        $request = new CreateRecipient($this->api);
        $request->email = $test_email;
        $request->name = 'Johnny';
        $request->ip = '123.123.123.123';
        $request->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];
        $request->tags[] = 'Unit test tag 1';
        $request->tags[] = 'Unit test tag 2';

        $response = $request->send();

        $this->assertInstanceOf(CreateRecipientResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getRecipient());

        $this->assertTrue(property_exists($response->getRecipient(), 'email'), 'Recipient does not have an "email" property.');
        $this->assertEquals($test_email, $response->getRecipient()->email);

        $this->assertTrue(property_exists($response->getRecipient(), 'name'), 'Recipient does not have a "name" property.');
        $this->assertEquals('Johnny', $response->getRecipient()->name);

        $this->assertTrue(property_exists($response->getRecipient(), 'ip'), 'Recipient does not have an "ip" property.');
        $this->assertEquals($request->ip, $response->getRecipient()->ip);

        // Assert custom attributes
        $this->assertTrue(property_exists($response->getRecipient(), 'customAttributes'), 'Recipient does not have a "customAttributes" property.');
        $this->assertIsArray($response->getRecipient()->customAttributes);

        $custom_attrs = $response->getRecipient()->customAttributes;
        $this->assertCount(1, $custom_attrs);
        $attr = $custom_attrs[0];
        $this->assertIsArray($attr);
        $this->assertArrayHasKey('name', $attr);
        $this->assertEquals('custom_attribute', $attr['name']);
        $this->assertArrayHasKey('value', $attr);
        $this->assertEquals('Test value', $attr['value']);

        // Assert tags
        $this->assertTrue(property_exists($response->getRecipient(), 'tags'), 'Recipient does not have a "tags" property.');
        $this->assertIsArray($response->getRecipient()->tags);

        $tags = $response->getRecipient()->tags;
        $this->assertEquals(['Unit test tag 1', 'Unit test tag 2'], $tags);

        // Cleanup
        $this->deleteTestRecipient($response->getRecipient()->id);
    }
}
