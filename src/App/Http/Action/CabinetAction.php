<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthActionMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        $username = $request->getAttribute(BasicAuthActionMiddleware::ATTRIBUTE);
        return new HtmlResponse('Я залогинен как ' . $username);
    }
}
