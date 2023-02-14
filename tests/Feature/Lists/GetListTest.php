<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\GetList;
use DataLinx\SqualoMail\Responses\EntityResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetListTest extends AbstractTest
{
    public function testById()
    {
        // Create test list
        $rq = new CreateList($this->api);
        $rq->name = 'Test list '. rand();
        $rq->published = true;
        $rs = $rq->send();

        $test_list = $rs->getList();

        // Test fetching the list
        $request = new GetList($this->api);
        $request->id = $test_list->id;
        $response = $request->send();

        $this->assertInstanceOf(EntityResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $this->assertArrayHasKey('name', $response->getData());
        $this->assertEquals($rq->name, $response->getData()['name']);

        // Cleanup
        $this->deleteTestList($test_list->id);
    }

    public function testByName()
    {
        // Create test list
        $rq = new CreateList($this->api);
        $rq->name = 'Test list '. rand();
        $rq->published = true;
        $rs = $rq->send();

        $test_list = $rs->getList();

        // Test fetching the list
        $request = new GetList($this->api);
        $request->name = $test_list->name;
        $response = $request->send();

        $this->assertInstanceOf(EntityResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getErrorCode());
        $this->assertNull($response->getErrorMessage());

        $this->assertArrayHasKey('name', $response->getData());
        $this->assertEquals($rq->name, $response->getData()['name']);

        // Cleanup
        $this->deleteTestList($test_list->id);
    }

    public function testValidation()
    {
        $request = new GetList($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_EITHER_REQUIRED);
        $this->expectExceptionMessage('Either the "id" or "name" attribute is required');

        $request->validate();
    }
}
