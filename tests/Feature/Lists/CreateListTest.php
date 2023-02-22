<?php

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Tests\AbstractTest;

class CreateListTest extends AbstractTest
{
    /**
     * @throws ValidationException
     * @throws APIException
     */
    public function testBasic(): void
    {
        $request = new CreateList($this->api);
        $request->name = 'PHPUnit Test list';
        $request->published = true;
        $request->description = 'Just a test list';
        $request->list_tags[] = 'Test tag';

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getErrorCode());
        $this->assertNull($response->getErrorMessage());
        $this->assertIsObject($response->getList());

        $list = $response->getList();

        $this->assertTrue(property_exists($list, 'name'), 'List does not have a "name" property.');
        $this->assertEquals('PHPUnit Test list', $list->name);

        $this->assertTrue(property_exists($list, 'listTags'), 'List does not have a "listTags" property.');
        $this->assertIsArray($list->listTags);
        $this->assertContains('Test tag', $list->listTags);

        // Cleanup
        $this->deleteTestList($list->id);
    }

    public function testValidation(): void
    {
        $request = new CreateList($this->api);

        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::CODE_ATTR_REQUIRED);
        $this->expectExceptionMessage('Attribute "name" is required');

        $request->validate();
    }
}
