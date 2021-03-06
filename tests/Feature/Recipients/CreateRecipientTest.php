<?php declare(strict_types=1);

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\API;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Responses\Recipients\CreateRecipientResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class CreateRecipientTest extends AbstractTest {

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

		$response = $request->send();

		$this->assertInstanceOf(CreateRecipientResponse::class, $response);
		$this->assertTrue($response->isSuccessful());
		$this->assertEquals(0, $response->getErrorCode());
		$this->assertNull($response->getErrorMessage());
		$this->assertIsObject($response->getRecipient());

		$this->assertObjectHasAttribute('email', $response->getRecipient());
		$this->assertEquals($test_email, $response->getRecipient()->email);

        $this->assertObjectHasAttribute('name', $response->getRecipient());
        $this->assertEquals('Johnny', $response->getRecipient()->name);

        $this->assertObjectHasAttribute('ip', $response->getRecipient());
        $this->assertEquals($request->ip, $response->getRecipient()->ip);

        // Assert custom attributes
        $this->assertObjectHasAttribute('customAttributes', $response->getRecipient());
        $this->assertIsArray($response->getRecipient()->customAttributes);

        $custom_attrs = $response->getRecipient()->customAttributes;
        $this->assertCount(1, $custom_attrs);
        $attr = $custom_attrs[0];
        $this->assertIsArray($attr);
        $this->assertArrayHasKey('name', $attr);
        $this->assertEquals('custom_attribute', $attr['name']);
        $this->assertArrayHasKey('value', $attr);
        $this->assertEquals('Test value', $attr['value']);

        // Cleanup
        $this->deleteTestRecipient($response->getRecipient()->id);
	}

}