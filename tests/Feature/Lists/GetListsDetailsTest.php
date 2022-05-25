<?php declare(strict_types=1);

namespace DataLinx\SqualoMail\Tests\Feature\Lists;

use DataLinx\SqualoMail\Requests\Lists\CreateList;
use DataLinx\SqualoMail\Requests\Lists\GetListsDetails;
use DataLinx\SqualoMail\Requests\Recipients\CreateRecipient;
use DataLinx\SqualoMail\Responses\CommonResponse;
use DataLinx\SqualoMail\Tests\AbstractTest;

class GetListsDetailsTest extends AbstractTest
{
    public function testBasic(): void
    {
        // Create test list
        $rq = new CreateList($this->api);
        $rq->name = 'Test list '. rand();
        $rq->published = true;
        $rs = $rq->send();

        $test_list = $rs->getList();

        // Create test recipient
        $cr = new CreateRecipient($this->api);
        $cr->email = rand() .'@example.com';
        $cr->list_ids[] = $test_list->id;

        $cr_r = $cr->send();

        // Test list details fetching
        $request = new GetListsDetails($this->api);
        $request->list_ids[] = $test_list->id;

        $response = $request->send();

        $this->assertInstanceOf(CommonResponse::class, $response);
        $this->assertTrue($response->isSuccessful());

        $this->assertIsArray($response->getParameter('lists'));
        $this->assertCount(1, $response->getParameter('lists'));

        $details = $response->getParameter('lists')[0];

        $this->assertArrayHasKey('name', $details);
        $this->assertEquals($rq->name, $details['name']);
        $this->assertArrayHasKey('subscribersCount', $details);
        $this->assertEquals(1, $details['subscribersCount']);

        // Cleanup
        $this->deleteTestRecipient($cr_r->getRecipient()->id);
        $this->deleteTestList($test_list->id);
    }
}