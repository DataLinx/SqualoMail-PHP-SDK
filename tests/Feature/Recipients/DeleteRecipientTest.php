<?php

namespace DataLinx\SqualoMail\Tests\Feature\Recipients;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Requests\Recipients\DeleteRecipient;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class DeleteRecipientTest extends AbstractTest
{
    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testBasic(): void
    {
        // First, create the test object
        $test_email = rand() .'@example.com';

        $cr = new CreateRecipient($this->api);
        $cr->email = $test_email;

        $cr_r = $cr->send();

        // Now test the Delete request
        $request = new DeleteRecipient($this->api);
        $request->id = $cr_r->getRecipient()->id;

        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
    }

    public function testValidation(): void
    {
        $request = new DeleteRecipient($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "id" is required');

        $request->validate();
    }
}
