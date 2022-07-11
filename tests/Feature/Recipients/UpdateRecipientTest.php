<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\UpdateRecipient;
use DataLinx\SqualoMail\Responses\Recipients\UpdateRecipientResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class UpdateRecipientTest extends AbstractTest
{
    public function testBasic()
    {
        // First, create the test object
        $test_email = rand() .'@example.com';

        $cr = new CreateRecipient($this->api);
        $cr->email = $test_email;
        $cr->name = 'Johnny';
        $cr->surname = 'Shmoxville';
        $cr->custom_attributes = [[
            'name' => 'custom_attribute',
            'value' => 'Test value',
        ]];

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

        $response = $request->send();

        $this->assertInstanceOf(UpdateRecipientResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getRecipient());

        $this->assertObjectHasAttribute('name', $response->getRecipient());
        $this->assertEquals('Riccardo', $response->getRecipient()->name);

        $this->assertObjectHasAttribute('surname', $response->getRecipient());
        $this->assertEquals('Delonghi', $response->getRecipient()->surname);

        // API does not return the custom attributes when updating, so we can't test it

        // Cleanup
        $this->deleteTestRecipient($cr_r->getRecipient()->id);
    }

    public function testValidation()
    {
        $request = new UpdateRecipient($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_EITHER_REQUIRED);
        $this->expectExceptionMessage('Either the "id" or "email" attribute is required');

        $request->validate();
    }
}