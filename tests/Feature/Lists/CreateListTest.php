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
        $request->name = 'PHPUnit Test list';
        $request->published = true;
        $request->description = 'Just a test list';
        $request->list_tags[] = 'Test tag';

        $response = $request->send();

        $this->assertInstanceOf(CreateListResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getList());

        $list = $response->getList();

        $this->assertObjectHasAttribute('name', $list);
        $this->assertEquals('PHPUnit Test list', $list->name);

        $this->assertObjectHasAttribute('listTags', $list);
        $this->assertIsArray($list->listTags);
        $this->assertContains('Test tag', $list->listTags);

        // Cleanup
        $this->deleteTestList($list->id);
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