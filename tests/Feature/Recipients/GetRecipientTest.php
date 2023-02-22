<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\GetRecipient;
use DataLinx\SqualoMail\Responses\EntityResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetRecipientTest extends AbstractTest
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
        $cr->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];
        $cr->tags[] = 'Unit test tag 1';
        $cr->tags[] = 'Unit test tag 2';

        $cr_r = $cr->send();

        // Second, attempt to fetch the record
        $request = new GetRecipient($this->api);
        $request->email = $test_email;

        $response = $request->send();

        $this->assertInstanceOf(EntityResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertInstanceOf(GetRecipient::class, $response->getRequest());
        $this->assertIsArray($response->getData());
        $this->assertArrayHasKey('email', $response->getData());
        $this->assertEquals($test_email, $response->getData()['email']);

        // Assert custom attributes
        $data = $response->getData();
        $this->assertArrayHasKey('user_defined_fields', $data);
        $this->assertIsArray($data['user_defined_fields']);
        $this->assertGreaterThan(0, count($data['user_defined_fields']));
        $attr = end($data['user_defined_fields']);
        $this->assertIsArray($attr);
        $this->assertArrayHasKey('Key', $attr);
        $this->assertEquals('custom_attribute', $attr['Key']);
        $this->assertArrayHasKey('Value', $attr);
        $this->assertEquals('Test value', $attr['Value']);

        // Assert tags
        $this->assertArrayHasKey('tags', $data);
        $this->assertIsArray($data['tags']);
        $this->assertEquals(['Unit test tag 1', 'Unit test tag 2'], $data['tags']);

        // Cleanup
        $this->deleteTestRecipient($cr_r->getRecipient()->id);
    }

    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testNonExisting(): void
    {
        $request = new GetRecipient($this->api);
        $request->email = 'someone@example.com';

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getData());
    }

    public function testValidation(): void
    {
        $request = new GetRecipient($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "email" is required');

        $request->validate();
    }
}
