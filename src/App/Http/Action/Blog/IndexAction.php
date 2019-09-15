<?php

namespace App\Http\Action\Blog;

use Zend\Diactoros\Response\JsonResponse;

class IndexAction
{
    public function __invoke()
    {
        return new JsonResponse([
            ['id' => 1, 'title' => 'The First Post'],
            ['id' => 2, 'title' => 'The Second Post'],
        ]);
    }
}
