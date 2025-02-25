<?php

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    public function testGuest()
    {
        $action = new HelloAction;
        $request = new ServerRequest;
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, Guest', $response->getBody()->getContents());
    }

    public function testVasya()
    {
        $action = new HelloAction;
        $request = (new ServerRequest)->withQueryParams(['name' => 'Vasya']);
        $response = $action($request);
        
        self::assertEquals('Hello, Vasya', $response->getBody()->getContents());
    }
}
