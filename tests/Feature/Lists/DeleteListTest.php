<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\DeleteList;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class DeleteListTest extends AbstractTest
{
    public function testBasic()
    {
        // Create test list
        $rq = new CreateList($this->api);
        $rq->name = 'Test list '. rand();
        $rq->published = true;
        $rs = $rq->send();

        $list = $rs->getList();

        // Test deletion
        $request = new DeleteList($this->api);
        $request->id = $list->id;
        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
    }

    public function testValidation()
    {
        $request = new DeleteList($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "id" is required');

        $request->validate();
    }
}
