<?php

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    public function testSiccess()
    {
        $action = new IndexAction;
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(json_encode([
            ['id' => 1, 'title' => 'The First Post'],
            ['id' => 2, 'title' => 'The Second Post'],
        ]), $response->getBody()->getContents());
    }
}
