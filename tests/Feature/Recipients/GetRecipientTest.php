<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\GetRecipient;
use DataLinx\SqualoMail\Responses\EntityResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetRecipientTest extends AbstractTest {

	public function testBasic()
	{
		// First, create the test object
		$test_email = rand() .'@example.com';

		$cr = new CreateRecipient($this->api);
		$cr->email = $test_email;
        $cr->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];

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
        $this->assertCount(1, $data['user_defined_fields']);
        $attr = $data['user_defined_fields'][0];
        $this->assertIsArray($attr);
        $this->assertArrayHasKey('Key', $attr);
        $this->assertEquals('custom_attribute', $attr['Key']);
        $this->assertArrayHasKey('Value', $attr);
        $this->assertEquals('Test value', $attr['Value']);

        // Cleanup
        $this->deleteTestRecipient($cr_r->getRecipient()->id);
	}

    public function testNonExisting()
    {
        $request = new GetRecipient($this->api);
        $request->email = 'someone@example.com';

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getData());
    }

    public function testValidation()
    {
        $request = new GetRecipient($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "email" is required');

        $request->validate();
    }
}