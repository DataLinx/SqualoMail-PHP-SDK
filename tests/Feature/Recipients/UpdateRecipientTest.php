<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\UpdateRecipient;
use DataLinx\SqualoMail\Responses\Recipients\UpdateRecipientResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class UpdateRecipientTest extends AbstractTest
{
    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testBasic(): void
    {
        // First, create the test object
        $test_email = mt_rand() .'@example.com';

        $cr = new CreateRecipient($this->api);
        $cr->email = $test_email;
        $cr->name = 'Johnny';
        $cr->surname = 'Shmoxville';
        $cr->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];
        $cr->tags[] = 'Unit test tag 1';
        $cr->tags[] = 'Unit test tag 2';

        $cr_r = $cr->send();

        // Second, attempt to update the record
        $request = new UpdateRecipient($this->api);
        $request->email = $test_email;
        $request->name = 'Riccardo';
        $request->surname = 'Delonghi';
        $request->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Some other value',
        ]];
        $request->tags[] = 'Unit test tag 3';
        $request->tags[] = 'Unit test tag 4';

        $response = $request->send();

        $this->assertInstanceOf(UpdateRecipientResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getRecipient());

        $this->assertTrue(property_exists($response->getRecipient(), 'name'), 'Recipient does not have a "name" property.');
        $this->assertEquals('Riccardo', $response->getRecipient()->name);

        $this->assertTrue(property_exists($response->getRecipient(), 'surname'), 'Recipient does not have a "surname" property.');
        $this->assertEquals('Delonghi', $response->getRecipient()->surname);

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
        $this->assertEquals('Some other value', $attr['value']);

        // Assert tags
        $this->assertTrue(property_exists($response->getRecipient(), 'tags'), 'Recipient does not have a "tags" property.');
        $this->assertIsArray($response->getRecipient()->tags);

        $tags = $response->getRecipient()->tags;
        $this->assertEquals(['Unit test tag 3', 'Unit test tag 4'], $tags);

        // Cleanup
        $this->deleteTestRecipient($cr_r->getRecipient()->id);
    }

    public function testValidation(): void
    {
        $request = new UpdateRecipient($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_EITHER_REQUIRED);
        $this->expectExceptionMessage('Either the "id" or "email" attribute is required');

        $request->validate();
    }
}
