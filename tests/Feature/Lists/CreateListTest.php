<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Responses\Lists\CreateListResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class CreateListTest extends AbstractTest
{
    public function testBasic()
    {
        $request = new CreateList($this->api);
        $request->name = 'Test list';
        $request->published = true;
        $request->description = 'Just a test list';

        $response = $request->send();

        $this->assertInstanceOf(CreateListResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getList());

        $this->assertObjectHasAttribute('name', $response->getList());
        $this->assertEquals('Test list', $response->getList()->name);

        // Cleanup
        $this->deleteTestList($response->getList()->id);
    }

    public function testValidation()
    {
        $request = new CreateList($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "name" is required');

        $request->validate();
    }
}